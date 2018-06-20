<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/20 0020
 * Time: 15:11
 */

namespace App\Http\Service;


use GuzzleHttp\Client;

class WeChatMessageService
{
    private $client;
    private $baseUrl;
    private $token;

    public function __construct($appId)
    {
        $this->client = new Client;
        $this->baseUrl = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template';
        $this->token = app(TokenService::class)->getAccessToken($appId);
    }

    public function getTemplateId($token,$title)
    {
        
    }

    public function send()
    {
        $url = $this->baseUrl.'/send?access_token='.$this->token;
        $data = [
            'touser'=>'oZr1r5dBCP1K8Iv_sSVB7q5cNIaw',
            'template_id'=>'skhGHKBAyAW8o9WrC43wa2D63yaqYcOAHJb2N1s4rG0',
            'data'=>[
                "keyword1"=>[
                    "value"=>"代课"
                ],
                "keyword2"=>[
                    "value"=>"叶子"
                ],
                "keyword3"=>[
                    "value"=>"您的接受了该任务！"
                ],
            ]
        ];

        $response = $this->client->post($url,['json'=>$data]);

        $result = json_decode((string) $response->getBody(), true);

        return $result;
    }

}