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
			$table->increments('reportId');
			$table->integer('userId');
			$table->string('wordId');
			$table->integer('status');
			$table->integer('like');
			$table->integer('dislike');
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
