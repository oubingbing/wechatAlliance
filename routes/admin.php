<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin','namespace' => 'Admin','middleware'=>['guest','web','auth']], function () {

    Route::group(['middleware'=>['authUser']], function () {
        /** 后台首页 */
        Route::get('/','IndexController@index');
        /** 后台主页 */
        Route::get('/dashboard','IndexController@dashboard');
        /** 获取小程序信息 */
        Route::get('/app','AppController@appInfo');
        /** 用户列表 */
        Route::get('wechat_users','UserController@allUsers');
        /** 用户统计 */
        Route::get('user_statistics','UserController@userStatistics');
    });

    Route::group(['middleware'=>['createApp']], function () {
        /** 新建小程序视图 */
        Route::get('/create_app','AppController@createApp');
        /** 新建小程序 */
        Route::post('/create_app','AppController@store');
    });

});

/** 激活账号 */
Route::get('/active','Auth\RegisterController@active');
/** 退出登录 */
Route::get('/logout','Auth\LoginController@logout')->middleware(['guest','web']);

/** 获取学校资料 */
Route::get('colleges','Controller@colleges');
