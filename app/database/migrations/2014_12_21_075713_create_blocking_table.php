<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlockingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create("blocking", function(Blueprint $table)
		{
			$table->increments("id");
			$table->string("ip_address");
			$table->boolean("should_block_chat");
			$table->boolean("should_block_tickets");
			$table->boolean("should_block_login");
			$table->boolean("should_block_web_access");
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists("blocking");
	}

}
