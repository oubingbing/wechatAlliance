<?php

use App\Events\Chat;
use Illuminate\Support\Facades\Redis;


Route::get('/', function () {
    return view('gateway.home');
});
Route::get('/contact',function (){
    return view('gateway.contact');
});
Route::get('/about',function (){
    return view('gateway.about');
});

Route::get('/hui_yi_happy_birthday', function (){
    return view('birthday.flower');
});

Route::get('/hui_yi_song_ni_de_dang_gao', function (){
    return view('birthday.cake');
});

/** 测试 */
//App\Http\Controllers\App\Http\IM\IndexController
Route::get('test_socket','IM\IndexController@chatRoom');
Route::get('socket','IM\IndexController@socket');
Route::get('bind','IM\IndexController@bindSocket');
Route::post('send','IM\IndexController@sendSocket');


