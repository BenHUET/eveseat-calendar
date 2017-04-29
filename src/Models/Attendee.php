<?php

namespace Seat\Kassie\Calendar\Models;

use Seat\Services\Repositories\Configuration\UserRespository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Seat\Eveapi\Models\Eve\CharacterInfo;
use Seat\Web\Models\User;


use Seat\Kassie\Calendar\Helpers\Helper;

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
	protected $appends = array('main_character', 'character_sheet_url', 'localized_status');

	public function character() {
		return $this->belongsTo(CharacterInfo::class);
	}

	public function user() {
		return $this->belongsTo(User::class);
	}

	public function getMainCharacterAttribute() {
		return Helper::GetUserMainCharacter($this->user_id);
	}

	public function getCharacterSheetUrlAttribute() {
		return route('character.view.sheet', ['character_id' => $this->character_id]);
	}

	public function getLocalizedStatusAttribute() {
		return Lang::get('calendar::seat.attending_' . $this->status);
	}

}
