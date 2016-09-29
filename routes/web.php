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

Auth::routes();

Route::get('/logout', 'Auth\LoginController@logout');

Route::get('myaccount', 'HomeController@dashboard');

Route::group(['prefix' => 'repository'], function()
{
    Route::get('/', 'RepositoryController@list');
    Route::get('new', 'RepositoryController@new');
    Route::post('new', 'RepositoryController@save');
    Route::get('{repo}', 'RepositoryController@manage');
    // Route::get('{repo}/details', 'RepositoryController@details');
    // Route::get('{repo}/clone', 'RepositoryController@clone');
    Route::get('{repo}/branches', 'RepositoryController@branches');
    Route::get('{repo}/files', 'RepositoryController@changedFiles');
    Route::get('{repo}/test', 'RepositoryController@testing');
});

Route::group(['prefix' => 'environment'], function()
{
    Route::get('{repo}/new', 'EnvironmentController@new');
    Route::post('new', 'EnvironmentController@save');
    Route::get('{environment}', 'EnvironmentController@manage');
});

Route::group(['prefix' => 'server'], function()
{
    Route::get('{environment}/{server}/new', 'ServerController@new');
    Route::post('save', 'ServerController@save');
    Route::get('{server}/manage', 'ServerController@manage');
});