<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportMeanTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('report_mean', function ($table) {
			$table->increments('id');
			$table->string('email');
			$table->integer('userId');
			$table->integer('wordId');
			$table->integer('status');
			$table->integer('rate');
			$table->string('mean');
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
		//
	}

}
