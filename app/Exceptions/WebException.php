<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/20 0020
 * Time: 15:28
 */

namespace App\Exceptions;


class WebException extends \Exception
{
    function __construct($msg='',$code=0)
    {
        parent::__construct($msg,$code);
    }

}