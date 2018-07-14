<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/10 0010
 * Time: 15:05
 */

namespace App\Http\Service;


use App\Exceptions\ApiException;
use GuzzleHttp\Client;

class WeChatRequestService
{
    const BASE_URL = 'https://api.weixin.qq.com/sns/jscode2session';

    private $url;
    private $appid;
    private $secretKey;

    /**
     * 构造函数
     * @param $sessionKey string 用户在小程序登录后获取的会话密钥
     * @param $appid string 小程序的appid
     */
    public function __construct($appid,$secretKey,$code)
    {
        $this->appid = $appid;
        $this->secretKey = $secretKey;
        $this->url = self::BASE_URL."?appid={$this->appid}&secret={$this->secretKey}&js_code={$code}&grant_type=authorization_code";
    }

    public function getWeChatData()
    {
        $http = new Client;
        $response = $http->get($this->url);
        $result = json_decode((string) $response->getBody(), true);
        if(!isset($result['session_key'])){
            throw new ApiException('获取数据失败！',500);
        }

        return $result;
    }

    public function getSessionKey()
    {
        $result = $this->getWeChatData();
        return $result['session_key'];
    }

    /**
     * 解密数据
     *
     * @author 叶子
     *
     * @param $encryptedData
     * @param $iv
     * @param $sessionKey
     * @return string
     * @throws ApiException
     */
    public function decryptData( $encryptedData, $iv,$sessionKey)
    {
        if (strlen($sessionKey) != 24) {
            throw new ApiException('session key error',500);
        }
        $aesKey=base64_decode($sessionKey);

        if (strlen($iv) != 24) {
            throw new ApiException('iv error',500);
        }
        $aesIV=base64_decode($iv);

        $aesCipher=base64_decode($encryptedData);

        $result=openssl_decrypt( $aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);

        $dataObj=json_decode( $result );
        if( $dataObj  == NULL )
        {
            throw new ApiException('解密后得到的buffer非法',500);
        }

        if( $dataObj->watermark->appid != $this->appid )
        {
            throw new ApiException('解密后得到的buffer非法',500);
        }

        return $result;
    }

    public function getWeRunData($encryptedData,$iv)
    {
        $sessionKey = $this->getSessionKey();
        $runData = $this->decryptData( $encryptedData, $iv,$sessionKey);
        return $runData;
    }
}