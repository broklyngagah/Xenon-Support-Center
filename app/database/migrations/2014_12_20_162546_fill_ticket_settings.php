<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillTicketSettings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$setting = new Settings();
		$setting->key = "tickets";
		$setting->value = json_encode([
			'should_send_email_ticket_reply'=>true,
			'convert_chat_ticket_no_operators'=>true
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
		Settings::where('key','tickets')->delete();
	}

}
