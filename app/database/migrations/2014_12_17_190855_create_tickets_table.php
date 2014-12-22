<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create("tickets", function(Blueprint $table)
		{
			$table->increments("id");
			$table->integer("customer_id");
			$table->string("priority");
			$table->integer("company_id");
			$table->integer("department_id");
			$table->boolean("has_attachment");
			$table->string("attachment_path");
			$table->string("subject");
			$table->text("description");
			$table->tinyInteger("status");
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
		Schema::dropIfExists('tickets');
	}

}
