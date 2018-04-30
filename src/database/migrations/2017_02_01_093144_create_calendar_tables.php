<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalendarTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar_operations', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('user_id');

            $table->string('title');
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->enum('type', ['PvP', 'PvE', 'PvR', 'Other'])->nullable();
            $table->string('importance')->default('0');
            $table->string('description')->nullable();
            $table->string('staging')->nullable();
            $table->string('fc')->nullable();
            $table->bigInteger('fc_character_id')->nullable();
            $table->boolean('is_cancelled')->default(false);
            $table->nullableTimestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('fc_character_id')
                ->references('character_id')
                ->on('character_infos')
                ->onDelete('cascade');
        });

        Schema::create('calendar_attendees', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('operation_id')->unsigned();
            $table->bigInteger('user_id');
            $table->bigInteger('character_id');
            $table->enum('status', ['yes', 'no', 'maybe']);
            $table->string('comment')->nullable();
            $table->nullableTimestamps();

            $table->foreign('operation_id')
                ->references('id')
                ->on('calendar_operations')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('character_id')
                ->references('character_id')
                ->on('character_infos')
                ->onDelete('cascade');
        });

        Schema::create('calendar_settings', function (Blueprint $table) {
            $table->increments('id');

            $table->boolean('slack_integration');
            $table->string('slack_webhook');
            $table->string('slack_emoji_importance_full');
            $table->string('slack_emoji_importance_half');
            $table->string('slack_emoji_importance_empty');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('calendar_attendees');
        Schema::drop('calendar_operations');
        Schema::drop('calendar_settings');
    }
}
