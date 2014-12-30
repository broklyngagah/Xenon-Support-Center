<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsOnlineUsers2 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table("online_users", function(Blueprint $table)
		{
			$table->integer("company_id");
			$table->integer("department_id");
		});

		Schema::table("closed_conversations", function(Blueprint $table)
		{
			$table->integer("company_id");
			$table->integer("department_id");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table("online_users", function(Blueprint $table)
		{
			$table->dropColumn("company_id");
			$table->dropColumn("department_id");
		});

		Schema::table("closed_conversations", function(Blueprint $table)
		{
			$table->dropColumn("company_id");
			$table->dropColumn("department_id");
		});
	}

}
