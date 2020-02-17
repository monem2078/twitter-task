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

Route::post('authenticate', 'Auth\AuthenticateController@authenticate');
Route::post('authenticate/register', 'Auth\AuthenticateController@register');

Route::group(['middleware' => ['jwt.customAuth']],
    function () {
        Route::resource('tweets', 'TweetController')->only(['store', 'destroy']);
        Route::post('follow/{user}', 'UserController@follow');
        Route::get('timeline', 'UserController@timeLine');
    });
