<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/12/15
 * Time: 下午2:45
 */

namespace App\Http\Repository;


use App\SaleFriend;

class SaleFriendRepository
{
    protected $saleFriend;

    public function __construct(SaleFriend $saleFriend)
    {
        $this->saleFriend = $saleFriend;
    }

    public function getSaleFriendById($id)
    {
        return $this->saleFriend->find($id);
    }

}