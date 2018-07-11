<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/11 0011
 * Time: 15:24
 */

namespace App\Http\Service;


class MathService
{
    /**
     * 已知x轴，求两点直线上的y轴
     *
     * @author yezi
     *
     * @param $x1
     * @param $y1
     * @param $x2
     * @param $y2
     * @param $x
     * @return float|int
     */
    public function locationY($x1,$y1,$x2,$y2,$x)
    {
        $y = ($y1-$y2)*($x-$x1)/($x1-$x2)+$y1;
        return $y;
    }

    /**
     * 已知y轴，求两点直线上的x轴
     *
     * @author yezi
     *
     * @param $x1
     * @param $y1
     * @param $x2
     * @param $y2
     * @param $y
     * @return float|int
     */
    public function locationX($x1,$y1,$x2,$y2,$y)
    {
        $x = ($y-$y1)*($x1-$x2)/($y1-$y2)+$x1;
        return $x;
    }

}