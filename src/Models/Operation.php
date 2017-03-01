<?php

namespace Seat\Kassie\Calendar\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

use Carbon\Carbon;
use \DateTime;

use Seat\Web\Models\User;

use Seat\Kassie\Calendar\Helpers\Settings;

class Operation extends Model
{
	use Notifiable;

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
		'fc',
		'fc_character_id'
	];
	protected $dates = ['start_at', 'end_at', 'created_at', 'updated_at'];

	public function user() {
		return $this->belongsTo(User::class);
	}

	public function attendees()
    {
        return $this->hasMany(Attendee::class);
    }

	public function getDurationAttribute() {
		if ($this->end_at)
			return $this->diffToHumanFormat($this->start_at, $this->end_at);

		return null;
	}

	public function getStatusAttribute() {
		if ($this->is_cancelled == false)
		{
			if ($this->start_at > Carbon::now('UTC'))
			{
				return "incoming";
			}
			else if ($this->end_at > Carbon::now('UTC'))
			{
				return "ongoing";
			}
			else {
				return "faded";
			}
		}
		else
		{
			return "cancelled";
		}
	}

	public function getStartsInAttribute() {
		return $this->diffToHumanFormat(Carbon::now('UTC'), $this->start_at);
	}

	public function getEndsInAttribute() {
		return $this->diffToHumanFormat(Carbon::now('UTC'), $this->end_at);
	}

	public function getStartedAttribute() {
		return $this->diffToHumanFormat($this->start_at, Carbon::now('UTC'));
	}

	public function getAttendeeStatus($user_id) {
		$entry = $this->attendees->where('user_id', $user_id)->first();
		if ($entry != null)
			return $entry->status;
		return null;
	}

	private function diffToHumanFormat($_date1, $_date2) {
		$diff = (new DateTime($_date1))->diff(new DateTime($_date2));

		$duration = '';

		if ($diff->m > 0)
			$duration .= $diff->m . ' ' . trans_choice('calendar::seat.month', $diff->m) . ' ';

		if ($diff->d > 0)
			$duration .= $diff->d . ' ' . trans_choice('calendar::seat.day', $diff->d) . ' ';

		if ($diff->h > 0)
			$duration .= $diff->h . ' ' . trans_choice('calendar::seat.hour', $diff->h) . ' ';

		if ($diff->i > 0)
			$duration .= $diff->i . ' ' . trans_choice('calendar::seat.minute', $diff->i) . ' ';

		if ($duration == '')
			if ($diff->s > 0)
				$duration .= $diff->s . ' ' . trans_choice('calendar::seat.second', $diff->s) . ' ';

		return $duration;
	}

	public function routeNotificationForSlack()
    {
		return Settings::get('slack_webhook');;
    }
}
