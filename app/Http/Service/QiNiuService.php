<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/11/13
 * Time: 下午5:24
 */

namespace App\Http\Service;


use App\Exceptions\ApiException;
use App\Models\QiNiuTokenModel;
use Carbon\Carbon;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class QiNiuService
{
    protected $auth;
    protected $accessKey;
    protected $secretKey;
    protected $bucket;
    protected $baseUrl;

    const EXPIRED_AT = 3600 * 24;

    public function __construct()
    {
        $this->accessKey = env('QI_NIU_ACCESS_KEY');
        $this->secretKey = env('QI_NIU_SECRET_KEY');
        $this->bucket    = env('BUCKET_NAME');
        $this->baseUrl   = 'http://if-pri.qiniudn.com/qiniu.png?imageView2/1/h/500';

        $this->auth = new Auth($this->accessKey, $this->secretKey);
    }

    public function getToken()
    {
        $qiNiuToken = QiNiuTokenModel::query()->orderBy(QiNiuTokenModel::FIELD_CREATED_AT,'DESC')->first();
        if($qiNiuToken){
            if(!Carbon::parse($qiNiuToken->{QiNiuTokenModel::FIELD_EXPIRED_AT})->lt(Carbon::now())){
                $token = $qiNiuToken->{QiNiuTokenModel::FIELD_TOKEN};
            }else{
                $token = $this->uploadToken();
                if(!$token){
                    throw new ApiException("获取七牛token出错",500);
                }
                $qiNiuToken->{QiNiuTokenModel::FIELD_TOKEN} = $token;
                $qiNiuToken->{QiNiuTokenModel::FIELD_EXPIRED_AT} = Carbon::now()->addSecond(self::EXPIRED_AT);
                $updateResult = $qiNiuToken->save();
                if(!$updateResult){
                    throw new ApiException("更新七牛token失败",500);
                }
            }
        }else{
            $token = $this->uploadToken();
            if(!$token){
                throw new ApiException("获取七牛token出错",500);
            }
            $createResult = QiNiuTokenModel::create([
                QiNiuTokenModel::FIELD_TOKEN=>$token,
                QiNiuTokenModel::FIELD_EXPIRED_AT=>Carbon::now()->addSecond(self::EXPIRED_AT)
            ]);
            if(!$createResult){
                throw new ApiException("保存七牛token失败",500);
            }
        }

        return $token;
    }

    /**
     * 获取七牛上传凭证
     *
     * @author yezi
     *
     * @return string
     */
    private function uploadToken()
    {
        $expireSeconds = self::EXPIRED_AT;
        $policy['returnBody'] = '{"key":"$(key)","hash":"$(etag)","bucket":"$(bucket)","fsize":$(fsize),"width":"$(imageInfo.width)","height":"$(imageInfo.height)"}';

        $token = $this->auth->uploadToken($this->bucket,null,$expireSeconds,$policy);

        return $token;
    }

    public function downloadToken()
    {
        $token = $this->auth->privateDownloadUrl($this->baseUrl);

        return $token;
    }

    public function uploadImage($token,$filePath)
    {
        $uploadMgr = new UploadManager();
        list($ret, $err) = $uploadMgr->putFile($token, null, $filePath);
        if($err != ''){
            throw new ApiException("上传图片出错！",500);
        }
        return $ret;
    }

}