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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $result = (new NotificationService($this->appId))->templateMessage($this->data['open_id'],$this->data['template_id'],$this->data['values'],$this->data['from_id'],$this->data['page']);
        if($result['errcode' == 0]){
            $status = TemplateLog::ENUM_STATUS_SUCCESS;
        }else{
            $status = TemplateLog::ENUM_STATUS_SUCCESS;
        }

        TemplateLog::create([
            TemplateLog::FIELD_ID_APP=>$this->appId,
            TemplateLog::FIELD_ID_TEMPLATE=>$this->data['template_id'],
            TemplateLog::FIELD_ID_OPEN=>$this->data['open_id'],
            TemplateLog::FIELD_PAGE=>$this->data['page'],
            TemplateLog::FIELD_STATUS=>$status
        ]);
    }
}
