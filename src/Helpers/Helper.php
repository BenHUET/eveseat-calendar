<?php

namespace Seat\Kassie\Calendar\Helpers;

use Carbon\Carbon;

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

}