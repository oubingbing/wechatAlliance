<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/20 0020
 * Time: 15:11
 */

namespace App\Http\Service;


use App\Exceptions\ApiException;
use App\Models\TemplateKeyWord;
use App\Models\TemplateLog;
use App\Models\WeChatTemplate;
use GuzzleHttp\Client;

class WeChatMessageService
{
    private $client;
    private $baseUrl;
    private $token;
    private $appId;

    public function __construct($appId)
    {
        $this->client = new Client;
        $this->baseUrl = 'https://api.weixin.qq.com/cgi-bin/wxopen/template';
        $this->token = app(TokenService::class)->getAccessToken($appId);
        $this->appId = $appId;
    }

    /**
     * 初始化微信消息模板
     *
     * @author yezi
     *
     * @throws \Exception
     */
    public function initTemplate()
    {
        try{
            \DB::beginTransaction();

            $this->batchDeleteTemplate();
            $this->batchAddTemplate();

            \DB::commit();
        }catch (\Exception $e){
            \DB::rollBack();
            throw $e;
        }
    }

    /**
     * 初始化小程序的消息模板,给小程序添加微信消息模板
     *
     * @author yezi
     *
     * @return mixed
     * @throws \Exception
     */
    public function batchAddTemplate()
    {
        $template = [];
        $keys = TemplateKeyWord::query()->get();
        foreach ($keys as $item){
            $result = $this->addTemplate($item->{TemplateKeyWord::FIELD_KEY_WORD},$item->{TemplateKeyWord::FIELD_KEY_WORD_IDS});
            if($result['errcode'] == 0){
                array_push($template,[
                    WeChatTemplate::FIELD_ID_APP=>$this->appId,
                    WeChatTemplate::FIELD_ID_TEMPLATE=>$result['template_id'],
                    WeChatTemplate::FIELD_TITLE=>$item->{TemplateKeyWord::FIELD_TITLE},
                    WeChatTemplate::FIELD_CONTENT=>$item->{TemplateKeyWord::FIELD_CONTENT},
                    WeChatTemplate::FIELD_KEY_WORD_IDS=>json_encode($item->{TemplateKeyWord::FIELD_KEY_WORD_IDS})
                ]);
            }else{
                throw new \Exception('初始化错误！',500);
            }
        }

        if(!empty($template)){
            $result = WeChatTemplate::insert($template);
            if(!$result){
                throw new \Exception('初始化失败！',500);
            }
        }

        return $result;
    }

    /**
     * 获取模板标题下关键词库
     *
     * @author yezi
     *
     * @param $key
     * @return mixed
     */
    public function getKeyWorld($key)
    {
        $url = $this->baseUrl.'/library/get?access_token='.$this->token;
        $data = ['id'=>$key];

        $response = $this->client->post($url,['json'=>$data]);

        $result = json_decode((string) $response->getBody(), true);

        return $result;
    }

    /**
     * 添加模板消息
     *
     * @author yezi
     *
     * @param $titleId
     * @param $keywordIds
     * @return mixed
     * @throws \Exception
     */
    public function addTemplate($titleId,$keywordIds)
    {
        $url = $this->baseUrl.'/add?access_token='.$this->token;
        $data = ['id'=>$titleId,'keyword_id_list'=>$keywordIds];
        $response = $this->client->post($url,['json'=>$data]);

        $result = json_decode((string) $response->getBody(), true);
        if($result['errcode'] != 0){
            throw new \Exception('添加模板失败！',500);
        }
        return $result;
    }

    /**
     * 删除小程序的所有模板
     *
     * @author yezi
     */
    public function batchDeleteTemplate()
    {
        $templates = WeChatTemplate::query()->where(WeChatTemplate::FIELD_ID_APP,$this->appId)->get();
        if($templates){
            foreach ($templates as $template){
                $this->deleteTemplate($template[WeChatTemplate::FIELD_ID_TEMPLATE]);
            }
            WeChatTemplate::query()->where(WeChatTemplate::FIELD_ID_APP,$this->appId)->delete();
        }
    }

    /**
     * 删除单个小程序模板
     *
     * @author yezi
     *
     * @param $templateId
     * @return mixed
     */
    public function deleteTemplate($templateId)
    {
        $url = $this->baseUrl.'/del?access_token='.$this->token;
        $data = ['template_id'=>$templateId];
        $response = $this->client->post($url,['json'=>$data]);

        $result = json_decode((string) $response->getBody(), true);
        return $result;
    }

    /**
     * 发送微信模板消息
     *
     * @author yezi
     *
     * @param $openId
     * @param $title
     * @param $values
     * @param $fromId
     * @param string $page
     * @return mixed
     * @throws ApiException
     */
    public function send($openId,$title,$values,$fromId,$page='pages/index/index')
    {
        $template = WeChatTemplate::query()
            ->where(WeChatTemplate::FIELD_ID_APP,$this->appId)
            ->where(WeChatTemplate::FIELD_TITLE,$title)
            ->first();
        if(!$template){
            //没有模板就不发微信消息
            return false;
        }

        $templateId = $template->{WeChatTemplate::FIELD_ID_TEMPLATE};

        $content = [];
        foreach ($template->{WeChatTemplate::FIELD_KEY_WORD_IDS} as $key => $item){
            $keyword = $key + 1;
            $content["keyword$keyword"] = ['value'=>$values[$key]];
        }

        $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token='.$this->token;
        $data = [
            'touser'=>$openId,
            'template_id'=>$templateId,
            'form_id'=>$fromId,
            'data'=>$content
        ];
        if($page){
            $data['page'] = $page;
        }

        $response = $this->client->post($url,['json'=>$data]);

        $result = json_decode((string) $response->getBody(), true);

        if($result['errcode'] == 0){
            $status = TemplateLog::ENUM_STATUS_SUCCESS;
        }else{
            $status = TemplateLog::ENUM_STATUS_SUCCESS;
        }

        TemplateLog::create([
            TemplateLog::FIELD_ID_OPEN=>$openId,
            TemplateLog::FIELD_ID_TEMPLATE=>$templateId,
            TemplateLog::FIELD_ID_APP=>$this->appId,
            TemplateLog::FIELD_CONTENT=>$data,
            TemplateLog::FIELD_PAGE=>$page,
            TemplateLog::FIELD_STATUS=>$status,
            TemplateLog::FIELD_RESULT=>$result
        ]);

        return $result;
    }

}