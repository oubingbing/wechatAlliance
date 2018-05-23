<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/11/11
 * Time: 下午3:06
 */

namespace App\Exceptions;


class ApiException extends \Exception
{
    function __construct($msg='',$code)
    {
        parent::__construct($msg,$code);
    }

}