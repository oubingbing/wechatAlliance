<?php

namespace App\Jobs;

use App\Http\Service\FormIdService;
use App\Http\Service\NotificationService;
use App\Models\FormIds;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendTemplateMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $appId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($appId,$data)
    {
        $this->data = $data;
        $this->appId = $appId;
    }

    /**
     * 队列执行发送微信模板消息
     *
     * @author yezi
     *
     * @return void
     */
    public function handle()
    {
        $userId = User::query()->where(User::FIELD_ID_OPENID,$this->data['open_id'])->value(User::FIELD_ID);
        if($userId){
            $formId = app(FormIdService::class)->getForIdByUserId($userId);
            if($formId){
                (new NotificationService($this->appId))->templateMessage($this->data['open_id'], $this->data['title'], $this->data['values'], $formId, $this->data['page']);
            }
        }
    }
}
