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
        Route::post('auth/logout', 'AuthController@logout');

        Route::post('user/register', 'UserController@register');
        Route::patch('user/{id}', 'UserController@update');
        Route::delete('user/{id}', 'UserController@deactivate');
        Route::get('users', 'UserController@index');
        Route::get('user/{id}', 'UserController@show');


        Route::get('roles', 'RoleController@all');

        Route::get('permissions', 'PermissionController@all');
        Route::post('permissions/create', 'PermissionController@create');
        Route::patch('permission/assign', 'PermissionController@assign');
        Route::patch('permission/unassign', 'PermissionController@unassign');

        Route::post('news', 'PostController@create');
        Route::patch('news/{id}', 'PostController@update');
        Route::delete('posts/{id}', 'PostController@destroy');
        Route::get('news', 'PostController@index');
        Route::get('news/{id}', 'PostController@show');

        Route::post('media/upload', 'FileUploadController@handleMediaUpload');
        Route::patch('media/{id}', 'FileUploadController@update');
        Route::delete('media/{id}', 'FileUploadController@delete');

        Route::get('/galleries', 'GalleryController@index');
        Route::get('/gallery/{id}', 'GalleryController@show');
        Route::post('/gallery', 'GalleryController@create');
        Route::patch('/gallery/{id}', 'GalleryController@update');
        Route::delete('/gallery/{id}', 'GalleryController@destroy');
    });
});
