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

Route::get('/', function () {
    return [
        'app' => 'Laravel',
        'version' => '1.0.0',
    ];
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/me', function (Request $request) {
        return $request->user();
    });
});

Route::group(['middleware' => 'guest:api'], function () {
    Route::post('auth/login', 'Auth\LoginController@login');
    Route::post('auth/register', 'Auth\RegisterController@register');
});
