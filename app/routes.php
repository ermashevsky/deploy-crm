<?php 

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', "SettingController@index");
Route::get('/createCRM', "SettingController@createCRMForm");
Route::post('/createCRMDB', "SettingController@createCRMDB");
Route::post('/saveNewCRMData', 'SettingController@createNewRow');
Route::post('/getRowByPrimaryKey', 'SettingController@getRowByPrimaryKey');
Route::post('/createVhost', 'SettingController@createVhostEnvironment');


