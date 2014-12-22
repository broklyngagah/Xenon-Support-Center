<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTranslationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create("translations", function(Blueprint $table)
		{
			$table->increments("id");
			$table->string("language_name");
			$table->string("language_code");
			$table->boolean("active");
		});

		DB::table("translations")->insert(['language_name'=>"English",'language_code'=>"en",'active'=>1]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists("translations");
	}

}
