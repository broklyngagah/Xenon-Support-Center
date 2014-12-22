<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillSettingsMailchimp extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$setting = new Settings();
		$setting->key = "mailchimp";
		$setting->value = json_encode([
			'api_key'=>"",
			'use_mailchimp'=>""
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
		Settings::where('key','mailchimp')->delete();
	}

}
