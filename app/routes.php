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

// ReportMobile

Route::controller('report-mobile', 'ReportMeanMobileController');

Route::post('api/add-mean-mobile', 'ReportMeanMobileController@actionAddReportMean');

Route::post('api/get-mean-mobile', 'ReportMeanMobileController@actionGetMean');

Route::post('api/rate-mean-mobile', 'ReportMeanMobileController@actionRateMean');

Route::post('api/get-rate-mobile', 'ReportMeanMobileController@actionGetRateReport');

Route::post('api/check-mean-mobile', 'ReportMeanMobileController@actionCheckMean');

Route::post('api/update-mean-mobile', 'ReportMeanMobileController@actionUpdateMean');

Route::post('api/delete-mean-mobile', 'ReportMeanMobileController@actionDeleteMean');

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

// Sync

Route::controller('sync', 'SyncController');

Route::post('api/get-time', 'SyncController@actionGetTime');

Route::post('api/pull-note', 'SyncController@actionPullNoteServer');

Route::post('api/push-note-new', 'SyncController@actionPushNoteNewServer');

Route::post('api/update-note-server', 'SyncController@actionUpdateNote');

Route::post('api/pull-cate', 'SyncController@actionPullCateServer');

Route::post('api/push-cate-new', 'SyncController@actionPushCateNewServer');

Route::post('api/update-cate-server', 'SyncController@actionUpdateCate');

// Word

Route::controller('word', 'WordController');

Route::get('danh-sach-anh-da-duyet/{id_course}/{id_subject}', 'WordController@showListImageExcuted');

Route::get('danh-sach-anh-chua-duyet/{id_course}/{id_subject}', 'WordController@showListImageNotExcuted');

Route::get('dang-nhap', 'WordController@showLogin');

Route::get('dang-xuat', 'WordController@actionLogout');

Route::get('them-admin', 'WordController@showAddAdmin');

Route::get('xuat-du-lieu/{id_course}', 'WordController@actionExportData');

Route::get('sap-xep-du-lieu/{id_course}', 'WordController@sortDataSubject');

Route::get('xuat-du-lieu-khoa-hoc', 'WordController@showExportDataCourse');

Route::get('xuat-du-lieu-topic', 'WordController@showExportDataSubject');

Route::get('xuat-du-lieu-json/{id_course}', 'WordController@sortDataSubjectJson');

Route::get('xuat-du-lieu-course-json', 'WordController@sortDataCourseJson');

Route::post('hoan-thanh-duyet-anh', 'WordController@actionCompleteImage');

Route::post('lay-danh-sach-anh', 'WordController@actionGetImageUrl');

Route::post('tai-anh-ve', 'WordController@actionDownloadImage');

Route::post('them-anh', 'WordController@actionLoadMoreImageUrl');

Route::post('sua-nghia', 'WordController@actionFixMean');

Route::post('dang-nhap', 'WordController@actionLogin');

Route::post('them-admin', 'WordController@actionAddAdmin');

