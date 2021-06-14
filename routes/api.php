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
Route::post('checkAvailabel', 'AuthController@checkAvailabel');

Route::get('/autoResult', 'AutoResultController@index');
Route::get('/autoPaymentCheck', 'PaymentController@autoPaymentCheck');
Route::get('/resultReset', 'ResultController@reset');

Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResource('user', 'UserController');


    Route::apiResource('bookieReferal', 'BookieReferalController');

    Route::apiResource('play', 'PlayController');
    Route::apiResource('withdraw', 'WithdrawController');
    Route::post('cancelWithdraw', 'WithdrawController@cancelByUser');
    Route::apiResource('history', 'HistoryController');
    Route::get('getBookieRates', 'BookieRateController@getBookieRates');
    Route::get('getBookie', 'BookieRateController@getBookie');

    //bookie
    Route::apiResource('bookieUser', 'BookieUserController')->middleware('can:bookie');
    Route::post('searchUser', 'BookieUserController@searchUser')->middleware('can:bookie');
    Route::post('generateReport', 'BookieUserController@generateReport')->middleware('can:bookie');
    Route::post('searchToken', 'BookieUserController@searchToken')->middleware('can:bookie');
    Route::post('addBal', 'BookieUserController@addBal')->middleware('can:bookie');
    Route::post('userStatus', 'BookieUserController@userStatus')->middleware('can:bookie');
    Route::post('searchUserForAuto', 'BookieUserController@searchUserForAuto')->middleware('can:bookie');

    Route::get('withdrawList', 'WithdrawController@withdrawList')->middleware('can:bookie');
    Route::get('userHistory/{id}', 'BookieUserController@userHistory')->middleware('can:bookie');
    Route::get('tokenHistory/{id}', 'BookieUserController@tokenHistory')->middleware('can:bookie');
    Route::apiResource('notification', 'NotificationController');

    //admin

    Route::post('getUsers', 'AdminController@getUsers')->middleware('can:admin');
    Route::post('getWinnerList', 'AdminController@getWinnerList')->middleware('can:admin');
    Route::get('bookieList', 'AdminController@bookieList')->middleware('can:admin'); //for assigning bookie
    Route::post('assignBookie', 'AdminController@assignBookie')->middleware('can:admin');
    Route::post('makeBookie', 'AdminController@makeBookie')->middleware('can:admin');
    Route::post('bookieList', 'AdminController@bookieListIndex')->middleware('can:admin');
    Route::post('addBalAdmin', 'AdminController@addBalAdmin')->middleware('can:admin');
    Route::post('agentStatus', 'AdminController@agentStatus')->middleware('can:admin');
    Route::post('deleteUser', 'AdminController@deleteUser')->middleware('can:admin');
    Route::get('adminHistory/{id}', 'AdminController@adminHistory')->middleware('can:admin');
    Route::get('adminwithdrawList', 'WithdrawController@adminwithdrawList')->middleware('can:admin');
    Route::get('bookieWithdrawList', 'WithdrawController@bookieWithdrawList')->middleware('can:admin');

    Route::apiResource('bookieRate', 'BookieRateController')->middleware('can:admin');

    Route::prefix('admin')->group(function () {

        Route::apiResource('/', 'AdminController')->middleware('can:admin');
        Route::apiResource('package', 'PackageController')->middleware('can:admin');
        Route::apiResource('games', 'GameController');
        Route::apiResource('result', 'ResultController');
        Route::get('/resultReset', 'ResultController@reset')->middleware('can:admin');
        Route::post('/cancelGame', 'ResultController@cancelGame')->middleware('can:admin');
        Route::apiResource('payments', 'PaymentController')->middleware('can:admin');
        Route::post('/searchUser', 'AdminController@searchUser')->middleware('can:admin');
        Route::post('/generateReport', 'AdminController@generateReport')->middleware('can:admin');
    });
});
