<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMailRows extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$setting = new Settings();
		$setting->key = "mail";
		$setting->value = json_encode([
			'api_key'=>"",
			'domain'=>"",
			'email'=>"",
			'name'=>""
		]);
		$setting->save();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Settings::where('key','mail')->delete();
	}

}
