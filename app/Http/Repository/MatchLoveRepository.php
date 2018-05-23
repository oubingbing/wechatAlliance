<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/12/15
 * Time: 下午2:45
 */

namespace App\Http\Repository;


use App\MatchLove;

class MatchLoveRepository
{
    protected $matchLove;

    public function __construct(MatchLove $matchLove)
    {
        $this->matchLove = $matchLove;
    }

    public function getMatchLoveById($id)
    {
        return $this->matchLove->find($id);
    }
}