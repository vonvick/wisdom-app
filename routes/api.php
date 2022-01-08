<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group([ 'middleware' => 'api', 'namespace' => 'Api'], static function () {
    Route::post('login', 'AuthController@login');
    Route::group(['middleware' => 'jwt.verify'], static function() {
        Route::post('logout', 'AuthController@logout');
    });


     Route::group(['namespace'  => 'User'], static function () {
         Route::get('users', 'UserController@index');
         Route::get('user/{id}', 'UserController@show');
//         Route::get('news', 'NewsController@index');
         Route::group(['middleware' => 'jwt.verify'], static function () {
             Route::patch('user/{id}', 'UserController@update');
         });
     });

    Route::group(['namespace' => 'Admin', 'middleware' => 'jwt.verify'], static function() {
        Route::post('register', 'UserController@register');
        Route::delete('user/{id}', 'UserController@deactivate');
        Route::get('roles', 'RoleController@all');


        Route::get('permissions', 'PermissionController@all');
        Route::post('permissions/create', 'PermissionController@create');
        Route::patch('permission/assign', 'PermissionController@assign');
        Route::patch('permission/unassign', 'PermissionController@unassign');
    });
});
