<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWordCloneTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('words_clone', function ($table) {
			$table->increments('id');
			$table->integer('id_word');
			$table->integer('id_subject');
			$table->integer('id_course');
			$table->string('word');
			$table->string('mean');
			$table->text('url1');
			$table->text('url2');
			$table->text('url3');
			$table->integer('status');
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
