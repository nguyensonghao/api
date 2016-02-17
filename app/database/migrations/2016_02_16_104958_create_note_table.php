<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('note', function ($table) {
			$table->increments('noteId');
	        $table->string('noteName');
	        $table->string('noteMean');
	        $table->integer('categoryId');
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
		Schema::create('note', function ($table) {
			$table->string('date');
			$table->string('type');
	    });
	}

}
