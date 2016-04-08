<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubjectsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('subjects', function ($table) {
			$table->integer('id');
			$table->string('name');
			$table->integer('id_course');
			$table->string('mean');
			$table->integer('total');
			$table->string('number_word');
			$table->string('time_date');			
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
