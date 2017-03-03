<?php

namespace Seat\Kassie\Calendar\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Seat\Eveapi\Models\Eve\CharacterInfo;
use Seat\Web\Models\User;

class Setting extends Model
{
	public $timestamps = false;
	protected $table = 'calendar_settings';
	protected $fillable = [
		'slack_integration',
		'slack_webhook', 
		'slack_emoji_importance_full',
		'slack_emoji_importance_half',
		'slack_emoji_importance_empty'
	];
}
