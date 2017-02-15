<?php

namespace Kassie\Seat\Calendar\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Seat\Eveapi\Models\Eve\CharacterInfo;
use Seat\Web\Models\User;
use \DateTime;

class Attendee extends Model
{
	protected $table = 'calendar_attendees';

	protected $fillable = [
		'character_id',
		'operation_id',
		'user_id',
		'status',
		'comment'
	];

	public function character() {
		return $this->belongsTo(CharacterInfo::class);
	}

	public function user() {
		return $this->belongsTo(User::class);
	}

}
