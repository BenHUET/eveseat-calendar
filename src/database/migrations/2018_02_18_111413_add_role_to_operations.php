<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleToOperations extends Migration {

    public function up(): void
    {
        Schema::table('calendar_operations', function(Blueprint $table): void{

            $table->string('role_name')->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('calendar_operations', function(Blueprint $table): void{

            $table->dropColumn(['role_name']);

        });
    }

}
