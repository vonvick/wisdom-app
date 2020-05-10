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

    Route::group(['namespace'  => 'User'], static function () {
        Route::get('news', 'NewsController@index');
        Route::group(['middleware' => 'jwt.verify'], static function () {
            Route::post('logout', 'AuthController@logout');
        });
    });

    Route::group(['namespace' => 'Admin', 'middleware' => 'jwt.verify'], static function() {
        Route::post('register', 'UserController@register');
    });
});
