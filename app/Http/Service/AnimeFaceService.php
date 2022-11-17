<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/5 0005
 * Time: 9:56
 */

namespace App\Http\Service;

use App\Exceptions\ApiException;
use App\Http\Service\Baidu\AipImageProcess;

class AnimeFaceService
{
    public function animeFace($url)
    {
        $appId = env("BAIDU_APP_ID");
        $apiKey = env("BAIDU_API_ID");
        $appSercret = env("BAIDU_APP_SECRET");

        $client = new AipImageProcess($appId, $apiKey, $appSercret);
        $options = array("type"=>"anime", "mask_id"=>3);
        $image = file_get_contents($url);
        $result = $client->selfieAnime($image, $options);
        if($result["error_code"] != 0){
            \Log::error('漫画脸请求失败：'.$result["error_msg"]);            
            throw new ApiException("转化失败，请稍后重试！",500);
        }

        return $result["image"];
    }

}