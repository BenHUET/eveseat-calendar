<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Seat\Services\Models\GlobalSetting;

class DefaultNotificationSettings extends Migration
{
    const SETTINGS = [
        'kassie.calendar.notify_create_operation',
        'kassie.calendar.notify_update_operation',
        'kassie.calendar.notify_cancel_operation',
        'kassie.calendar.notify_activate_operation',
        'kassie.calendar.notify_end_operation',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (self::SETTINGS as $setting) {
            setting([$setting, true], true);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        GlobalSetting::whereIn('name', self::SETTINGS)->delete();
    }
}