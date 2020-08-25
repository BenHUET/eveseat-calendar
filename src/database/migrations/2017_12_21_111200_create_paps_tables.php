<?php
/**
 * User: Warlof Tutsimo <loic.leuilliot@gmail.com>
 * Date: 21/12/2017
 * Time: 11:13
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreatePapsTables extends Migration {

    public function up()
    {
        Schema::create('kassie_calendar_esi_tokens', function(Blueprint $table){

            $table->bigInteger('character_id');
            $table->string('scopes');
            $table->string('access_token');
            $table->string('refresh_token');
            $table->boolean('active')->default(true);
            $table->dateTime('expires_at');

            $table->primary('character_id');

        });

        Schema::create('kassie_calendar_paps', function(Blueprint $table){

            $table->integer('operation_id');
            $table->bigInteger('character_id');
            $table->bigInteger('ship_type_id');
            $table->dateTime('join_time');
            $table->integer('week');
            $table->integer('month');
            $table->integer('year');

            $table->primary(['operation_id', 'character_id']);
            $table->index('week');
            $table->index('month');
            $table->index('year');
            $table->index(['week', 'month']);
            $table->index(['month', 'year']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('kassie_calendar_paps');
        Schema::dropIfExists('kassie_calendar_esi_tokens');
    }

}
