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


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/socialLogin','AuthController@socialLogin');

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

Route::namespace("Api")->group(function () {

    Route::get('getRecommendations/{user_id}', 'RecommendationController@getRecommendations');
    Route::post('buyRecommendation', 'RecommendationController@buyRecommendation');
    Route::post('buyAcademy', 'AcademyController@buyAcademy');
    Route::get('getUserOrders/{id}', 'RecommendationController@getUserOrders');

    //afilliate

    // Route::post('genCode','affiliateController@genCode');
    // Route::post('addPoints','affiliateController@addPoints');
    Route::get('getPoints/{user}','affiliateController@getUserPoints');
    Route::post('upgrade','UserController@upgradeLevel');
    Route::post('buyVip','UserController@buyVip');
    Route::post('addPoints','UserController@addPoints');
    Route::post('updateVip','UserController@updateVip');

//get levels
    Route::get('getLevels', 'LevelController@getlevels');
//get vip
    Route::get('getVips', 'VipController@getvips');
//get academy
    Route::get('getAcademy/{user_id}', 'AcademyController@getAcademies');
    //open recommendation
    Route::post('openRecommendation', 'RecommendationController@openRecommendation');

////////////////////////////////////////////////////////////////////////////////////////////





    // update user data
    Route::post('updateUser', 'UserController@updateUser');


// hotline
Route::get('getSettings', 'UserController@Setting');

Route::get('/notifications/{user_id}','UserController@notifications');
Route::post('/markAsRead','UserController@notificationsMarkAsRead');


});
