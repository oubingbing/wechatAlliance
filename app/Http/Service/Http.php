<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/12/14
 * Time: 上午11:55
 */

namespace App\Http\Service;


use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use AlibabaCloud\SDK\ViapiUtils\ViapiUtils;
use AlibabaCloud\Tea\Exception\TeaUnableRetryError;
use AlibabaCloud\SDK\OSS\OSS;
use AlibabaCloud\SDK\OSS\OSS\PutObjectRequest;
use AlibabaCloud\SDK\ViapiTool\ViapiTool;
use AlibabaCloud\SDK\Viapiutils\V20200401\Models\GetOssStsTokenRequest;
use AlibabaCloud\SDK\Viapiutils\V20200401\Viapiutils as AlibabaCloudSDKViapiutilsV20200401Viapiutils;
use AlibabaCloud\Tea\Exception\TeaError;
use AlibabaCloud\Tea\OSSUtils\OSSUtils\RuntimeOptions;
use AlibabaCloud\Tea\Rpc\Rpc\Config;
use AlibabaCloud\Tea\Utils\Utils;
use App\Exceptions\ApiException;

class Http
{
    /**
     * post请求
     *
     * @author yezi
     *
     * @param $url
     * @param $option
     * @param array $header
     * @return array
     */
    public function post($url, $option, $header = [])
    {
        return $this->request($type = 'POST', $url, $option, $header);
    }

    /**
     * get请求
     *
     * @param $url
     * @param $option
     * @param array $header
     * @return array
     */
    public function get($url, $option, $header = [])
    {
        return $this->request($type = 'GET', $url, $option, $header);
    }

    /**
     * 发起请求
     *
     * @author yezi
     *
     * @param string $type
     * @param $url
     * @param $option
     * @param array $header
     * @param int $setopt
     * @return array
     */
    public function request($type = 'POST', $url, $option, $header = [], $setopt = 10)
    {
        $curl = curl_init(); // 启动一个CURL会话
        if (!empty ($option)) {
            if ($type == "GET") {
                $url .= '?' . http_build_query($option);
            } else {
                $options = json_encode($option);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $options); // Post提交的数据包
            }
        }
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0)'); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_TIMEOUT, $setopt); // 设置超时限制防止死循环
        if (empty($header)) {
            $header = [];
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header); // 设置HTTP头
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $type);
        $result = curl_exec($curl); // 执行
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl); // 关闭CURL会话

        return ['status_code' => $status, 'result' => json_decode($result, true)];
    }

    /**
     * 调用阿里云人脸对比功能
     *
     * 1% - 10% 哈哈你们可以愉快的拍拖
     *
     * 10% - 50 夫妻情人
     *
     * 50% - 70% 家人
     *
     * 70% 以上的是本人
     *
     * @author yezi
     *
     * @param $image1
     * @param $image2
     * @param int $imageType
     * @return mixed
     */
    public function compareFace($image1,$image2,$imageType=0)
    {
        $akId     = env('ALI_ID');
        $akSecret = env('ALI_SECRET');

        $img1 = self::upload($akId, $akSecret, $image1);
        $img2 = ViapiUtils::upload($akId, $akSecret, $image2);

        AlibabaCloud::accessKeyClient($akId, $akSecret)
            ->regionId('cn-shanghai')
            ->asDefaultClient();

        try {
            $result = AlibabaCloud::rpc()
                ->product('facebody')
                // ->scheme('https') // https | http
                ->version('2019-12-30')
                ->action('CompareFace')
                ->method('POST')
                ->host('facebody.cn-shanghai.aliyuncs.com')
                ->options([
                    'query' => [
                        'RegionId' => "cn-shanghai",
                        'ImageURLA' => $img1,
                        'ImageURLB' => $img2,
                    ],
                ])
                ->request();
        } catch (ClientException $e) {
            throw new ApiException("请上传含人脸的正常图片");
        } catch (ServerException $e) {
            throw new ApiException("请上传含人脸的正常图片");
        }

        return $result->toArray()["Data"];
    }

    /**
     * @param string $accessKeyId
     * @param string $accessKeySecret
     * @param string $filePath
     *
     * @throws \Exception
     *
     * @return string
     */
    public static function upload($accessKeyId, $accessKeySecret, $filePath)
    {
        $ins = null;

        try {
            $viConfig = new Config([
                'accessKeyId'     => $accessKeyId,
                'accessKeySecret' => $accessKeySecret,
                'type'            => 'access_key',
                'endpoint'        => env("ALI_ENDPOINT"),
                'regionId'        => env("ALI_ENDPOINT_ID"),
            ]);
            $viclient   = new AlibabaCloudSDKViapiutilsV20200401Viapiutils($viConfig);
            $viRequest  = new GetOssStsTokenRequest([]);
            $viResponse = $viclient->getOssStsToken($viRequest);
            if (Utils::isUnset($viResponse) || Utils::isUnset($viResponse->data)) {
                throw new TeaError([
                    'code'    => 'InvalidResponse',
                    'message' => 'GetOssStsToken gets a invalid response',
                    'data'    => $viResponse,
                ]);
            }
            $fileName = '';
            if (ViapiTool::startsWith($filePath, 'https://') || ViapiTool::startsWith($filePath, 'http://')) {
                $filePath = ViapiTool::decode($filePath, 'UTF-8');
                $fileName = ViapiTool::match($filePath, '\\w+.(jpg|gif|png|jpeg|bmp|mov|mp4|avi)');
                if (Utils::empty_($fileName)) {
                    $fileName = ViapiTool::getNameFromUrl($filePath);
                    $fileName = ViapiTool::subStringAfterLast($fileName, '/');
                }
                $ins = ViapiTool::getStreamFromNet($filePath);
            } else {
                $ins      = ViapiTool::getStreamFromPath($filePath);
                $fileName = ViapiTool::getNameFromPath($filePath);
            }
            $ossConfig = new \AlibabaCloud\SDK\OSS\OSS\Config([
                'accessKeyId'     => $viResponse->data->accessKeyId,
                'accessKeySecret' => $viResponse->data->accessKeySecret,
                'securityToken'   => $viResponse->data->securityToken,
                'type'            => 'sts',
                'endpoint'        => 'oss-cn-shanghai.aliyuncs.com',
                'regionId'        => 'cn-shanghai',
            ]);
            $ossClient     = new OSS($ossConfig);
            $objectName    = '' . $accessKeyId . '/' . Utils::getNonce() . '' . $fileName . '';
            $uploadRequest = new PutObjectRequest([
                'bucketName' => 'viapi-customer-temp',
                'body'       => $ins,
                'objectName' => $objectName
            ]);
            $uploadRequest->header = new PutObjectRequest\header();
            $ossRuntime = new RuntimeOptions([]);
            $ossClient->putObject($uploadRequest, $ossRuntime);

            return 'http://viapi-customer-temp.oss-cn-shanghai.aliyuncs.com/' . $objectName . '';
        } finally {
            if (!Utils::isUnset($ins)) {
                ViapiTool::close($ins);
            }
        }
    }

}