<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('login', 'AuthController@login');
Route::post('register', 'AuthController@register');
Route::post('forgot', 'AuthController@forgotPassword')->name('auth.forgotten.api');
Route::get('test','Developer\DeveloperController@test');

Route::get('app-info','AppController@info');
Route::group(['middleware' => ['auth:api','maybelater','suspend']], static function(){
    Route::post('/user/change-password','AuthController@changePassword');
    Route::post('/user/change-avatar','AuthController@changeAvatar');
    Route::get('/user/info','AuthInfoController@info');
    Route::get('/user/information','AuthInfoController@notifications');
    Route::get('/user/profile','AuthInfoController@profile');
    Route::post('/user/add-reference','ReferenceController@enterRefCode');
    Route::get('/user/references','ReferenceController@myReferenceUsers');
    Route::post('/register/fcm-token','FirebaseController@registerFCMToken');

    Route::get('/mission/all','MissionController@all');
    Route::get('/mission/current','MissionController@current');
    Route::get('/mission/new','MissionController@takeNewMissions');
    Route::post('/mission/get','MissionController@getMission');
    Route::post('/mission/answer','MissionController@answerMission');
    Route::post('/mission/payment-status','MissionController@checkPayment');
    Route::post('/mission/expired','MissionController@setTimeExpired');

    Route::get('/ads/diamond/info','RewardedVideoController@diamondDetails');
    Route::get('/ads/diamond/create','RewardedVideoController@createAdDiamond');
    Route::post('/ads/diamond/check','RewardedVideoController@checkAdDiamond');
    Route::get('/diamond/skip-seconds','RewardedVideoController@skipTheSeconds');

    Route::get('competitions/all','CompetitionController@competitions');
    Route::get('competitions/registered','CompetitionController@registered');

    Route::get('/banks','PaymentRequestController@getBanks');

    Route::post('payment-request','PaymentRequestController@newPaymentRequest');
    Route::get('payment-request-all','PaymentRequestController@getAllRequests');

    Route::post('support/create-thread','TicketController@createThread');
    Route::post('support/create-message','TicketController@createMessage');
    Route::get('support/threads','TicketController@getThreads');
    Route::post('support/thread-detail','TicketController@threadDetail');
    Route::get('support/information','TicketController@ticketInformations');

    Route::post('/report/response','MediaCountController@responseReporter');


});
