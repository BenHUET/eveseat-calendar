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
	public function up()
	{
		try {
			Schema::table('calendar_operations', function (Blueprint $table) {
				$table->dropForeign('calendar_operations_staging_sys_id_foreign');
			});
		}
		catch (QueryException $e) {

		}
		catch (PDOException $e) {

		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('calendar_operations', function (Blueprint $table) {
			$table->foreign('staging_sys_id')
				->references('itemID')
				->on('invUniqueNames')
				->onDelete('set null');
		});
	}
}
