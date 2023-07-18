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



Route::post('sign-in', 'Auth\LoginController@loginA');
Route::get('/user/verify/{token}', 'Auth\RegisterController@verifyUser');
Auth::routes();

Route::group(['middleware' => ['auth']], function () {
	Route::get('/','ProfileController@index');
}); 

Route::group(['prefix' => 'admin','middleware' => ['auth', 'roles'],'roles' => 'AU'], function () {
	Route::get('/', 'Admin\AdminController@index');
	Route::post('recover-item', 'Admin\AdminController@recoverItem');
	
	Route::get('/categories/img-view', 'Admin\CategoriesController@catImgView');
	Route::get('/categories/search', 'Admin\CategoriesController@search');
	Route::get('/categories/export', 'Admin\CategoriesController@export');
	Route::resource('/categories', 'Admin\CategoriesController');
	
	Route::get('/reference-file/{id}/delete', 'Admin\ReferenceController@destroy');
	Route::get('/reference-file/{id}/rotate', 'Admin\ReferenceController@rotate');
	
	Route::get('/items-image-edit/{id}', 'Admin\IssueController@editImages');
	Route::post('/items-image-update', 'Admin\IssueController@updateImages');
	Route::resource('/items', 'Admin\IssueController');
    Route::get('/items-data', 'Admin\IssueController@datatable');

	Route::get('roles/datatable', 'Admin\RolesController@datatable');
    Route::resource('/roles', 'Admin\RolesController');
	
	Route::get('/users/search', 'Admin\UsersController@search');
    Route::get('/users/datatable', 'Admin\UsersController@userDatatable');
    Route::resource('/users', 'Admin\UsersController');
	
	Route::resource('permissions', 'Admin\PermissionsController');
	
	
	Route::get('/profile', 'Admin\ProfileController@index')->name('profile.index');
    Route::get('/profile/edit', 'Admin\ProfileController@edit')->name('profile.edit');
    Route::patch('/profile/edit', 'Admin\ProfileController@update');
        //
    Route::get('/profile/change-password', 'Admin\ProfileController@changePassword')->name('profile.password');
    Route::patch('/profile/change-password', 'Admin\ProfileController@updatePassword');
	
	
	
	
	Route::get('generator', ['uses' => '\Appzcoder\LaravelAdmin\Controllers\ProcessController@getGenerator']);
	Route::post('generator', ['uses' => '\Appzcoder\LaravelAdmin\Controllers\ProcessController@postGenerator']);
});  



