<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/12/14
 * Time: 上午11:55
 */

namespace App\Http\Service;


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

        /*array:6 [
                "confidence" => 45.945251464844
          "thresholds" => array:3 [
                0 => 61.0
            1 => 69.0
            2 => 75.0
          ]
          "rectA" => array:4 [
                0 => 115
            1 => 222
            2 => 382
            3 => 550
          ]
          "rectB" => array:4 [
                0 => 213
            1 => 540
            2 => 484
            3 => 618  //如图片中没有人脸，返回矩形框数值均为0
          ]
          "errno" => 0 //返回正常
          "request_id" => "406081ae-3a79-4562-a4db-6e7e0fc3b465"
        ]*/

        $akId     = env('ALI_ID');
        $akSecret = env('ALI_SECRET');
        $url      = env('ALI_URL');

        $content = [
            "type"        => $imageType,
            "image_url_1" => $image1,
            "image_url_2" => $image2
        ];

        $options                    = array(
            'http'                  => array(
                'header'            => array(
                    'accept'        => "application/json",
                    'content-type'  => "application/json",
                    'date'          => gmdate("D, d M Y H:i:s \G\M\T"),
                    'authorization' => ''
                ),
                'method'            => "POST",
                'content'           => json_encode($content) //如有数据，请用json_encode()进行编码
            )
        );
        $http   = $options['http'];
        $header = $http['header'];
        $urlObj = parse_url($url);
        if(empty($urlObj["query"]))
            $path = $urlObj["path"];
        else
            $path = $urlObj["path"]."?".$urlObj["query"];
        $body     = $http['content'];
        if(empty($body))
            $bodymd5 = $body;
        else
            $bodymd5  = base64_encode(md5($body,true));
        $stringToSign = $http['method']."\n".$header['accept']."\n".$bodymd5."\n".$header['content-type']."\n".$header['date']."\n".$path;
        $signature    = base64_encode(
            hash_hmac(
                "sha1",
                $stringToSign,
                $akSecret, true));
        $authHeader = "Dataplus "."$akId".":"."$signature";
        $options['http']['header']['authorization'] = $authHeader;
        $options['http']['header'] = implode(
            array_map(
                function($key, $val){
                    return $key.":".$val."\r\n";
                },
                array_keys($options['http']['header']),
                $options['http']['header']));
        $context = stream_context_create($options);
        $file = file_get_contents($url, false, $context );

        return json_decode($file,true);
    }

}