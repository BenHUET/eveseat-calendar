<?php

namespace Seat\Kassie\Calendar\database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ScheduleSeeder extends Seeder
{
    public function run()
    {
        $job = [
            'command'           => 'calendar:remind',
            'expression'        => '1 * * * *',
            'allow_overlap'     => false,
            'allow_maintenance' => false,
            'ping_before'       => null,
            'ping_after'        => null
        ];

        $existing = DB::table('schedules')
            ->where('command', $job['command'])
            ->where('expression', $job['expression'])
            ->first();

        if (!$existing) {
            DB::table('schedules')->insert($job);
        }
    }
}
