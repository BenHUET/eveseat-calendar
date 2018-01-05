<?php

namespace Seat\Kassie\Calendar\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;
use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\RequestFailedException;
use Seat\Kassie\Calendar\Helpers\EsiGuzzleFetcher;
use Seat\Kassie\Calendar\Models\Api\EsiToken;
use Seat\Kassie\Calendar\Models\Pap;
use Seat\Services\Repositories\Configuration\UserRespository;
use Seat\Services\Repositories\Character\Character;
use Seat\Web\Http\Controllers\Controller;
use Seat\Kassie\Calendar\Models\Operation;
use Seat\Kassie\Calendar\Models\Attendee;
use Seat\Kassie\Calendar\Models\Tag;
use Seat\Kassie\Calendar\Helpers\Helper;


class OperationController extends Controller
{
    use UserRespository, Character;

    public function __construct() {
        $this->middleware('bouncer:calendar.view')->only('index');
        $this->middleware('bouncer:calendar.create')->only('store');
    }

    public function index(Request $request)
    {
        $isKnownCharacter = !is_null(EsiToken::find(setting('main_character_id')));

        $ops = Operation::all()->take(-50);
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

        $userCharacters = $this->getUserCharacters(auth()->user()->id)->unique('characterID')->sortBy('characterName');
        $mainCharacter = Helper::GetUserMainCharacter(auth()->user()->id);

        if($mainCharacter != null) {
            $mainCharacter['main'] = true;
            $userCharacters = $userCharacters->reject(function ($character) use ($mainCharacter) {
                return $character->characterID == $mainCharacter['characterID'];
            });
            $userCharacters->prepend($mainCharacter);
        }

        return view('calendar::operation.index', [
            'userCharacters' => $userCharacters,
            'ops_all' => $ops,
            'ops_incoming' => $ops_incoming,
            'ops_ongoing' => $ops_ongoing,
            'ops_faded' => $ops_faded,
            'default_op' => $request->id ? $request->id : 0,
            'tags' => $tags,
            'isKnownCharacter' => $isKnownCharacter,
        ]);
    }

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

        $operation->notify = $request->get('notify');

        $operation->user()->associate(auth()->user());

        $operation->save();

        $operation->tags()->attach($tags);
    }

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

            $operation->title = $request->title;
            $operation->importance = $request->importance;
            $operation->description = $request->description;
            $operation->staging_sys = $request->staging_sys;
            $operation->staging_info = $request->staging_info;
            $operation->staging_sys_id = $request->staging_sys_id == null ? null : $request->staging_sys_id;
            $operation->fc = $request->fc;
            $operation->fc_character_id = $request->fc_character_id == null ? null : $request->fc_character_id;

            if ($request->known_duration == "no") {
                $operation->start_at = Carbon::parse($request->time_start);
                $operation->end_at = null;
            }
            else {
                $dates = explode(" - ", $request->time_start_end);
                $operation->start_at = Carbon::parse($dates[0]);
                $operation->end_at = Carbon::parse($dates[1]);
            }
            $operation->start_at = Carbon::parse($operation->start_at);

            if ($request->importance == 0)
                $operation->importance = 0;

            $operation->notify = $request->get('notify');

            $operation->save();

            $operation->tags()->sync($tags);

            return $operation;
        }

        return redirect()
            ->back()
            ->with('error', 'An error occurred while processing the request.');
    }

    public function delete(Request $request)
    {
        $operation = Operation::find($request->operation_id);
        if (auth()->user()->has('calendar.deleteAll') || $operation->user->id == auth()->user()->id) {
            if ($operation != null) {
                Operation::destroy($operation->id);
                return redirect()->route('operation.index');
            }
        }

        return redirect()
            ->back()
            ->with('error', 'An error occurred while processing the request.');
    }

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

    public function cancel(Request $request)
    {
        $operation = Operation::find($request->operation_id);
        if (auth()->user()->has('calendar.closeAll') || $operation->user->id == auth()->user()->id) {
            if ($operation != null) {

                $operation->timestamps = false;
                $operation->is_cancelled = true;
                $operation->notify = $request->get('notify');
                $operation->save();

                return redirect()->route('operation.index');
            }
        }

        return redirect()
            ->back()
            ->with('error', 'An error occurred while processing the request.');
    }

    public function activate(Request $request)
    {
        $operation = Operation::find($request->operation_id);
        if (auth()->user()->has('calendar.closeAll') || $operation->user->id == auth()->user()->id) {
            if ($operation != null) {
                $operation->timestamps = false;
                $operation->is_cancelled = false;
                $operation->notify = $request->get('notify');
                $operation->save();

                return redirect()->route('operation.index');
            }
        }

        return redirect()
            ->back()
            ->with('error', 'An error occurred while processing the request.');
    }

    public function subscribe(Request $request)
    {
        $operation = Operation::find($request->operation_id);

        if ($operation != null) {
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

    public function find($operation_id) {
        if (auth()->user()->has('calendar.view', false)) {
            $operation = Operation::find($operation_id)->load('tags');
            return response()->json($operation);
        }

        return redirect()
            ->back()
            ->with('error', 'An error occurred while processing the request.');
    }

    public function paps(int $operation_id)
    {
        $operation = Operation::find($operation_id);
        if (is_null($operation))
            return redirect()
                ->back()
                ->with('error', 'Unable to retrieve the requested operation.');

        if (is_null($operation->fc_character_id))
            return redirect()
                ->back()
                ->with('error', 'No fleet commander has been set for this operation.');

        if ($operation->fc_character_id != setting('main_character_id'))
            return redirect()
                ->back()
                ->with('error', 'You are not the fleet commander or wrong character has been set.');

        $token = EsiToken::find(setting('main_character_id'));
        if (is_null($token))
            return redirect()
                ->back()
                ->with('error', 'Fleet commander is not already linked to SeAT.');

        $configuration = Configuration::getInstance();
        $configuration->http_user_agent = sprintf('eveseat-calendar/%s (Kassie Yvo;Daerie Inc.;Get Off My Lawn)',
            config('calendar.config.version'));
        $configuration->logfile_location = storage_path('logs/eseye.log');
        $configuration->file_cache_location = storage_path('app/eseye/');
        $configuration->datasource = env('CALENDAR_ESI_SERVER');
        $configuration->fetcher = EsiGuzzleFetcher::class;

        $authentication = new EsiAuthentication([
            'client_id'     => env('CALENDAR_EVE_CLIENT_ID'),
            'secret'        => env('CALENDAR_EVE_CLIENT_SECRET'),
            'access_token'  => $token->access_token,
            'refresh_token' => $token->refresh_token,
            'scopes'        => [
                'esi-fleets.read_fleet.v1',
            ],
            'token_expires' => $token->expires_at,
        ]);

        $esi = new Eseye($authentication);

        try {
            $fleet = $esi->setVersion('v1')->invoke('get', '/characters/{character_id}/fleet/', [
                'character_id' => $token->character_id,
            ]);

            $members = $esi->setVersion('v1')->invoke('get', '/fleets/{fleet_id}/members/', [
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
            if ($e->getError() == 'Character is not in a fleet')
                return redirect()
                    ->back()
                    ->with('error', $e->getError());

            return redirect()
                ->back()
                ->with('error', 'Esi respond with an unhandled error : (' . $e->getCode() . ') ' . $e->getError());
        } catch (EsiScopeAccessDeniedException $e) {
            $token->delete();
            return redirect()
                ->back()
                ->with('error', 'Registered tokens has been dropped due to missing privileges. '.
                                'Please bind your character and pap again.');
        }

        if ($token->access_token  != $esi->getAuthentication()->access_token ||
            $token->refresh_token != $esi->getAuthentication()->refresh_token) {
            $token->access_token  = $esi->getAuthentication()->access_token;
            $token->refresh_token = $esi->getAuthentication()->refresh_token;
            $token->expires_at    = $esi->getAuthentication()->token_expires;
            $token->save();
        }

        return redirect()
            ->back()
            ->with('success', 'Fleet members has been successfully papped.');
    }

}
