<?php

namespace Kassie\Seat\Calendar\Models;

use Illuminate\Database\Eloquent\Model;
use Seat\Web\Models\User;
use \DateTime;

class Operation extends Model
{
	protected $table = 'calendar_operations';

	protected $fillable = [
		'title',
		'start_at',
		'end_at',
		'type',
		'importance',
		'description',
		'staging',
		'is_cancelled',
		'fc'
	];

	public function user() {
		return $this->belongsTo(User::class);
	}

	public function attendees()
    {
        return $this->hasMany(Attendee::class);
    }

	public function getDurationAttribute() {
		if ($this->end_at) {
			$start = new DateTime($this->start_at);
			$end = new DateTime($this->end_at);
			$diff = $start->diff($end);

			return $this->diffToHumanFormat($diff);
		}

		return "-";
	}

	public function getStatusAttribute() {
		// $ops_incoming = $ops->where('is_cancelled', '=', false)
		// 				->where('start_at', '>', $this->now);

		// $ops_ongoing = $ops->where('is_cancelled', '=', false)
		// 				->where('end_at', '>', $this->now)
		// 				->where('start_at', '<=', $this->now);

		// $ops_faded = Operation::where('is_cancelled', '=', true)
		// ->orWhere(function ($query) {
		// 	$query->whereNull('end_at')
		// 		->where('start_at', '<=', $this->now);
		// })
		// ->orWhere('end_at', '<=', $this->now)
		// ->take(50)
		// ->get();
		$dt = new \DateTime('now', new \DateTimeZone('UTC'));
		$dt->setTimestamp(time());
		$now = $dt->format('Y-m-d H:i:s');

		if ($this->is_cancelled == false) 
		{
			if ($this->start_at > $now)
			{
				return "incoming";
			}
			else if ($this->end_at > $now) 
			{
				return "ongoing";
			}
			else {
				return "faded";
			}
		}
		else 
		{
			return "faded";
		}
	}

	public function getStartsInAttribute() {
		$now = new DateTime('now', new \DateTimeZone('UTC'));
		$start_at = new DateTime($this->start_at);
		$diff = $now->diff($start_at);

		return $this->diffToHumanFormat($diff);
	}

	public function getEndsInAttribute() {
		$now = new DateTime('now', new \DateTimeZone('UTC'));
		$end_at = new DateTime($this->end_at);
		$diff = $now->diff($end_at);

		return $this->diffToHumanFormat($diff);
	}

	public function getStartedAttribute() {
		$now = new DateTime('now', new \DateTimeZone('UTC'));
		$start_at = new DateTime($this->start_at);
		$diff = $start_at->diff($now);

		return $this->diffToHumanFormat($diff);
	}

	public function getAttendeeStatus($user_id) {
		$entry = $this->attendees->where('user_id', $user_id)->first();
		if ($entry != null)
			return $entry->status;
		return null;
	}

	private function diffToHumanFormat($diff) {
		$duration = '';

		if ($diff->m > 0)
			$duration = $duration . $diff->m . ' ' . trans_choice('calendar::seat.month', $diff->m) . ' ';

		if ($diff->d > 0)
			$duration = $duration . $diff->d . ' ' . trans_choice('calendar::seat.day', $diff->d) . ' ';

		if ($diff->h > 0)
			$duration = $duration . $diff->h . ' ' . trans_choice('calendar::seat.hour', $diff->h) . ' ';

		if ($diff->i > 0)
			$duration = $duration . $diff->i . ' ' . trans_choice('calendar::seat.minute', $diff->i) . ' ';

		return $duration;
	}
}
