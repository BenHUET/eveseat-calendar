<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Seat\Services\Models\GlobalSetting;

class DefaultPingOperationSettings extends Migration
{
    const SETTING = 'kassie.calendar.notify_operation_interval';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        setting([self::SETTING, '15,30,60'], true);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        GlobalSetting::where('name', self::SETTING)->delete();
    }
}