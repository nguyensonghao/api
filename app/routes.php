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

Route::get('demo', function () {
	date_default_timezone_set('Europe/London');
	$date = new DateTime();
	$timeServer = $date->getTimestamp();
	echo $timeServer;
});

Route::get('demo2', function () {
	function get_url_contents($url) {
		$crl = curl_init();
		curl_setopt($crl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
		curl_setopt($crl, CURLOPT_URL, $url);
		curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($crl, CURLOPT_CONNECTTIMEOUT, 5);
		$ret = curl_exec($crl);
		curl_close($crl);
		return $ret;
	}
	$json = get_url_contents('http://ajax.googleapis.com/ajax/services/search/images?v=1.0&q=sausages');
	// $data = json_decode($json);
	// foreach ($data->responseData->results as $result) {
	// 	$results[] = array('url' => $result->url, 'alt' => $result->title);
	// }
	// print_r($results); 
	echo $json;
});