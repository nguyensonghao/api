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

Route::get('demo', function () {
	return View::make('demo');
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

Route::get('api/active/{key}', 'AcountController@actionActiveUser');

Route::get('api/reset/{key}', 'AcountController@actionResetPasswordSysterm');

Route::get('test', function () {
	return View::make('test');
});

// ReportMean

Route::controller('report', 'ReportMeanController');

Route::post('api/add-mean', 'ReportMeanController@actionAddReportMean');

