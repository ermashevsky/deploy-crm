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
Route::get('/manageCRM', "SettingController@manageCRM");
Route::get('/viewUserCRM', "SettingController@viewUserCRM");
Route::get('/viewAsteriskSettings', "SettingController@viewAsteriskSettings");
Route::post('/createCRMDB', "SettingController@createCRMDB");
Route::post('/saveNewCRMData', 'SettingController@createNewRow');
Route::post('/getRowByPrimaryKey', 'SettingController@getRowByPrimaryKey');
Route::post('/createVhost', 'SettingController@createVhostEnvironment');
Route::post('/viewModuleList', "SettingController@viewModuleList");
Route::post('/viewUsersList', "SettingController@viewUsersList");
Route::post('/getUserDetail', "SettingController@getUserDetail");
Route::post('/deleteUser', "SettingController@deleteUser");
Route::post('/deleteCRM', "SettingController@deleteCRM");
Route::post('/updateUserDetails', "SettingController@updateUserDetails");
Route::post('/createAsteriskSettingsXMLFile', "SettingController@createAsteriskSettingsXMLFile");
Route::post('/getAsteriskSettings', "SettingController@getAsteriskSettings");
Route::post('/updateAsteriskSettings', "SettingController@updateAsteriskSettings");
Route::post('/moveXMLFile', "SettingController@moveXMLFile");
Route::post('/checkBoxTrigger', "SettingController@checkBoxTrigger");
Route::get('/checkInstallationCRM', "SettingController@checkInstallationCRM");
Route::post('/getNotInstalledModules', "SettingController@getNotInstalledModules");
Route::get('/checkModuleList', "SettingController@checkModuleList");
Route::post('/installModule', "SettingController@installModule");
Route::post('/checkVersionModule', "SettingController@checkVersionModule");
Route::post('/startUpdateModuleVersion', "SettingController@startUpdateModuleVersion");
Route::post('/saveNewUser', "SettingController@saveNewUser");






