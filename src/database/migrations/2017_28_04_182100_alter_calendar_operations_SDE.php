<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\QueryException;

class AlterCalendarOperationsSDE extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        try {
            Schema::table('calendar_operations', function (Blueprint $table): void {
                $table->dropForeign('calendar_operations_staging_sys_id_foreign');
            });
        }
        catch (QueryException|PDOException) {

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('calendar_operations', function (Blueprint $table): void {
            $table->foreign('staging_sys_id')
                ->references('itemID')
                ->on('invUniqueNames')
                ->onDelete('set null');
        });
    }
}
