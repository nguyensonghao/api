<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWordsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('words', function ($table) {
			$table->increments('id');
			$table->integer('id_word');
			$table->integer('id_subject');
			$table->integer('id_course');
			$table->string('word');
			$table->string('mean');
			$table->string('example');
			$table->string('example_mean');
			$table->integer('num_ef');
			$table->string('time_date');
			$table->string('next_time');
			$table->integer('num_n');
			$table->integer('num_i');
			$table->integer('max_q');
			$table->string('phonectic');
			$table->string('des');
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
