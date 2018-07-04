<?php

namespace App\Jobs;

use App\Http\Service\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendPhoneMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $appId;
    protected $mobile;
    protected $content;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($appId,$mobile,$content)
    {
        $this->appId = $appId;
        $this->mobile = $mobile;
        $this->content = $content;
    }

    /**
     * 发送短信消息
     *
     * @author yezi
     *
     * @return void
     */
    public function handle()
    {
        (new NotificationService($this->appId))->sendMobileMessage($this->mobile,$this->content);
    }
}
