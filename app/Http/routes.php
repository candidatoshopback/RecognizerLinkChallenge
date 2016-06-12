<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'api','middleware' => 'cross'], function () {
    Route::post('notify',['uses' => 'NotifyController@notify']);
    Route::post('client/create',['uses' => 'ClientController@create']);
    Route::get('xml/import/{id}',['uses' => 'XmlImporterController@import']);
});