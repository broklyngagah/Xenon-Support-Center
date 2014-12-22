<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnlineUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create("online_users", function(Blueprint $table)
		{
			$table->increments("id");
			$table->string("user_id");
			$table->integer("thread_id");
			$table->integer("operator_id");
			$table->boolean("locked_by_operator");
			$table->datetime("requested_on");
			$table->datetime("started_on");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists("online_users");
	}

}
