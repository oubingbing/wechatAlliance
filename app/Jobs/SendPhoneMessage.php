<?php

namespace App\Jobs;

use App\Http\Service\YunPianService;
use App\Models\MessageCode;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendPhoneMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $mobile;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mobile)
    {
        $this->mobile = $mobile;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $code = rand(1000,10000);
        $content = "您的手机验证码为：$code";

        $yunPian = app(YunPianService::class);
        $yunPian->sendSingle($this->mobile,$content);

        $expireTime = Carbon::now()->addMinute(2);
        $result = MessageCode::query()->where(MessageCode::FIELD_MOBILE,$this->mobile)->first();
        if($result){
            $result->{MessageCode::FIELD_UPDATED_AT} = $expireTime;
            $result->save();
        }else{
            MessageCode::create([
                MessageCode::FIELD_MOBILE => $this->mobile,
                MessageCode::FIELD_CODE => $code,
                MessageCode::FIELD_CREATED_AT => Carbon::now(),
                MessageCode::FIELD_UPDATED_AT => $expireTime
            ]);
        }
    }
}
