<?php
/**
 * Created by PhpStorm.
 * User: bingbing
 * Date: 2018/7/7
 * Time: 17:21
 */

namespace App\Http\Wechat;


use App\Http\Controllers\Controller;
use App\Http\Service\FormIdService;
use App\Models\User;
use Carbon\Carbon;

class FormIdController extends Controller
{
    public function save()
    {
        $user   = request()->input('user');
        $formId = request()->input('form_id');

        if($formId == 'the formId is a mock one'){
            return;
        }

        $result = app(FormIdService::class)->save($user->id,$user->{User::FIELD_ID_OPENID},$formId,Carbon::now()->addDay(7));

        return $result;
    }

}