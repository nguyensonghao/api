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
	        $table->string('idx');
	        $table->integer('categoryId');
	        $table->string('date');
			$table->string('type');
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
