<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCalendarOperationsDescriptionField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('calendar_operations', function (Blueprint $table): void {
            $table->text('description_new')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('calendar_operations', function (Blueprint $table): void {
            $table->dropColumn('description_new');
        });
    }
}
