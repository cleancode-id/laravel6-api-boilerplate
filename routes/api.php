<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\User as UserResource;

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
        'app' => config('app.name'),
        'version' => '1.0.0',
    ];
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('auth/me', function (Request $request) {
        return new UserResource($request->user());
    });
});

Route::group(['middleware' => 'guest:api'], function () {
    Route::post('auth/login', 'Auth\LoginController@login');
    Route::post('auth/register', 'Auth\RegisterController@register');
});
