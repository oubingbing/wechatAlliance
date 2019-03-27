<?php
/**
 * Created by PhpStorm.
 * User: bingbing
 * Date: 2018/7/7
 * Time: 17:15
 */

namespace App\Http\Service;


use App\Models\FormIds;
use Carbon\Carbon;

class FormIdService
{
    /**
     * 保存formid
     *
     * @author yezi
     *
     * @param $userId
     * @param $formId
     * @param $openId
     * @param $expire
     * @return mixed
     */
    public function save($userId,$openId,$formId,$expire)
    {
        $formId = FormIds::create([
            FormIds::FIELD_ID_FORM    => $formId,
            FormIds::FIELD_ID_USER    => $userId,
            FormIds::FIELD_EXPIRED_AT => $expire,
            FormIds::FIELD_ID_OPEN=>$openId
        ]);

        return $formId;
    }

    /**
     * 获取form id
     *
     * @author yezi
     *
     * @param $userId
     * @return mixed
     */
    public function getForIdByUserId($userId)
    {
        $form = FormIds::query()
            ->where(FormIds::FIELD_ID_USER,$userId)
            ->where(FormIds::FIELD_EXPIRED_AT,'>=',Carbon::now())
            ->first();

        if($form){
            FormIds::query()->where(FormIds::FIELD_ID,$form->id)->delete();
            return $form;
        }
    }

}