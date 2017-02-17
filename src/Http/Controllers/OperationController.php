<?php

namespace Kassie\Seat\Calendar\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Seat\Services\Repositories\Configuration\UserRespository;
use Seat\Services\Repositories\Character\Character;
use Seat\Web\Http\Controllers\Controller;
use Kassie\Seat\Calendar\Models\Operation;
use Kassie\Seat\Calendar\Models\Attendee;

class OperationController extends Controller
{
	use UserRespository, Character;

	private $now;

	public function __construct() {
		$this->middleware('bouncer:calendar.view')->only('index');
		$this->middleware('bouncer:calendar.create')->only('store');

		$dt = new \DateTime('now', new \DateTimeZone('UTC'));
		$dt->setTimestamp(time());

		$this->now = $dt->format('Y-m-d H:i:s');
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

		$userCharacters = $this->getUserCharacters(auth()->user()->id);
		
		return view('calendar::index', [
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
			$operation->start_at = $request->time_start;
		else {
			$dates = explode(" - ", $request->time_start_end);
			$operation->start_at = $dates[0];
			$operation->end_at = (new \DateTime($dates[1]))->format('Y-m-d H:i:s');
		}

		$operation->start_at = (new \DateTime($operation->start_at))->format('Y-m-d H:i:s');

		$operation->user()->associate(auth()->user());


		$operation->save();
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
				$dt = new \DateTime('now', new \DateTimeZone('UTC'));
				$dt->setTimestamp(time());
				$this->now = $dt->format('Y-m-d H:i:s');
				$operation->end_at = $this->now;
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

}