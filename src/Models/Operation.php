<?php

namespace Seat\Kassie\Calendar\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use s9e\TextFormatter\Bundles\Forum as TextFormatter;
use Carbon\Carbon;
use \DateTime;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Notifications\Models\Integration;
use Seat\Web\Models\User;

/**
 * Class Operation.
 * @package Seat\Kassie\Calendar\Models
 */
class Operation extends Model
{
    use Notifiable;

    /**
     * @var string
     */
    protected $table = 'calendar_operations';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'start_at',
        'end_at',
        'importance',
        'integration_id',
        'description',
        'description_new',
        'staging_sys',
        'staging_sys_id',
        'staging_info',
        'is_cancelled',
        'fc',
        'fc_character_id',
        'role_name',
    ];

    /**
     * @var array
     */
    protected $dates = ['start_at', 'end_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function fleet_commander()
    {
        return $this->hasOne(CharacterInfo::class, 'character_id', 'fc_character_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attendees()
    {
        return $this->hasMany(Attendee::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'calendar_tag_operation');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function integration()
    {
        return $this->belongsTo(Integration::class, 'integration_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function staging()
    {
        return $this->hasOne(MapDenormalize::class, 'itemID', 'staging_sys_id')
            ->withDefault();
    }

    public function getIsFleetCommanderAttribute()
    {
        if ($this->fc_character_id == null)
            return false;

        return in_array($this->fc_character_id, auth()->user()->associatedCharacterIds());
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getDescriptionAttribute($value) {
        return $value ?: $this->description_new;
    }

    /**
     * @param $value
     */
    public function setDescriptionAttribute($value) {
        $this->attributes['description_new'] = $value;
    }

    /**
     * @return mixed
     */
    public function getParsedDescriptionAttribute() {
        $parser = TextFormatter::getParser();
        $parser->disablePlugin('Emoji');

        $xml = $parser->parse($this->description ?: $this->description_new);

        return TextFormatter::render($xml);
    }

    /**
     * @return string|null
     */
    public function getDurationAttribute() {
        if ($this->end_at)
            return $this->diffToHumanFormat($this->start_at, $this->end_at);

        return null;
    }

    /**
     * @return string
     */
    public function getStatusAttribute() {
        if ($this->is_cancelled)
            return "cancelled";

        if ($this->start_at > Carbon::now('UTC'))
            return "incoming";

        if ($this->end_at > Carbon::now('UTC'))
            return "ongoing";

        return "faded";
    }

    /**
     * @return string
     */
    public function getStartsInAttribute() {
        return $this->diffToHumanFormat(Carbon::now('UTC'), $this->start_at);
    }

    /**
     * @return string
     */
    public function getEndsInAttribute() {
        return $this->diffToHumanFormat(Carbon::now('UTC'), $this->end_at);
    }

    /**
     * @return string
     */
    public function getStartedAttribute() {
        return $this->diffToHumanFormat($this->start_at, Carbon::now('UTC'));
    }

    /**
     * @param $user_id
     * @return |null
     */
    public function getAttendeeStatus($user_id) {
        $entry = $this->attendees->where('user_id', $user_id)->first();

        if ($entry != null)
            return $entry->status;

        return null;
    }

    /**
     * @param $_date1
     * @param $_date2
     * @return string
     * @throws \Exception
     */
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

    /**
     * @return string
     */
    public function routeNotificationForSlack()
    {

        if (! is_null($this->integration()))
            return $this->integration->settings['url'];

        return '';
    }

    /**
     * Return true if the user can see the operation
     *
     * @param User $user
     * @return bool
     */
    public function isUserGranted(User $user) : bool
    {
        if (is_null($this->role_name))
            return true;

        return $user->hasRole($this->role_name);
    }
}
