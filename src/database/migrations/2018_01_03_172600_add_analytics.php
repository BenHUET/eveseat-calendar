<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAnalytics extends Migration {

    public function up()
    {
        Schema::table('calendar_tags', function(Blueprint $table){

            $table->decimal('quantifier', 5, 2)->default(1.0);
            $table->enum('analytics', ['strategic', 'pvp', 'mining', 'other', 'untracked'])->default('untracked');

        });

        Schema::table('kassie_calendar_paps', function(Blueprint $table){

            $table->decimal('value', 5, 2)
                  ->after('ship_type_id')
                  ->default(1.0);

        });
    }

    public function down()
    {
        Schema::table('calendar_tags', function(Blueprint $table){

            $table->dropColumn(['quantifier', 'analytics']);

        });

        Schema::table('kassie_calendar_paps', function(Blueprint $table){

            $table->dropColumn('value');

        });
    }

}
