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

Route::get('/', function () {
    return view('home');
});

Auth::routes();

Route::get('/logout', 'Auth\LoginController@logout');

Route::get('/home', 'HomeController@index');
Route::get('myaccount', 'HomeController@dashboard');

Route::group(['prefix' => 'repository'], function()
{
    Route::get('/', 'RepositoryController@list');
    Route::get('new', 'RepositoryController@new');
    Route::post('new', 'RepositoryController@save');
    Route::get('{repo}/manage', 'RepositoryController@manage');
    Route::get('{repo}/details', 'RepositoryController@details');
    Route::get('{repo}/clone', 'RepositoryController@clone');
});