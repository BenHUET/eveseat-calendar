<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleToOperations extends Migration {

    public function up()
    {
        Schema::table('calendar_operations', function(Blueprint $table){

            $table->string('role_name')->nullable();

        });
    }

    public function down()
    {
        Schema::table('calendar_operations', function(Blueprint $table){

            $table->dropColumn(['role_name']);

        });
    }

}
