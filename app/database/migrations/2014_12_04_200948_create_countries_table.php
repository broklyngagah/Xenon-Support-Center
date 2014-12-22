<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('countries', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('countryCode');
            $table->string('countryName');
            $table->string('currencyCode');
            $table->string('fipsCode');
            $table->string('isoNumeric');
            $table->string('isoAlpha3');
            $table->string('geonameId');
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
		Schema::dropIfExists("countries");
	}

}
