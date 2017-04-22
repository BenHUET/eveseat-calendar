<?php

namespace Seat\Kassie\Calendar\Models;

use Seat\Services\Repositories\Configuration\UserRespository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Seat\Eveapi\Models\Eve\CharacterInfo;
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
		return $this->belongsTo(CharacterInfo::class);
	}

	public function user() {
		return $this->belongsTo(User::class);
	}

	public function getMainCharacterAttribute() {
		$main = null;
		$userCharacters = $this->getUserCharacters(auth()->user()->id)->unique('characterID')->sortBy('characterName');
		if(setting('main_character_id') != 1 && $userCharacters->count() > 0) {
			$main = $userCharacters->where('characterID', '=', setting('main_character_id'))->first();
		}

		return $main;
	}

}
