<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	echo 'This is admin Mazii';
});

Route::get('test', function () {
	return View::make('test');
});

// Excute import database sqlite to elasticsearch
Route::controller('import', 'ImportElasticController');

Route::get('import-example', 'ImportElasticController@importExample');

Route::get('import-jaen', 'ImportElasticController@importJaen');

Route::get('import-kanji', 'ImportElasticController@importKanji');

// Search
Route::controller('search', 'SearchController');

Route::post('api/example/{key}', 'SearchController@searchExample');

Route::post('api/kanji/{key}/{numberRecord}', 'SearchController@searchKanji');

Route::post('api/word/{key}', 'SearchController@searchJaen');

Route::post('api/jlpt/{begin}/{level}', 'SearchController@getJLPT');

// Acount

Route::controller('acount', 'AcountController');

Route::post('api/login', 'AcountController@actionLogin');

Route::post('api/register', 'AcountController@actionRegister');

Route::post('api/init-login', 'AcountController@actionInit');

Route::post('api/logout', 'AcountController@actionLogout');

Route::post('api/reset-password', 'AcountController@actionResetPassword');

Route::post('api/change-password', 'AcountController@actionChangePassword');

Route::post('api/reset-password-really', 'AcountController@actionResetPasswordReally');

Route::post('api/change-username', 'AcountController@actionChangeUsername');

Route::get('api/active/{key}', 'AcountController@actionActiveUser');

Route::get('api/reset/{key}', 'AcountController@actionResetPasswordSysterm');

// ReportMean

Route::controller('report', 'ReportMeanController');

Route::post('api/add-mean', 'ReportMeanController@actionAddReportMean');

Route::post('api/get-mean', 'ReportMeanController@actionGetMean');

Route::post('api/rate-mean', 'ReportMeanController@actionRateMean');

Route::post('api/get-rate', 'ReportMeanController@actionGetRateReport');

Route::post('api/check-mean', 'ReportMeanController@actionCheckMean');

Route::post('api/update-mean', 'ReportMeanController@actionUpdateMean');

Route::post('api/delete-mean', 'ReportMeanController@actionDeleteMean');

Route::post('api/get-new', 'ReportMeanController@actionGetNew');

// MyNote

Route::controller('note', 'MyNoteController');

Route::post('api/get-mynote', 'MyNoteController@getMyNote');

Route::post('api/add-category', 'MyNoteController@addCategory');

Route::post('api/add-note', 'MyNoteController@addNote');

Route::post('api/update-category', 'MyNoteController@updateCategory');

Route::post('api/update-note', 'MyNoteController@updateNote');

Route::post('api/delete-category', 'MyNoteController@deleteCategory');

Route::post('api/delete-note', 'MyNoteController@deleteNote');

// Mazii

Route::controller('mazii', 'MaziiController');

Route::post('api/check-trial', 'MaziiController@actionCheckTrialUser');

// Flashcard


Route::controller('flash', 'FlashController');

Route::post('api/get-flashcard', 'FlashController@getFlashCard');

Route::post('api/remember-flash', 'FlashController@rememberFlashCard');

Route::post('api/forget-flash', 'FlashController@forgetFlashCard');

// Word

Route::controller('word', 'WordController');

Route::get('danh-sach-anh-da-duyet/{id_course}', 'WordController@showListImageExcuted');

Route::get('danh-sach-anh-chua-duyet/{id_course}', 'WordController@showListImageNotExcuted');

Route::post('hoan-thanh-duyet-anh', 'WordController@actionCompleteImage');

Route::post('lay-danh-sach-anh', 'WordController@actionGetImageUrl');

Route::post('tai-anh-ve', 'WordController@actionDownloadImage');

Route::post('them-anh', 'WordController@actionLoadMoreImageUrl');

Route::post('sua-nghia', 'WordController@actionFixMean');

Route::get('test', function () {
	ini_set('max_execution_time', 600000000);
	$list_data = DB::table('words')->where('word', '')->where('id_course', '>', 104000000)->where('id_course', '<', 104000009)
	->get();
	foreach ($list_data as $key => $value) {
		$phonectic = $value->phonectic;
		$word = $value->word;
		if ($word == null || $word == '') {
			DB::table('words')->where('id', $value->id)->update(array('word' => $phonectic));
		}
	}
});