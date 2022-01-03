<?php

namespace Seat\Kassie\Calendar\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use s9e\TextFormatter\Bundles\Forum as TextFormatter;
use Carbon\Carbon;
use Carbon\CarbonInterface;
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
            return $this->start_at->diffForHumans($this->end_at,
                [
                    'syntax' => CarbonInterface::DIFF_RELATIVE_TO_NOW,
                    'options' => Carbon::ROUND,
                ]
            );

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
        return $this->start_at->diffForHumans(Carbon::now('UTC'),
            [
                'syntax' => CarbonInterface::DIFF_RELATIVE_TO_NOW,
                'options' => Carbon::ROUND,
            ]
        );
    }

    /**
     * @return string
     */
    public function getEndsInAttribute() {
        return $this->end_at->longRelativeToNowDiffForHumans(Carbon::now('UTC'),
            [
                'syntax' => CarbonInterface::DIFF_RELATIVE_TO_NOW,
                'options' => Carbon::ROUND,
            ]
        );
    }

    /**
     * @return string
     */
    public function getStartedAttribute() {
        return $this->start_at->longRelativeToNowDiffForHumans(Carbon::now('UTC'),
            [
                'syntax' => CarbonInterface::DIFF_RELATIVE_TO_NOW,
                'options' => Carbon::ROUND,
            ]
        );
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

        return $user->roles->where('title', $this->role_name)->isNotEmpty() || auth()->user()->isAdmin();
    }
}
