<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin','namespace' => 'Admin','middleware'=>['guest','web','auth']], function () {

    Route::group(['middleware'=>['authUser']], function () {
        /** 后台首页 */
        Route::get('/','IndexController@index');

        /** 后台主页 */
        Route::get('/dashboard','IndexController@dashboard');
    });

    Route::group(['middleware'=>['createApp']], function () {
        /** 新建小程序视图 */
        Route::get('/create_app','AppController@createApp');

        /** 新建小程序 */
        Route::post('/create_app','AppController@store');
    });

});

Route::get('/active','Auth\RegisterController@active');
Route::get('/logout','Auth\LoginController@logout')->middleware(['guest','web']);
