<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketAttachments extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create("tickets_attachment", function(Blueprint $table)
		{
			$table->increments("id");
			$table->integer("thread_id");
			$table->integer("message_id");
			$table->boolean("has_attachment");
			$table->string("attachment_path");
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
		Schema::dropIfExists("tickets_attachment");
	}

}
