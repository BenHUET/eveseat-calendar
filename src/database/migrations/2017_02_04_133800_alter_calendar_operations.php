<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;

class AlterCalendarOperations extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('calendar_operations', function (Blueprint $table) {
			$table->dropColumn('staging');

			$table->string('staging_sys')->nullable();
			$table->integer('staging_sys_id')->nullable();
			$table->string('staging_info')->nullable();

			$table->foreign('staging_sys_id')
				->references('itemID')
				->on('invUniqueNames')
				->onDelete('set null');
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('calendar_operations', function (Blueprint $table) {
			$table->dropForeign('calendar_operations_staging_sys_id_foreign');
			$table->dropColumn('staging_sys');
			$table->dropColumn('staging_sys_id');
			$table->dropColumn('staging_info');

			$table->string('staging')->nullable();
		});
	}
}
