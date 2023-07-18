<?php

use Illuminate\Http\Request;

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
Route::group(['prefix' => 'v1' , 'as' => 'v1'], function () {
    Route::post('login', 'v1\UserController@UserLogin');
    Route::post('forgot-password-request', 'v1\UserController@forgotpassword');
    Route::post('forgot-password-submit', 'v1\UserController@forgotpasswordSubmit');
    
	Route::get('get-settings', 'v1\SettingsController@getSettings');
	Route::get('get-settings-2', 'v1\SettingsNewController@getSettings');
	Route::get('get-updates', 'v1\SettingsController@getUpdatetime');
	
	
	//Route::post('register', 'v1\UserController@register');
	
	Route::group(['middleware' => 'jwt.verify'], function () {
		 Route::get('logout', 'v1\UserController@logout');
		 Route::get('get-users', 'v1\UserController@getUsers');
		 
		 Route::get('get-sync-enable', 'v1\SurveyController@checkIsLocalSync');
		 Route::get('get-survey-list', 'v1\SurveyController@getSurveyA');
		 Route::get('get-survey-detail', 'v1\SurveyController@getSurveyDetail');
		 
		 Route::post('survey-duplicate', 'v1\SurveyController@surveyDublicate');
		 Route::post('survey-assign', 'v1\SurveyController@assignSurvey');
		 Route::post('action-on-assign', 'v1\SurveyController@actionOnAssign');
		 
		 
		 Route::post('change-password', 'v1\UserController@changePassword');
		 Route::post('sync-upload', 'v1\SettingsController@syncData');
		 Route::post('sync-upload-2', 'v1\SettingsNewController@syncData');
		 Route::post('image-single-upload', 'v1\SettingsController@singleUpload');
	});	
	
	Route::get('get-survey-detail-test', 'v1\SurveyController@getSurveyDetail');
});

