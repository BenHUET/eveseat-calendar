<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Seat\Services\Models\GlobalSetting;

class DefaultNotificationSettings extends Migration
{
    const DEFAULT_SETTINGS = [
        'kassie.calendar.notify_create_operation' => true,
        'kassie.calendar.notify_update_operation' => true,
        'kassie.calendar.notify_cancel_operation' => true,
        'kassie.calendar.notify_activate_operation' => true,
        'kassie.calendar.notify_end_operation' => true,
        'kassie.calendar.notify_operation_interval' => '15,30,60',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (self::DEFAULT_SETTINGS as $name => $value) {
            setting([$name, $value], true);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        GlobalSetting::whereIn('name', array_keys(self::DEFAULT_SETTINGS))->delete();
    }
}