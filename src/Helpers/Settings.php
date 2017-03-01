<?php

namespace Seat\Kassie\Calendar\Helpers;

use Seat\Kassie\Calendar\Models\Setting;

class Settings
{
	public static function get($name) {
		$settings = Setting::all()->first();
		return $settings->{$name};
	}
}