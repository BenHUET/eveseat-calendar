<?php

namespace Seat\Kassie\Calendar\Models;

use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Services\Repositories\Configuration\UserRespository;
use Illuminate\Database\Eloquent\Model;
use Seat\Web\Models\User;


class Attendee extends Model
{
    use UserRespository;

    protected $table = 'calendar_attendees';

    protected $fillable = [
        'character_id',
        'operation_id',
        'user_id',
        'status',
        'comment'
    ];
    protected $dates = ['created_at', 'updated_at'];

    public function character() {
        return $this->belongsTo(CharacterInfo::class, 'character_id', 'character_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
