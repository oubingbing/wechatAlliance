<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/19 0019
 * Time: 17:33
 */

namespace App\Http\Service;


use App\Exceptions\ApiException;
use App\Models\WechatApp;
use GuzzleHttp\Client;

class WeChatService
{
    protected $weChatLoginUrl = "https://api.weixin.qq.com/sns/jscode2session";
    private $appKey    = '';
    private $secretKey = '';

    public function getSessionInfo($weChatApp,$code,$iv,$encryptedData){
        if($weChatApp->{WechatApp::FIELD_STATUS} === WechatApp::ENUM_STATUS_TO_BE_AUDIT){
            throw new ApiException('小程序处于审核中，无法使用后台服务！',6001);
        }

        $this->appKey    = $weChatApp->{WechatApp::FIELD_APP_KEY};
        $this->secretKey = $weChatApp->{WechatApp::FIELD_APP_SECRET};
        $url             = $this->weChatLoginUrl."?appid={$this->appKey}&secret={$this->secretKey}&js_code=$code&grant_type=authorization_code";
        $http            = new Client;
        $response        = $http->get($url);
        $result          = json_decode((string) $response->getBody(), true);
        if(!isset($result['openid'])){
            throw new ApiException('小程序登录失败，请检查您的app_id和app_secret是否正确！',5000);
        }

        $sessionKey = $result["session_key"];
        $userInfo = $this->decryptData($encryptedData,$iv,$sessionKey);
        $userInfo = json_decode($userInfo,true);
        $userInfo["app_id"] = $weChatApp->id;

        return $userInfo;
    }

    /**
     * 检验数据的真实性，并且获取解密后的明文.
     * @param $encryptedData string 加密的用户数据
     * @param $iv string 与用户数据一同返回的初始向量
     * @param $data string 解密后的原文
     *
     * @return int 成功0，失败返回对应的错误码
     */
    public function decryptData($encryptedData, $iv, $sessionKey)
    {
        if (strlen($sessionKey) != 24) {
            throw new ApiException("session_key error",500);
        }
        $aesKey=base64_decode($sessionKey);


        if (strlen($iv) != 24) {
            throw new ApiException("iv error",500);
        }
        $aesIV=base64_decode($iv);

        $aesCipher=base64_decode($encryptedData);

        $result=openssl_decrypt( $aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);

        $dataObj=json_decode( $result );
        if( $dataObj  == NULL )
        {
            throw new ApiException("解密失败",5000);
        }
        if( $dataObj->watermark->appid != $this->appKey )
        {
            throw new ApiException("解密失败",5000);
        }
        $data = $result;
        return $data;
    }
}