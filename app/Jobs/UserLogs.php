<?php

namespace App\Jobs;

use App\Exceptions\ApiException;
use App\Models\User;
use App\Models\UserVisitLog;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class UserLogs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * UserLogs constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            \DB::beginTransaction();

            $user = DB::table('users')->where(User::FIELD_ID,$this->user->id)->lockForUpdate()->first();

            $visitLog = UserVisitLog::query()
                ->where(UserVisitLog::FIELD_ID_USER,$user->id)
                ->whereBetween(UserVisitLog::FIELD_CREATED_AT,[Carbon::now()->startOfDay(),Carbon::now()->endOfDay()])
                ->value(UserVisitLog::FIELD_ID);

            if(!$visitLog){
                UserVisitLog::create([
                    UserVisitLog::FIELD_ID_USER=>$user->id,
                    UserVisitLog::FIELD_NICKNAME=>$user->{User::FIELD_NICKNAME}
                ]);
            }

            \DB::commit();
        }catch (\Exception $exception){
            \DB::rollBack();
            throw new ApiException($exception,500);
        }
    }
}
