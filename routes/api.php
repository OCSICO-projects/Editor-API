<?php

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

Auth::routes(['verify' => true]);

Route::get('/', function (){
    return \App\Http\Services\AppVersionService::getVersion();
});

Route::get('ping', function (){
    return 'pong';
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');

    Route::group(['prefix' => 'phone'], function () {
        Route::post('verify', 'VerificationController@phoneVerification');
        Route::post('resend-code', 'VerificationController@resendPhoneVerificationCode');
    });

    Route::group(['prefix' => 'facebook'], function () {
        Route::get('/', 'SocialiteController@redirectToFacebook');
        Route::post('/callback', 'SocialiteController@handleFacebookCallback');
    });

    Route::group(['prefix' => 'google'], function () {
        Route::get('/', 'SocialiteController@redirectToGoogle');
        Route::post('/callback', 'SocialiteController@handleGoogleCallback');
    });
});

Route::get('/files/data/{file}', 'FileController@data');

Route::middleware(['auth:api'])->group(function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::get('logout', 'AuthController@logout');
        Route::get('me', 'AuthController@user');
    });

    Route::group(['prefix' => 'folders'], function () {
        Route::get('/', 'FolderController@index');
        Route::post('/', 'FolderController@store');
        Route::get('/{folder}', 'FolderController@show');
        Route::get('/{folder}/resources', 'FolderController@showResources');
        Route::put('/{folder}', 'FolderController@update');
        Route::delete('/{id}', 'FolderController@destroy');
    });

    Route::group(['prefix' => 'files'], function () {
        Route::get('/', 'FileController@index');
        Route::get('/{file}', 'FileController@show');
    });

    Route::group(['prefix' => 'resources'], function () {
        Route::get('/', 'ResourceController@index');
        Route::get('/{resource}', 'ResourceController@show');
        Route::post('/', 'ResourceController@store');
        Route::post('/files', 'ResourceController@storeFromFile');
        Route::put('/{resource}', 'ResourceController@update');
        Route::delete('/{id}', 'ResourceController@destroy');
    });
});
