<?php

namespace App\Jobs;

use App\Http\Service\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendInbox implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $appId;
    protected $fromId;
    protected $toId;
    protected $objId;
    protected $content;
    protected $objType;
    protected $actionType;
    protected $postAt;
    protected $private;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($appId,$fromId, $toId, $objId, $content, $objType, $actionType, $postAt,$private=0)
    {
        $this->appId = $appId;
        $this->fromId = $fromId;
        $this->toId = $toId;
        $this->objId = $objId;
        $this->content = $content;
        $this->objType = $objType;
        $this->actionType = $actionType;
        $this->postAt = $postAt;
        $this->private = $private;
    }

    /**
     * 往消息盒子投递消息
     *
     * @author yezi
     *
     * @return void
     */
    public function handle()
    {
        (new NotificationService($this->appId))->sendInbox($this->fromId, $this->toId, $this->objId, $this->content, $this->objType, $this->actionType, $this->postAt,$this->private);
    }
}
