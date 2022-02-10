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
Route::group([
    // 'prefix'        => 'api',
    'namespace'     => 'App\Tiktok\Controllers',
    'middleware'    => ['cors'],
    'as'            => 'tiktok' . '.',
], function () {
    Route::post('login_user_dome', 'WanchorUserController@login_user_dome')->name('login_user_dome');
});
