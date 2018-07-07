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
        $form = app(FormIdService::class)->getForIdByUserId($this->data['user_id']);
        if($form){
            (new NotificationService($this->appId))->templateMessage($form->{FormIds::FIELD_ID_OPEN}, $this->data['title'], $this->data['values'], $form->{FormIds::FIELD_ID_FORM}, $this->data['page']);
        }
    }
}
