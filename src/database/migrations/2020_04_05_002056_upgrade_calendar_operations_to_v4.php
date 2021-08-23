<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Class UpgradeCalendarOperationsToV4.
 */
class UpgradeCalendarOperationsToV4 extends Migration {

    public function up()
    {
        DB::table('calendar_operations')
            ->join('mig_groups', 'user_id', '=', 'old_user_id')
            ->orderBy('user_id')
            ->each(function ($row) {
                DB::table('calendar_operations')
                    ->where('user_id', $row->old_user_id)
                    ->update([
                        'user_id' => $row->new_user_id,
                    ]);
            });

    }

    public function down()
    {
        DB::table('calendar_operations')
            ->join('mig_groups', 'user_id', '=', 'new_user_id')
            ->orderBy('user_id')
            ->each(function ($row) {
                DB::table('calendar_operations')
                    ->where('user_id', $row->new_user_id)
                    ->update([
                        'user_id' => $row->old_user_id,
                    ]);
            });
    }

}
