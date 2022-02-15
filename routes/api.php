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
	//模拟代理
    Route::post('login_user_dome', 'WanchorUserController@login_user_dome')->name('login_user_dome');
    //设置用户语言
    Route::post('save_lang_iso', 'WanchorUserController@save_lang_iso')->name('save_lang_iso');
    //获取语言列表
    Route::post('gain_language', 'CurrencyController@gain_language')->name('gain_language');
    //获取页面语言
    Route::post('gain_home_language', 'CurrencyController@gain_home_language')->name('gain_home_language');
    //获取视频主目录
    Route::post('gain_wcourse', 'WcourseController@gain_wcourse')->name('gain_wcourse');
    //获取课程子表
    Route::post('gain_wcourse_low', 'WcourseController@gain_wcourse_low')->name('gain_wcourse_low');
});
