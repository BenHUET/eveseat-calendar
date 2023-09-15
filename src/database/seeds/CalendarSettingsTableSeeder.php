<?php

namespace Seat\Kassie\Calendar\database\seeds;

use Illuminate\Database\Seeder;


class CalendarSettingsTableSeeder extends Seeder
{
    public function run(): void
    {
        setting([
            'kassie.calendar.slack_integration',
            false,
        ], true);

        setting([
            'kassie.calendar.slack_emoji_importance_full',
            ':full_moon_with_face:',
        ], true);

        setting([
            'kassie.calendar.slack_emoji_importance_half',
            ':last_quarter_moon:',
        ], true);

        setting([
            'kassie.calendar.slack_emoji_importance_empty',
            ':new_moon_with_face:',
        ], true);
    }
}
