<?php


Route::get('/login','Admin\IndexController@login');

Route::group(['prefix' => 'admin','namespace' => 'Admin'], function () {
    Route::get('/','IndexController@index');
});