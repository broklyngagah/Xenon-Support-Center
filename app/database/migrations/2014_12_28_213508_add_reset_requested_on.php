<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddResetRequestedOn extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table("users", function(Blueprint $table)
		{
			$table->dateTime("reset_requested_on");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table("users", function(Blueprint $table)
		{
			$table->dropColumn("reset_requested_on");
		});
	}

}
