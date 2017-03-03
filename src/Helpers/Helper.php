<?php

namespace Seat\Kassie\Calendar\Helpers;

use Carbon\Carbon;

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
		$url = url('/calendar/operation');

		$fields = array();

		$fields[trans('calendar::seat.starts_at')] = $op->start_at->format('F j @ H:i EVE');
		$fields[trans('calendar::seat.duration')] = $op->getDurationAttribute() ? $op->getDurationAttribute() : trans('calendar::seat.unknown');

		$fields[trans('calendar::seat.importance')] = self::ImportanceAsEmoji($op->importance, Settings::get('slack_emoji_importance_full'), Settings::get('slack_emoji_importance_half'), Settings::get('slack_emoji_importance_empty'));

		$fields[trans('calendar::seat.fleet_commander')] = $op->fc ? $op->fc : trans('calendar::seat.unknown');

		return function ($attachment) use ($op, $url, $fields) {
			$attachment->title('(' . $op->type . ') ' . $op->title, $url)
			 	->fields($fields)
			 	->footer(trans('calendar::seat.created_by') . ' ' . $op->user->name)
			 	->markdown(['fields']);
		};
	}

}