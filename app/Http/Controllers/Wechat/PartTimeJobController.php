<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/19 0019
 * Time: 10:17
 */

namespace App\Http\Wechat;


use App\Http\Controllers\Controller;

class PartTimeJobController extends Controller
{
    public function store()
    {
        $user = request()->input('user');
        $title = request()->input('title');
        $content = request()->input('content');
        $attachments = request()->input('attachments',[]);
        $salary = request()->input('salary',0);
        $endAt = request()->input('end_at');
    }

    public function jobs()
    {

    }

}