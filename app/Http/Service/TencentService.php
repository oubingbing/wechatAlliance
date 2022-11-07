<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/10 0010
 * Time: 16:04
 */

namespace App\Http\Service;

use App\Exceptions\ApiException;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Iai\V20200303\IaiClient;
use TencentCloud\Iai\V20200303\Models\CompareFaceRequest;

class TencentService
{
    public function compareFace($urla,$urlb)
    {
        try {
            // 实例化一个认证对象，入参需要传入腾讯云账户secretId，secretKey,此处还需注意密钥对的保密
            // 密钥可前往https://console.cloud.tencent.com/cam/capi网站进行获取
            $cred = new Credential(env("TENCENT_SECRET_ID"), env("TENCENT_SECRET_KEY"));
            // 实例化一个http选项，可选的，没有特殊需求可以跳过
            $httpProfile = new HttpProfile();
            $httpProfile->setEndpoint("iai.tencentcloudapi.com");
        
            // 实例化一个client选项，可选的，没有特殊需求可以跳过
            $clientProfile = new ClientProfile();
            $clientProfile->setHttpProfile($httpProfile);
            // 实例化要请求产品的client对象,clientProfile是可选的

            $client = new IaiClient($cred, env("TENCENT_REGION"), $clientProfile);
        
            // 实例化一个请求对象,每个接口都会对应一个request对象
            $req = new CompareFaceRequest();
            
            $params = array(
                "UrlA" => $urla,
                "UrlB" => $urlb
            );
            $req->fromJsonString(json_encode($params));
        
            // 返回的resp是一个CompareFaceResponse的实例，与请求对象对应
            $resp = $client->CompareFace($req);
            return json_decode($resp->toJsonString(),true);
        }
        catch(TencentCloudSDKException $e) {
            $message = $e->getMessage();
            throw new ApiException("情侣脸比对失败：{$message} 请稍后重试",500);
        }
    }

}