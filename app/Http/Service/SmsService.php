<?php

namespace App\Service;

use App\Exceptions\ApiException;
use App\Models\App;
use App\Models\AppSmsConfig;
use App\Models\SmsConfig;
use Illuminate\Support\Facades\DB;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Sms\V20190711\Models\SendSmsRequest;
use TencentCloud\Sms\V20190711\SmsClient;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/14 0014
 * Time: 11:45
 */
class SmsService
{
    private $appId;
    private $secretId;
    private $secretKey;
    private $sign;
    private $templateId;

    public function __construct($appId,$secretId,$secretKey,$sign,$templateId)
    {
        $this->appId = $appId;
        $this->secretId = $secretId;
        $this->secretKey = $secretKey;
        $this->sign = $sign;
        $this->templateId = $templateId;
    }

    /**
     * 发送短信
     *
     * @param array $phones
     * @param array $message
     * @return array
     * @throws ApiException
     */
    public function send(array $phones,array $message)
    {
        try {
            $cred   = new Credential($this->secretId, $this->secretKey);
            $client = new SmsClient($cred, "ap-shanghai");
            $req    = new SendSmsRequest();

            $req->SmsSdkAppid   = $this->appId;
            $req->Sign          = $this->sign;
            $req->ExtendCode    = "0";
            /**
             * 下发手机号码，采用 e.164 标准，+[国家或地区码][手机号]
             * 例如+8613711112222， 其中前面有一个+号 ，86为国家码，13711112222为手机号，最多不要超过200个手机号
             */
            $req->PhoneNumberSet = $phones;
            $req->SenderId = "";
            $req->SessionContext = "";
            $req->TemplateID = $this->templateId;
            $req->TemplateParamSet = $message;

            $resp = $client->SendSms($req);
            $success = [];
            $fail = [];
            foreach ($resp->SendStatusSet as $item){
                if ($item->Code != "Ok"){
                    array_push($fail,["phone_number"=>$item->PhoneNumber,"message"=>$item->Message,"code"=>$item->Code]);
                }else{
                    array_push($success,["phone_number"=>$item->PhoneNumber,"message"=>$item->Message,"code"=>$item->Code]);
                }
            }
            return ["success"=>$success,"fail"=>$fail];
        }
        catch(TencentCloudSDKException $e) {
            throw new ApiException($e->getMessage(),500);
        }
    }

    public static function findSmsConfigByAppId($aid,$type)
    {
        $config = SmsConfig::query()
            ->where(SmsConfig::FIELD_TYPE,$type)
            ->whereExists(function ($query) use($aid) {
            $query->select(DB::raw(1))
                ->from(AppSmsConfig::TABLE_NAME)
                ->where(AppSmsConfig::FIELD_ID_APP,$aid)
                ->whereRaw("sms_configs.id=app_sms_configs.sms_config_id");
        })->first();
        return $config;
    }

    public static function findSmsConfigCacheByAppId($aid,$type)
    {
        $redis = new HasCacheService(AppSmsConfig::HAS_KEY);
        $key = "a{$aid}_t{$type}";
        $config = $redis->hasGet($key);
        if ($config){
            $config = json_decode($config);
        }else{
            $config = self::findSmsConfigByAppId($aid,$type);
            if($config) {
                $redis->hasSet($key, json_encode($config));
            }
        }
        return $config;
    }
}