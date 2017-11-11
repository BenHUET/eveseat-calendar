<?php

use Illuminate\Database\Seeder;

class CalendarSettingsTableSeeder extends Seeder
{
	public function run()
	{
		\DB::table('calendar_settings')->insert([
			'slack_integration' => false,
			'slack_webhook' => '',
			'slack_emoji_importance_full' => ':full_moon_with_face:',
			'slack_emoji_importance_half' => ':last_quarter_moon:',
			'slack_emoji_importance_empty' => ':new_moon_with_face:'
		]);
	}
}