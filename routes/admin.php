<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin','namespace' => 'Admin','middleware'=>['web','auth']], function () {

    Route::group(['middleware'=>['authUser']], function () {
        /** 后台首页 */
        Route::get('/','IndexController@index');
        /** 后台主页 */
        Route::get('/dashboard','IndexController@dashboard');
        /** 获取小程序信息 */
        Route::get('/app','AppController@appInfo');
        /** 用户列表 */
        Route::get('wechat_users','UserController@userList');
        /** 用户统计 */
        Route::get('user_statistics','UserController@userStatistics');
        /** 切换到微信审核模式 */
        Route::patch('open_audit','AppController@weChatAudit');
        /** 恢复正常模式 */
        Route::patch('close_audit','AppController@online');
        /** 用户列表 */
        Route::get('user/index','UserController@index');
        /** 设置客服 */
        Route::post('/set_service','AppController@serService');
        /** 设置超管 */
        Route::post('/set_supervise','AppController@setSupervise');
        /** 设置超管 */
        Route::post('/remove_service','AppController@removeService');
        /** 后台表白墙视图 */
        Route::get('/post/index','PostController@index');
        /** 后台表白墙帖子列表 */
        Route::get('/post/list','PostController@postList');
        /** 删除表白墙评论 */
        Route::delete('/delete/{id}/comment','PostController@delete');
        /** 话题主页 */
        Route::get('/topic','TopicController@index');
        /** 新建话题 */
        Route::get('/topic/create','TopicController@createView');
        /** 获取七牛token */
        Route::get('/upload_token','IndexController@getUploadToken');
        /** 新建话题 */
        Route::post('/topic/create','TopicController@store');
        /** 话题列表 */
        Route::get('/topic/list','TopicController@topicList');
        /** 上架话题 */
        Route::patch('/topic/{id}/up','TopicController@upTopic');
        /** 下架话题 */
        Route::patch('/topic/{id}/down','TopicController@downTopic');
        /** 修改小程序序二维码 */
        Route::patch('/update_qr_code','AppController@updateImage');
        /** 修改名称，ID，secret的视图 */
        Route::post('/update_app_info','AppController@updateApp');
        /** 微信模板列表 */
        Route::get('/templates_index','AppController@templateView');
        /** 微信模板列表 */
        Route::get('/templates','AppController@template');
        /** 微信模板列表 */
        Route::post('/templates','AppController@createTemplate');
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

/** 部署教程 */
Route::get('deploy','Admin\AppController@deployStep');
