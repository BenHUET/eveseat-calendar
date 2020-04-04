<?php

namespace Seat\Kassie\Calendar\Models;

use Seat\Eveapi\Models\Character\CharacterInfo;
use Illuminate\Database\Eloquent\Model;
use Seat\Web\Models\User;

/**
 * Class Attendee.
 *
 * @package Seat\Kassie\Calendar\Models
 */
class Attendee extends Model
{
    /**
     * @var string
     */
    protected $table = 'calendar_attendees';

    /**
     * @var array
     */
    protected $fillable = [
        'character_id',
        'operation_id',
        'user_id',
        'status',
        'comment'
    ];

    /**
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function character() {
        return $this->belongsTo(CharacterInfo::class, 'character_id', 'character_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }
}
