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

// Route::controller('flashcard', 'FlashCardController');

// Route::post('api/get-flashcard', 'FlashCardController@getFlashCard');

// Route::get('api/remember-flash', 'FlashCardController@rememberFlashCard');

// Route::post('api/forget-flash', 'FlashCardController@forgetFlashCard');

Route::controller('flash', 'FlashController');

Route::post('api/get-flashcard', 'FlashController@getFlashCard');

Route::post('api/remember-flash', 'FlashController@rememberFlashCard');

Route::post('api/forget-flash', 'FlashController@forgetFlashCard');

Route::get('demo', function () {
	$result = json_decode(file_get_contents('http://admin:kanjiapp@128.199.226.39:5984/kanjiapp/b2e169caae52ec7c9b5cabff7bb4e189'));	
	$list_data = DB::table('report_mean')->get();
	foreach ($list_data as $key => $value) {
		$id = $value->wordId;
		$word = json_decode(file_get_contents('http://admin:kanjiapp@128.199.226.39:5984/kanjiapp/' . $id));
		$wordmean = $word->word;
		DB::table('report_mean')->where('wordId', $wordId)->update(array('word' => $wordmean));
	}
});
