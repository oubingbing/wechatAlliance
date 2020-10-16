<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/16 0016
 * Time: 16:12
 */

namespace App\Http\Wechat;


use App\Http\Controllers\Controller;
use App\Http\Service\RotationImageService;
use App\Models\RotationImageModel;
use App\Models\User;

class RotationImageController extends Controller
{
    protected $service;

    public function __construct(RotationImageService $postService)
    {
        $this->service = $postService;
    }

    public function imageList()
    {
        $user = request()->input('user');

        $list = $this->service->listData($user->{User::FIELD_ID_APP});

        $domain = env("QI_NIU_DOMAIN");
        $list = collect($list)->map(function ($item,$index)use($domain){
            $item->{RotationImageModel::FIELD_IMAGE} = $domain."/".$item->{RotationImageModel::FIELD_IMAGE};
            if ($index == 0){
                $item->show = true;
            }else{
                $item->show = false;
            }
            return $item;
        });

        return $list;
    }

}