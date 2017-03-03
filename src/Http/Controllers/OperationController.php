<?php

namespace Seat\Kassie\Calendar\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

use Seat\Services\Repositories\Configuration\UserRespository;
use Seat\Services\Repositories\Character\Character;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Models\People;

use Seat\Kassie\Calendar\Models\Operation;
use Seat\Kassie\Calendar\Models\Attendee;
use Seat\Kassie\Calendar\Helpers\Settings;

class OperationController extends Controller
{
	use UserRespository, Character;

	public function __construct() {
		$this->middleware('bouncer:calendar.view')->only('index');
		$this->middleware('bouncer:calendar.create')->only('store');
	}

	public function index()
	{
		$ops = Operation::all()->take(50);

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
		if(setting('main_character_id') != 1) {
			$mainCharacter = $userCharacters->where('characterID', '=', setting('main_character_id'))->first();
			$mainCharacter->main = true;
			$userCharacters = $userCharacters->reject(function ($character) {
				return $character->characterID == setting('main_character_id');
			});
			$userCharacters->prepend($mainCharacter);
		}
		
		return view('calendar::operation.index', [
			'slack_integration' => Settings::get('slack_integration'),
			'userCharacters' => $userCharacters,
			'ops_all' => $ops,
			'ops_incoming' => $ops_incoming,
			'ops_ongoing' => $ops_ongoing,
			'ops_faded' => $ops_faded
		]);
	}

	public function store(Request $request)
	{
		$this->validate($request, [
			'title' => 'required',
			'importance' => 'required|between:0,5',
			'type' => 'required',
			'known_duration' => 'required',
			'time_start' => 'required_without_all:time_start_end|date|after_or_equal:today',
			'time_start_end' => 'required_without_all:time_start'
		]);

		$operation = new Operation($request->all());

		foreach ($request->toArray() as $name => $value)
			if (empty($value))
				$operation->{$name} = null;

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
	}

	public function update(Request $request) 
	{
		$this->validate($request, [
			'title' => 'required',
			'importance' => 'required|between:0,5',
			'type' => 'required',
			'known_duration' => 'required',
			'time_start' => 'required_without_all:time_start_end|date|after_or_equal:today',
			'time_start_end' => 'required_without_all:time_start'
		]);
		
		$operation = Operation::find($request->operation_id);
		if (auth()->user()->has('calendar.updateAll') || $operation->user->id == auth()->user()->id) {

			foreach ($request->toArray() as $name => $value)
				if (empty($value))
					$operation->{$name} = null;

			$operation->title = $request->title;
			$operation->type = $request->type;
			$operation->importance = $request->importance;
			$operation->description = $request->description;
			$operation->staging = $request->staging;
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

			return $operation;
		}

		return redirect()->route('auth.unauthorized');
	}

	public function delete(Request $request)
	{
		$operation = Operation::find($request->operation_id);
		if ($operation != null) {
			if (auth()->user()->has('calendar.deleteAll') || $operation->user->id == auth()->user()->id) {
				Operation::destroy($operation->id);
				return redirect()->back();
			}
		}

		return redirect()->route('auth.unauthorized');
	}

	public function close(Request $request)
	{
		$operation = Operation::find($request->operation_id);

		if ($operation != null) {
			if (auth()->user()->has('calendar.closeAll') || $operation->user->id == auth()->user()->id) {
				$operation->end_at = Carbon::now('UTC');
				$operation->save();

				return redirect()->back();
			}
		}

		return redirect()->route('auth.unauthorized');
	}

	public function cancel(Request $request)
	{
		$operation = Operation::find($request->operation_id);

		if ($operation != null) {
			if (auth()->user()->has('calendar.closeAll') || $operation->user->id == auth()->user()->id) {
				$operation->timestamps = false;
				$operation->is_cancelled = true;
				$operation->notify = $request->get('notify');
				$operation->save();

				return redirect()->back();
			}
		}

		return redirect()->route('auth.unauthorized');
	}

	public function activate(Request $request) 
	{
		$operation = Operation::find($request->operation_id);

		if ($operation != null) {
			if (auth()->user()->has('calendar.closeAll') || $operation->user->id == auth()->user()->id) {
				$operation->timestamps = false;
				$operation->is_cancelled = false;
				$operation->notify = $request->get('notify');
				$operation->save();

				return redirect()->back();
			}
		}
		
		return redirect()->route('auth.unauthorized');
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
				return redirect()->back();
			}
		}

		return redirect()->route('auth.unauthorized');
	}

	public function find($operation_id) {
		if (auth()->user()->has('calendar.view', false)) {
			$operation = Operation::find($operation_id);
			return response()->json($operation);
		}

		return redirect()->route('auth.unauthorized');
	}

}