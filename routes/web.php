<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('home');
});

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]], function()
{

    Route::group(['prefix' => 'admin'] , function () {
        Auth::routes();

    });

    Route::group(['namespace' => 'Admin', 'prefix' => 'admin'] , function () {

    Route::group(['middleware' => ['role:admin|moderator','auth']],function(){
        Route::get('dashboard', 'DashboardController@index')->name('home');
        Route::resource('/product','productController');
        Route::resource('/recommendation','recommendationController');
        Route::resource('/academy','AcademyController');
        Route::resource('/level','levelController');
        Route::resource('/vip','VipController');
        Route::resource('/currency','CurrencyController');
        Route::get('/OrderedRecommendations','recommendationController@getOrderRecommendation')->name('getOrder.recommendations');
        Route::delete('/OrderedRecommendations/{id}','recommendationController@destroyOrderRecommendation')->name('deleteOrder.recommendations');
        Route::put('/userEditVip','UsersController@editUserVip')->name('user.vip.edit');
        Route::put('/userEditLevel','UsersController@editUserLevel')->name('user.level.edit');
        Route::get('/SendNotification','NotificationController@create')->name('Notification.create');
        Route::post('/Send','NotificationController@send')->name('Notification.send');


        Route::get('/notifications','NotificationController@index')->name('Notification.index');
        Route::get('/markAsRead','NotificationController@markAsRead')->name('Notification.markAsRead');
    });

    Route::group(['middleware' => ['role:admin|moderator','auth']],function(){

        Route::resource('/banner','bannerController')->except(['create', 'show', 'destroy']);


    });
    Route::group(['middleware' => ['role:admin','auth']],function(){
        Route::resource('/user','UsersController');
        // Route::put('/updateUser/{id}','UsersController@update')->name('userupdate');

        /* Site Settings Routes */
        Route::get('site_settings', 'SiteSettingController@generalShow')->name('settings.site.show');
        Route::put('site_settings', 'SiteSettingController@generalUpdate')->name('settings.site.update');

        Route::get('social_settings', 'SiteSettingController@socialShow')->name('settings.social.show');
        Route::put('social_settings', 'SiteSettingController@socialUpdate')->name('settings.social.update');

        Route::get('/userAddRole','UsersController@addRole');
        Route::put('/userEditRole','UsersController@editUserRole')->name('user.role.edit');


    });


    Route::get('/vo','productController@createvo');

    });



});
Route::get('login/{provider}', '\App\Http\Controllers\Auth\LoginController@redirectToProvider')->name('social.login');
Route::get('login/{provider}/callback', '\App\Http\Controllers\Auth\LoginController@handleProviderCallback');
// Auth::routes();


