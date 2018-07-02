<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2018/3/17
 * Time: 上午11:56
 */

namespace App\Http\Controllers;


use GuzzleHttp\Client;
use Lndj\Lcrawl;

class BirthdayController
{
    public function index()
    {
        return view('birthday.flower');
    }


    public function test(){
        $stu_id = '152011052';
        $password = 'luo622';

        $user = ['stu_id' => $stu_id, 'stu_pwd' => $password];

        $client = new Lcrawl('http://jwxt.nfsysu.cn/', $user);

        $client->login();

    }

}