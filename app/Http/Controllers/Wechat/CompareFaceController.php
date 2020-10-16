<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/5 0005
 * Time: 9:40
 */

namespace App\Http\Wechat;


use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\CompareFaceService;
use App\Http\Service\Http;
use App\Models\CompareFace;
use App\Models\User;

class CompareFaceController extends Controller
{
    /**
     * 进行照片比对
     *
     * @author yezi
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws ApiException
     */
    public function store()
    {
        $user     = request()->input('user');
        $yourFace = request()->input('your_face');
        $hisFace  = request()->input('his_face');

        if(empty($yourFace) || empty($hisFace)){
            throw new ApiException('照片不能为空',500);
        }

        $compareService = app(CompareFaceService::class);
        $compareResult  = app(Http::class)->compareFace($yourFace,$hisFace);

        if($compareResult){
            if(isset($compareResult['RectAList'])){
                $emptyRectA = $compareService->checkEmptyRect($compareResult['RectAList']);
                $emptyRectB = $compareService->checkEmptyRect($compareResult['RectBList']);

                if($emptyRectA){
                    throw new ApiException('图中无人脸！',500);
                }

                if($emptyRectB){
                    throw new ApiException('图中无人脸！',500);
                }

                $result = $compareService->create($user->id,$user->{User::FIELD_ID_COLLEGE},$yourFace,$hisFace,CompareFace::ENUM_STATUS_SUCCESS,$compareResult);
                if($result){
                    $report = $compareService->report($compareResult);
                    return $report;
                }else{
                    throw new ApiException('比对失败，请稍后再试！',500);
                }

            }else{
                throw new ApiException('比对失败，请稍后再试！',500);
            }
        }else{
            throw new ApiException('比对失败，请稍后再试！',500);
        }

    }
}