<?php

namespace Seat\Kassie\Calendar\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

use Carbon\Carbon;
use \DateTime;

use Seat\Web\Models\User;
use Seat\Kassie\Calendar\Helpers\Settings;
use Seat\Kassie\Calendar\Models\Tag;

class Operation extends Model
{
	use Notifiable;

	protected $table = 'calendar_operations';
	protected $fillable = [
		'title',
		'start_at',
		'end_at',
		'importance',
		'description',
		'description_new',
		'staging_sys',
		'staging_sys_id',
		'staging_info',
		'is_cancelled',
		'fc',
		'fc_character_id'
	];
	protected $dates = ['start_at', 'end_at', 'created_at', 'updated_at'];

	private $notify;

	public function user() {
		return $this->belongsTo(User::class);
	}

	public function attendees()
    {
        return $this->hasMany(Attendee::class);
    }

    public function tags()
	{
		return $this->belongsToMany(Tag::class, 'calendar_tag_operation');
	}

	public function getDescriptionAttribute($value) {
		return preg_replace('/( (http|ftp|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])? )/', '<a href="\1">\1</a>', $value ?: $this->description_new);
	}
	public function setDescriptionAttribute($value) {
		$this->attributes['description_new'] = $value;
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

	public function setNotifyAttribute($value)
	{
		$this->notify = $value;
	}
	public function getNotifyAttribute($value)
	{
		return $this->notify;
	}

	public function routeNotificationForSlack()
    {
		return Settings::get('slack_webhook');
    }
}
