<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChatSettings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$setting = new Settings();
		$setting->key = "chat";
		$setting->value = json_encode([
			'chat_file_types'=>'jpeg,bmp,png,jpg',
			'max_file_size'=>'5',
			'enable_attachment_in_chat'=>true
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
		Settings::where('key','chat')->delete();
	}

}
