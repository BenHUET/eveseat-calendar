<?php

namespace Kassie\Seat\Calendar\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Seat\Web\Http\Controllers\Controller;
use Kassie\Seat\Calendar\Models\Operation;
use Kassie\Seat\Calendar\Models\Attendee;

class OperationController extends Controller
{
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
		$ops = Operation::all();

		$ops_incoming = $ops->filter(function($op) {
			return $op->status == "incoming";
		});

		$ops_ongoing = $ops->filter(function($op) {
			return $op->status == "ongoing";
		});

		$ops_faded = $ops->filter(function($op) {
			return $op->status == "faded";
		});
		
		return view('calendar::index', [
			'ops_incoming' => $ops_incoming,
			'ops_ongoing' => $ops_ongoing,
			'ops_faded' => $ops_faded
		]);
	}

	public function store(Request $request)
	{
		$this->validate($request, [
			'title' => 'required',
			'importance' => 'required|between:1,10',
			'type' => 'required',
			'known_duration' => 'required',
			'time_start' => 'required_without_all:time_start_end|date|after_or_equal:today',
			'time_start_end' => 'required_without_all:time_start'
		]);
		
		$operation = new Operation($request->all());

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
		else {
			return redirect()->route('auth.unauthorized');
		}
	}

	public function close($id) 
	{
		$operation = Operation::find($id);
		$dt = new \DateTime('now', new \DateTimeZone('UTC'));
		$dt->setTimestamp(time());
		$this->now = $dt->format('Y-m-d H:i:s');
		$operation->end_at = $this->now;
		$operation->save();

		return redirect()->back();
	}

	public function cancel($id) 
	{
		$operation = Operation::find($id);

		if (auth()->user()->has('calendar.closeAll') || $operation->user->id == auth()->user()->id) {
			$operation->timestamps = false;
			$operation->is_cancelled = true;
			$operation->save();

			return redirect()->back();
		}
		else {
			return redirect()->route('auth.unauthorized');
		}
	}

	public function activate($id) 
	{
		$operation = Operation::find($id);
		if (auth()->user()->has('calendar.closeAll') || $operation->user->id == auth()->user()->id) {
			$operation->timestamps = false;
			$operation->is_cancelled = false;
			$operation->save();

			return redirect()->back();
		}
		else {
			return redirect()->route('auth.unauthorized');
		}
	}

	public function subscribe(Request $request)
	{
		$operation = Operation::find($request->operation_id);

		if ($operation != null && $operation->status == "ongoing") {
			Attendee::updateOrCreate(
				[ 
					'operation_id' => $request->operation_id, 
					'user_id' => auth()->user()->id
				],
				[
					'status' => $request->status,
					'comment' => $request->comment
				]
			);
			return redirect()->back();
		}
		else {
			return redirect()->route('auth.unauthorized');
		}
	}

}