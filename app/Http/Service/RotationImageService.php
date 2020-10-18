<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/16 0016
 * Time: 16:41
 */

namespace App\Http\Service;


use App\Models\RotationImageModel;

class RotationImageService
{
    public function store($appId,$collegeId,$img)
    {
        $result = RotationImageModel::create([
            RotationImageModel::FIELD_ID_APP=>$appId,
            RotationImageModel::FIELD_ID_COLLEGE=>$collegeId,
            RotationImageModel::FIELD_IMAGE=>$img
        ]);
        return $result;
    }

    public function delete($appId,$id)
    {
        $result = RotationImageModel::query()
            ->where(RotationImageModel::FIELD_ID_APP,$appId)
            ->where(RotationImageModel::FIELD_ID,$id)
            ->delete();
        return $result;
    }

    public function listData($appId)
    {
        $list = RotationImageModel::query()
            //->where(RotationImageModel::FIELD_ID_APP,$appId)
            ->select([
                RotationImageModel::FIELD_ID,
                RotationImageModel::FIELD_URL,
                RotationImageModel::FIELD_SORT,
                RotationImageModel::FIELD_WECHAT_APP,
                RotationImageModel::FIELD_IMAGE,
                RotationImageModel::FIELD_URL
            ])
            ->get();
        return $list;
    }

    public function update($id,$appId,$wechatId)
    {
        $result = RotationImageModel::query()
            ->where(RotationImageModel::FIELD_ID,$id)
            ->where(RotationImageModel::FIELD_ID_APP,$appId)
            ->update([RotationImageModel::FIELD_WECHAT_APP=>$wechatId]);
        return $result;
    }

    public function updateUrl($id,$appId,$url)
    {
        $result = RotationImageModel::query()
            ->where(RotationImageModel::FIELD_ID,$id)
            ->where(RotationImageModel::FIELD_ID_APP,$appId)
            ->update([RotationImageModel::FIELD_URL=>$url]);
        return $result;
    }
}