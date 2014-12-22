<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThreadGeoinfo extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create("thread_geo_info", function(Blueprint $table)
		{
			$table->increments("id");
			$table->integer("thread_id");
			$table->string("ip_address");
			$table->string("country_code");
			$table->string("country");
			$table->string("provider");
			$table->string("current_page");
			$table->text("all_pages");
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
		Schema::dropIfExists("thread_geo_info");
	}

}
