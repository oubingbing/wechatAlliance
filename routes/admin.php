<?php

Route::group(['prefix' => 'admin','namespace' => 'Admin','middleware'=>['guest','web','auth','authUser']], function () {
    Route::get('/','IndexController@index');
});

Route::get('/active','Auth\RegisterController@active');
Route::get('/logout','Auth\LoginController@logout')->middleware(['guest','web']);
