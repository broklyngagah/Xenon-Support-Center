<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplatePairTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create("paired_templates", function(Blueprint $table)
		{
			$table->increments("id");
			$table->string("name");
			$table->string("view");
			$table->integer("template_id");
			$table->string("template_name");
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
		Schema::dropIfExists("paired_templates");
	}

}
