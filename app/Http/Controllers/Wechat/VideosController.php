<?php
/**
 * Created by PhpStorm.
 * User: bingbing
 * Date: 2019/3/3
 * Time: 14:25
 */

namespace App\Http\Controllers\Wechat;


use App\Http\Controllers\Controller;
use App\Http\Service\VideosService;
use App\Models\User;
use App\Models\Videos;

class VideosController extends Controller
{

    private $service;

    public function __construct(VideosService $service)
    {
        $this->service = $service;
    }

    public function videoList()
    {
        $user       = request()->input('user');
        $pageSize   = request()->input('page_size', 10);
        $pageNumber = request()->input('page_number', 1);

        $pageParams = ['page_size' => $pageSize, 'page_number' => $pageNumber];
        $query      = $this->service->createBuilder($user->{User::FIELD_ID_APP})->sort()->done();
        $select     = [
            Videos::FIELD_ID,
            Videos::FIELD_TITLE,
            Videos::FIELD_ID_APP,
            Videos::FIELD_V_ID,
            Videos::FIELD_SORT
        ];
        $videoList = paginate($query, $pageParams, $select, function ($item) use ($user) {
            return $item;
        });

        return $videoList;
    }
}