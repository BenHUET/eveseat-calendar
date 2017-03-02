<?php

namespace Seat\Kassie\Calendar\Helpers;

use Seat\Kassie\Calendar\Models\Setting;

class Settings
{
	private static $settings;

	private static function load() {
		self::$settings = Setting::all()->first();
	}

	public static function get($name) {
		if (!self::$settings)
			self::load();

		return self::$settings->{$name};
	}
}