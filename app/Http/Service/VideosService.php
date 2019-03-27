<?php
/**
 * Created by PhpStorm.
 * User: bingbing
 * Date: 2019/3/3
 * Time: 13:33
 */

namespace App\Http\Service;


use App\Models\Videos as Model;

class VideosService
{
    protected $builder;

    public function storeVideos(Model $videos)
    {
        $result = Model::create([
            Model::FIELD_ID_APP       => $videos->{Model::FIELD_ID_APP},
            Model::FIELD_V_ID         => $videos->{Model::FIELD_V_ID},
            Model::FIELD_ATTACHMENTS  => $videos->{Model::FIELD_ATTACHMENTS},
            Model::FIELD_TITLE        => $videos->{Model::FIELD_TITLE},
            Model::FIELD_SORT         => $videos->{Model::FIELD_SORT},
            Model::FIELD_INTRODUCTION => $videos->{Model::FIELD_INTRODUCTION},
        ]);
        return $result;
    }

    public function createBuilder($appId)
    {
        $this->builder = Model::query()->where(Model::FIELD_ID_APP,$appId);
        return $this;
    }

    public function sort()
    {
        $this->builder->orderBy('sort','asc');
        return $this;
    }

    public function done()
    {
        return $this->builder;
    }

    public function findById($id)
    {
        return Model::query()->find($id);
    }

}