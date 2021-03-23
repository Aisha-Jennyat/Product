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
    Route::post('/store', 'SearchController@store')->name('store');
    Route::get ('/store', 'SearchController@storeGet');


    Route::get ('/delete', 'SearchController@production');
    Route::post ('/delete', 'SearchController@delete')
        ->middleware('admin:admin')
        ->name('production.delete');
});

Route::group(['prefix' => 'statistics'], function () {
    Route::get('/', 'StatisticsController@statistics')->name('statistics');
    Route::post('/', 'StatisticsController@statisticsPost');


    Route::post('/delete-row','StatisticsController@deleteRow')->name('statistic.delete');
    Route::post('/modify', 'StatisticsController@editProduction')->name('modify');
    Route::post('/confirm', 'StatisticsController@confirmProduction')->name('confirm');

    Route::get('/download-file-day', 'StatisticsController@statistics')->name('statistics.downloadFileDay');
    Route::post('/download-file-day', 'StatisticsController@downloadFileDay');

    Route::get('/download-file-month', 'StatisticsController@downloadFileMonth')
        ->name('statistics.downloadFileMonth');


    Route::post('/download-last-file', 'StatisticsController@downloadLastFile')
        ->middleware('admin:admin')
        ->name('statistics.downloadLastFile');

});

Route::get('/login', 'Auth\LoginController@loginGet')->name('login');
Route::post('/login', 'Auth\LoginController@loginPost');

Route::get('/logout', 'Auth\LoginController@logoutGet')->name('logout');
Route::post('/logout', 'Auth\LoginController@logout');


Route::group(['prefix' => 'management', 'middleware' => 'admin:admin'], function () {
    Route::get('/', 'ManagementController@index')->name('management');

    Route::get('/add', 'ManagementController@directing')->name('management.add');
    Route::post('/add', 'ManagementController@add');


});