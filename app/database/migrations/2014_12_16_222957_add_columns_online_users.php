<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsOnlineUsers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create("closed_conversations", function(Blueprint $table)
		{
			$table->increments("id");
			$table->integer("user_id");
			$table->integer("thread_id");
			$table->integer("operator_id");
			$table->datetime("requested_on");
			$table->datetime("started_on");
			$table->string("token");
			$table->datetime("ended_on");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists("closed_conversations");
	}

}
