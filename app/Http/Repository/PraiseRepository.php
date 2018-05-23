<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/12/15
 * Time: 下午2:45
 */

namespace App\Http\Repository;


use App\Praise;

class PraiseRepository
{
    protected $praise;

    public function __construct(Praise $praise)
    {
        $this->praise = $praise;
    }

    public function getPraiseById($id)
    {
        return $this->praise->find($id);
    }

}