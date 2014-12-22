<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStampsTickets extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table("tickets", function(Blueprint $table)
		{
			$table->datetime("requested_on");
			$table->datetime("started_on");
			$table->dropColumn("created_at");
			$table->dropColumn("updated_at");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table("tickets", function(Blueprint $table)
		{
			$table->dropColumn("requested_on");
			$table->dropColumn("started_on");
			$table->timestamps();
		});
	}

}
