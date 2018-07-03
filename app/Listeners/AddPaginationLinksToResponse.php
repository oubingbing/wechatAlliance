<?php

namespace App\Listeners;

use Carbon\Carbon;
use Dingo\Api\Event\ResponseWasMorphed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\View;

class AddPaginationLinksToResponse
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ResponseWasMorphed  $event
     * @return void
     */
    public function handle(ResponseWasMorphed $event)
    {
        if($event->content instanceof View)
            return;
        $response = array();
        $data = $event->content;
        if(isset($data['error_code'])){
            foreach ($data as $key => $item){
                $response[$key] = $item;
            }
        }else{
            $response['error_code'] = 0;
            $response['error_message'] = 'success';
            $response['data'] = $data;
        }
        $response['json_api'] = [
            'meta'=>[
                'name'=>'Json Api 小情书联盟',
                'copyright'=>Carbon::now()->year.' ouzhibing@outlook.com',
                'power_by'=>'叶子'
            ]
        ];
        $event->content = $response;
    }
}
