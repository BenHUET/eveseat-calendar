<?php

namespace Seat\Kassie\Calendar\Helpers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Seat\Eveapi\Models\Account\ApiKeyInfoCharacters;

use Seat\Kassie\Calendar\Helpers\Settings;

class Helper
{

	public static function ImportanceAsEmoji($importance, $emoji_full, $emoji_half, $emoji_empty) {
		$output = "";

		$tmp = explode('.', $importance);
		$val = $tmp[0];
		$dec = 0;

		if (count($tmp) > 1)
			$dec = $tmp[1];

		for ($i = 0; $i < $val; $i++)
			$output .= $emoji_full;

		$left = 5;
		if ($dec != 0) {
			$output .= $emoji_half;
			$left--;
		}

		for ($i = $val; $i < $left; $i++)
			$output .= $emoji_empty;

		return $output;
	}

	public static function BuildSlackNotificationAttachment($op) {
		$url = url('/calendar/operation', [$op->id]);

		$fields = array();

		$fields[trans('calendar::seat.starts_at')] = $op->start_at->format('F j @ H:i EVE');
		$fields[trans('calendar::seat.duration')] = $op->getDurationAttribute() ? $op->getDurationAttribute() : trans('calendar::seat.unknown');

		$fields[trans('calendar::seat.importance')] = self::ImportanceAsEmoji($op->importance, Settings::get('slack_emoji_importance_full'), Settings::get('slack_emoji_importance_half'), Settings::get('slack_emoji_importance_empty'));

		$fields[trans('calendar::seat.fleet_commander')] = $op->fc ? $op->fc : trans('calendar::seat.unknown');

		return function ($attachment) use ($op, $url, $fields) {
			$attachment->title($op->title, $url)
			 	->fields($fields)
			 	->footer(trans('calendar::seat.created_by') . ' ' . $op->user->name)
			 	->markdown(['fields']);
		};
	}

	public static function GetUserMainCharacter($user_id) {
		$main = DB::table('user_settings')
			->where('user_id', $user_id)
			->where('name', 'main_character_id')
			->select('value')
			->get();

		if ($main->count() > 0 && $main->first()->value != '1')
			return ApiKeyInfoCharacters::where('characterID', $main->first()->value)->first();

		return null;
	}

}