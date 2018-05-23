<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/11/13
 * Time: 下午5:15
 */

namespace App\Http\Controllers;

use App\Http\Service\QiNiuService;

class QiNiuController extends Controller
{

    /**
     * 获取七牛上传凭证
     *
     * @author yezi
     *
     * @return mixed
     */
    public function getUploadToken()
    {
        $token = app(QiNiuService::class)->uploadToken();

        return ['uptoken'=>$token];
    }
}