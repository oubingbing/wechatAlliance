<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/12/14
 * Time: 上午11:55
 */

namespace App\Http\Logic;


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

}