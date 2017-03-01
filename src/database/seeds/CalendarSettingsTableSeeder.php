<?php

namespace Seat\Kassie\Calendar\Seeders;

use Illuminate\Database\Seeder;

class CalendarSettingsTableSeeder extends Seeder
{
	public function run()
	{
		\DB::table('calendar_settings')->insert([
			'slack_integration' => false,
			'slack_webhook' => ''
		]);
	}
}