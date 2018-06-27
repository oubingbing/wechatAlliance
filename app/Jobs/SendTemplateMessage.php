<?php

namespace App\Jobs;

use App\Http\Service\NotificationService;
use App\Models\TemplateLog;
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
        (new NotificationService($this->appId))->templateMessage($this->data['open_id'], $this->data['template_id'], $this->data['values'], $this->data['from_id'], $this->data['page']);
    }
}
