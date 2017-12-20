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
use Seat\Kassie\Calendar\Models\Tag;
use Seat\Kassie\Calendar\Helpers\Settings;
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
			'tags' => $tags
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

}
