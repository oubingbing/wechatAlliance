<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/14 0014
 * Time: 10:07
 */

namespace App\Http\Service;


use App\Models\SendMessage;
use Carbon\Carbon;

class YunPianService
{
    protected $singleUrl;
    protected $multiUrl;
    protected $apikey;
    protected $httpClient;

    public function __construct()
    {
        $this->singleUrl = env('YUN_PIAN_SINGLE_URL');
        $this->multiUrl = env('YUN_PIAN_MULTI');
        $this->apikey = env('YUN_PIAN_KEY');
    }

    /**
     * 发送短信验证码
     *
     * @author yezi
     *
     * @param $phone
     * @return mixed
     */
    public function sendMessageCode($phone)
    {
        $code = random_int(10000,100000);
        $content = "【情书网】Hi，同学，您的验证码是".$code."。如非本人操作，请忽略本短信";

        $result = $this->sendMessage($content,$phone);
        if($result['code'] == 0){
            $status = SendMessage::ENUM_STATUS_SUCCESS;
        }else{
            $status = SendMessage::ENUM_STATUS_FAIL;
        }

        $type = SendMessage::ENUM_TYPE_MESSAGE_CODE;
        $sessionId = 0;
        $expire = Carbon::now()->addSecond(90);
        app(SendMessageService::class)->saveSendMessageLog($phone,$code,$status,$type,$sessionId,$expire);

        return $result;
    }

    public function sendMessage($content,$mobile)
    {
        $result = app(YunPianService::class)->sendSingle($mobile,$content);

        return $result;
    }

    /**
     * 发送单条短信
     * @author yezi
     * @param $mobile
     * @param $content
     * @return array
     */
    public function sendSingle($mobile,$content)
    {
        if (empty($mobile))
            return ['success'=>false, 'statusCode'=>500, 'responseData'=>['msg'=>'手机号码不能为空']];
        if (empty($content))
            return ['success'=>false, 'statusCode'=>500, 'responseData'=>['msg'=>'内容不能为空']];

        $data = ['mobile'=>$mobile,'text'=>$content,'apikey'=>$this->apikey];

        $result = $this->send($this->singleUrl, $data);

        return $result;
    }

    /**
     * 执行发送消息
     *
     * @author yezi
     *
     * @param $url
     * @param $postData
     * @return mixed|string
     */
    public function send($url,$postData){
        $ch = curl_init();
        /* 设置验证方式 */
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept:text/plain;charset=utf-8',
            'Content-Type:application/x-www-form-urlencoded',
            'charset=utf-8'
        ));
        /* 设置返回结果为流 */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        /* 设置超时时间*/
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        /* 设置通信方式 */
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        $retry=0;
        // 若执行失败则重试
        do{
            $output = curl_exec($ch);
            $retry++;
        }while((curl_errno($ch) !== 0) && $retry<3);
        if (curl_errno($ch) !== 0) {
            curl_close($ch);
            return curl_error($ch);
        }
        $output = trim($output, "\xEF\xBB\xBF");

        curl_close($ch);
        return json_decode($output,true);
    }

}