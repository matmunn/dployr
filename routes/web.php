<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');
Route::get('pricing', 'HomeController@pricing');

Auth::routes();

Route::get('/logout', 'Auth\LoginController@logout');

Route::get('myaccount', 'HomeController@dashboard');
Route::get('about', 'HomeController@about');

Route::group(['prefix' => 'repository'], function () {
    Route::get('/', 'RepositoryController@list');
    Route::get('new', 'RepositoryController@new');
    Route::post('new', 'RepositoryController@save');
    Route::get('{repo}', 'RepositoryController@manage');
    // Route::get('{repo}/details', 'RepositoryController@details');
    // Route::get('{repo}/clone', 'RepositoryController@clone');
    // Route::get('{repo}/branches', 'RepositoryController@branches');
    // Route::get('{repo}/files', 'RepositoryController@changedFiles');
    // Route::get('{repo}/test', 'RepositoryController@testing');
    Route::get('{repo}/initialise', 'RepositoryController@initialise');
    Route::get('{repo}/key', 'RepositoryController@key');
    Route::delete('{repo}', 'RepositoryController@delete');
});

Route::group(['prefix' => 'environment'], function () {
    Route::get('{repo}/new', 'EnvironmentController@new');
    Route::post('new', 'EnvironmentController@save');
    Route::get('{environment}', 'EnvironmentController@manage');
    Route::delete('{environment}', 'EnvironmentController@delete');
    Route::get('{environment}/deploy', 'EnvironmentController@deploy');
});

Route::group(['prefix' => 'notifier'], function () {
    Route::get('{environment}/{type}/new', 'NotifierController@new');
    Route::post('save', 'NotifierController@save');
    Route::delete('delete', 'NotifierController@delete');
});

Route::group(['prefix' => 'server'], function () {
    Route::get('{environment}/{server}/new', 'ServerController@new');
    Route::post('save', 'ServerController@save');
    Route::get('{server}/manage', 'ServerController@manage');
});
