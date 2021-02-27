<?php

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

Route::get('/', 'HomeController@index')->name('index');

Route::group(['prefix' => 'production'], function (){
    Route::get('/', 'SearchController@production')->name('production.index');

    Route::get ('/search', 'SearchController@production')->name('search');
    Route::post('/search', 'SearchController@search');
    Route::post('/store', 'SearchController@store')->name('store');;
    Route::get ('/store', 'SearchController@storeGet');


    Route::get ('/delete', 'SearchController@production');
    Route::post ('/delete', 'SearchController@delete')
        ->middleware('admin:admin')
        ->name('production.delete');
});

Route::group(['prefix' => 'statistics'], function () {
    Route::get('/', 'StatisticsController@statistics')->name('statistics');
    Route::post('/', 'StatisticsController@statisticsPost');

    Route::get('/download-file-day', 'StatisticsController@statistics')->name('statistics.downloadFileDay');
    Route::post('/download-file-day', 'StatisticsController@downloadFileDay');

    Route::get('/download-file-month', 'StatisticsController@downloadFileMonth')
        ->middleware('admin:admin')
        ->name('statistics.downloadFileMonth');
//    Route::post('/download-file-month', 'StatisticsController@downloadFileMonth');

    Route::post('/download-last-file', 'StatisticsController@downloadLastFile')
        ->middleware('admin:admin')
        ->name('statistics.downloadLastFile');

});

Route::get('/login', 'Auth\LoginController@loginGet')->name('login');
Route::post('/login', 'Auth\LoginController@loginPost');

Route::get('/logout', 'Auth\LoginController@logoutGet')->name('logout');
Route::post('/logout', 'Auth\LoginController@logout');

//Route::get('register', [\App\Http\Controllers\Auth\LoginController::class, 'register']);

Route::group(['prefix' => 'management', 'middleware' => 'admin:admin'], function () {
    Route::get('/', 'ManagementController@index')->name('management');

    Route::get('/add', 'ManagementController@directing')->name('management.add');
    Route::post('/add', 'ManagementController@add');

//    Route::get('/modify', 'ManagementController@directing')->name('management.modify');
//    Route::post('/modify', 'ManagementController@modify');
});