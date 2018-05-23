<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/11/13
 * Time: 下午5:24
 */

namespace App\Http\Service;


use Qiniu\Auth;

class QiNiuService
{
    protected $auth;
    protected $accessKey;
    protected $secretKey;
    protected $bucket;
    protected $baseUrl;

    public function __construct()
    {
        $this->accessKey = env('QI_NIU_ACCESS_KEY');
        $this->secretKey = env('QI_NIU_SECRET_KEY');
        $this->bucket    = env('BUCKET_NAME');
        $this->baseUrl   = 'http://if-pri.qiniudn.com/qiniu.png?imageView2/1/h/500';

        $this->auth = new Auth($this->accessKey, $this->secretKey);
    }

    /**
     * 获取七牛上传凭证
     *
     * @author yezi
     *
     * @return string
     */
    public function uploadToken()
    {
        $token = $this->auth->uploadToken($this->bucket);

        return $token;
    }

    public function downloadToken()
    {
        $token = $this->auth->privateDownloadUrl($this->baseUrl);

        return $token;
    }

}