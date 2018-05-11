<?php

namespace Seat\Kassie\Calendar\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\RequestFailedException;
use Seat\Eveapi\Models\RefreshToken;
use Seat\Kassie\Calendar\Models\Pap;
use Seat\Notifications\Models\Integration;
use Seat\Services\Repositories\Configuration\UserRespository;
use Seat\Services\Repositories\Character\Character;
use Seat\Web\Http\Controllers\Controller;
use Seat\Kassie\Calendar\Models\Operation;
use Seat\Kassie\Calendar\Models\Attendee;
use Seat\Kassie\Calendar\Models\Tag;
use Seat\Web\Models\Acl\Role;


/**
 * Class OperationController
 * @package Seat\Kassie\Calendar\Http\Controllers
 */
class OperationController extends Controller
{
    use UserRespository, Character;

    /**
     * OperationController constructor.
     */
    public function __construct() {
        $this->middleware('bouncer:calendar.view')->only('index');
        $this->middleware('bouncer:calendar.create')->only('store');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Seat\Services\Exceptions\SettingException
     */
    public function index(Request $request)
    {
        $notification_channels = Integration::where('type', 'slack')->get();

        $ops = Operation::all()->take(-50)->filter(function($op){
            return $op->isUserGranted(auth()->user());
        });

        $tags = Tag::all()->sortBy('order');

        $ops_incoming = $ops->filter(function($op) {
            return $op->status == "incoming";
        });

        $ops_ongoing = $ops->filter(function($op) {
            return $op->status == "ongoing";
        });

        $ops_faded = $ops->filter(function($op) {
            return $op->status == "faded" || $op->status == "cancelled";
        });

        $roles = Role::orderBy('title')->get();
        $userCharacters = auth()->user()->group->users->sortBy('name');
        $mainCharacter = auth()->user()->group->main_character->character;

        if($mainCharacter != null) {
            $mainCharacter['main'] = true;
            $userCharacters = $userCharacters->reject(function ($character) use ($mainCharacter) {
                return $character->id == $mainCharacter['character_id'];
            });
            $userCharacters->prepend($mainCharacter);
        }

        return view('calendar::operation.index', [
            'roles'          => $roles,
            'userCharacters' => $userCharacters,
            'ops_all' => $ops,
            'ops_incoming' => $ops_incoming,
            'ops_ongoing' => $ops_ongoing,
            'ops_faded' => $ops_faded,
            'default_op' => $request->id ? $request->id : 0,
            'tags' => $tags,
            'notification_channels' => $notification_channels,
        ]);
    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'importance' => 'required|between:0,5',
            'known_duration' => 'required',
            'time_start' => 'required_without_all:time_start_end|date|after_or_equal:today',
            'time_start_end' => 'required_without_all:time_start'
        ]);

        $operation = new Operation($request->all());
        $tags = array();

        foreach ($request->toArray() as $name => $value) {
            if (empty($value))
                $operation->{$name} = null;
            else if (strpos($name, 'checkbox-') !== false) {
                $tags[] = $value;
            }
        }

        if ($request->known_duration == "no")
            $operation->start_at = Carbon::parse($request->time_start);
        else {
            $dates = explode(" - ", $request->time_start_end);
            $operation->start_at = Carbon::parse($dates[0]);
            $operation->end_at = Carbon::parse($dates[1]);
        }
        $operation->start_at = Carbon::parse($operation->start_at);

        if ($request->importance == 0)
            $operation->importance = 0;

        $operation->integration_id = ($request->get('integration_id') == "") ?
            null : $request->get('integration_id');

        $operation->user()->associate(auth()->user());

        $operation->save();

        $operation->tags()->attach($tags);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'importance' => 'required|between:0,5',
            'known_duration' => 'required',
            'time_start' => 'required_without_all:time_start_end|date|after_or_equal:today',
            'time_start_end' => 'required_without_all:time_start'
        ]);

        $operation = Operation::find($request->operation_id);
        $tags = array();

        if (auth()->user()->has('calendar.updateAll') || $operation->user->id == auth()->user()->id) {

            foreach ($request->toArray() as $name => $value) {
                if (empty($value))
                    $operation->{$name} = null;
                else if (strpos($name, 'checkbox-') !== false)
                    $tags[] = $value;
            }

            $operation->title           = $request->title;
            $operation->role_name       = ($request->role_name == "") ? null : $request->role_name;
            $operation->importance      = $request->importance;
            $operation->description     = $request->description;
            $operation->staging_sys     = $request->staging_sys;
            $operation->staging_info    = $request->staging_info;
            $operation->staging_sys_id  = $request->staging_sys_id == null ? null : $request->staging_sys_id;
            $operation->fc              = $request->fc;
            $operation->fc_character_id = $request->fc_character_id == null ? null : $request->fc_character_id;

            if ($request->known_duration == "no") {
                $operation->start_at = Carbon::parse($request->time_start);
                $operation->end_at = null;
            } else {
                $dates = explode(" - ", $request->time_start_end);
                $operation->start_at = Carbon::parse($dates[0]);
                $operation->end_at = Carbon::parse($dates[1]);
            }

            $operation->start_at = Carbon::parse($operation->start_at);

            if ($request->importance == 0)
                $operation->importance = 0;

            $operation->integration_id = ($request->get('integration_id') == "") ?
                null : $request->get('integration_id');

            $operation->save();

            $operation->tags()->sync($tags);

            return $operation;
        }

        return redirect()
            ->back()
            ->with('error', 'An error occurred while processing the request.');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        $operation = Operation::find($request->operation_id);
        if (auth()->user()->has('calendar.deleteAll') || $operation->user->id == auth()->user()->id) {
            if ($operation != null) {

                if (! $operation->isUserGranted(auth()->user()))
                    return redirect()->back()->with('error', 'You are not granted to this operation !');

                Operation::destroy($operation->id);
                return redirect()->route('operation.index');
            }
        }

        return redirect()
            ->back()
            ->with('error', 'An error occurred while processing the request.');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function close(Request $request)
    {
        $operation = Operation::find($request->operation_id);
        if (auth()->user()->has('calendar.closeAll') || $operation->user->id == auth()->user()->id) {

            if ($operation != null) {
                $operation->end_at = Carbon::now('UTC');
                $operation->save();
                return redirect()->route('operation.index');
            }
        }

        return redirect()
            ->back()
            ->with('error', 'An error occurred while processing the request.');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Request $request)
    {
        $operation = Operation::find($request->operation_id);
        if (auth()->user()->has('calendar.closeAll') || $operation->user->id == auth()->user()->id) {
            if ($operation != null) {

                $operation->timestamps = false;
                $operation->is_cancelled = true;
                $operation->integration_id = ($request->get('integration_id') == "") ?
                    null : $request->get('integration_id');
                $operation->save();

                return redirect()->route('operation.index');
            }
        }

        return redirect()
            ->back()
            ->with('error', 'An error occurred while processing the request.');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activate(Request $request)
    {
        $operation = Operation::find($request->operation_id);
        if (auth()->user()->has('calendar.closeAll') || $operation->user->id == auth()->user()->id) {
            if ($operation != null) {
                $operation->timestamps = false;
                $operation->is_cancelled = false;
                $operation->integration_id = ($request->get('integration_id') == "") ?
                    null : $request->get('integration_id');
                $operation->save();

                return redirect()->route('operation.index');
            }
        }

        return redirect()
            ->back()
            ->with('error', 'An error occurred while processing the request.');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function subscribe(Request $request)
    {
        $operation = Operation::find($request->operation_id);

        if ($operation != null) {

            if (! $operation->isUserGranted(auth()->user()))
                return redirect()->back()->with('error', 'You are not granted to this operation !');

            if ($operation->status == "incoming") {
                Attendee::updateOrCreate(
                    [
                        'operation_id' => $request->operation_id,
                        'character_id' => $request->character_id
                    ],
                    [
                        'user_id' => auth()->user()->id,
                        'status' => $request->status,
                        'comment' => $request->comment
                    ]
                );
                return redirect()->route('operation.index');
            }
        }

        return redirect()
            ->back()
            ->with('error', 'An error occurred while processing the request.');
    }

    /**
     * @param $operation_id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function find($operation_id) {
        if (auth()->user()->has('calendar.view', false)) {
            $operation = Operation::find($operation_id)->load('tags');

            if (! $operation->isUserGranted(auth()->user()))
                return redirect()->back()->with('error', 'You are not granted to this operation !');

            return response()->json($operation);
        }

        return redirect()
            ->back()
            ->with('error', 'An error occurred while processing the request.');
    }

    /**
     * @param int $operation_id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Seat\Eseye\Exceptions\InvalidAuthencationException
     * @throws \Seat\Eseye\Exceptions\InvalidContainerDataException
     * @throws \Seat\Services\Exceptions\SettingException
     */
    public function paps(int $operation_id)
    {
        $operation = Operation::find($operation_id);
        if (is_null($operation))
            return redirect()
                ->back()
                ->with('error', 'Unable to retrieve the requested operation.');

        if (! $operation->isUserGranted(auth()->user()))
            return redirect()->back()->with('error', 'You are not granted to this operation !');

        if (is_null($operation->fc_character_id))
            return redirect()
                ->back()
                ->with('error', 'No fleet commander has been set for this operation.');

        if (! in_array($operation->fc_character_id, auth()->user()->associatedCharacterIds()->toArray()))
            return redirect()
                ->back()
                ->with('error', 'You are not the fleet commander or wrong character has been set.');

        try {
            $token = RefreshToken::findOrFail($operation->fc_character_id);
        } catch (ModelNotFoundException $e) {
            return redirect()
                ->back()
                ->with('error', 'Fleet commander is not already linked to SeAT. Unable to PAP the fleet.');
        }

        $client = $this->eseye($token);

        try {
            $fleet = $client->setVersion('v1')->invoke('get', '/characters/{character_id}/fleet/', [
                'character_id' => $token->character_id,
            ]);

            $members = $client->setVersion('v1')->invoke('get', '/fleets/{fleet_id}/members/', [
                'fleet_id' => $fleet->fleet_id,
            ]);

            foreach ($members as $member) {
                Pap::firstOrCreate([
                    'character_id' => $member->character_id,
                    'operation_id' => $operation_id,
                ],[
                    'ship_type_id' => $member->ship_type_id,
                    'join_time'    => carbon($member->join_time)->toDateTimeString(),
                ]);
            }
        } catch (RequestFailedException $e) {

            $this->updateToken($token, $client->getAuthentication());

            if ($e->getError() == 'Character is not in a fleet')
                return redirect()
                    ->back()
                    ->with('error', $e->getError());

            if ($e->getError() == 'The fleet does not exist or you don\'t have access to it!')
                return redirect()
                    ->back()
                    ->with('error', sprintf('%s Ensure %s have the fleet boss and try again.', $e->getError(), $operation->fc));

            return redirect()
                ->back()
                ->with('error', 'Esi respond with an unhandled error : (' . $e->getCode() . ') ' . $e->getError());
        } catch (EsiScopeAccessDeniedException $e) {

            $this->updateToken($token, $client->getAuthentication());

            return redirect()
                ->back()
                ->with('error', 'Registered tokens has not enough privileges. '.
                                'Please bind your character and pap again.');
        }

        $this->updateToken($token, $client->getAuthentication());

        return redirect()
            ->back()
            ->with('success', 'Fleet members has been successfully papped.');
    }

    /**
     * @param RefreshToken $token
     * @return mixed
     * @throws \Seat\Eseye\Exceptions\InvalidContainerDataException
     */
    private function eseye(RefreshToken $token)
    {
        $client = app('esi-client');

        return $client = $client->get(new EsiAuthentication([
            'refresh_token' => $token->refresh_token,
            'access_token'  => $token->token,
            'token_expires' => $token->expires_on,
            'scopes'        => $token->scopes,
        ]));
    }

    /**
     * @param RefreshToken $token
     * @param EsiAuthentication $last_auth
     */
    private function updateToken(RefreshToken $token, EsiAuthentication $last_auth)
    {
        $token->token = $last_auth->access_token ?? '-';
        $token->expires_on = $last_auth->token_expires;
        $token->save();
    }

}
