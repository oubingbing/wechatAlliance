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
     * 获取出发地与目的直线斜率
     *
     * @author yezi
     *
     * @param $fx
     * @param $fy
     * @param $tx
     * @param $ty
     * @return float|int
     */
    public function lineSlope($fx,$fy,$tx,$ty)
    {
        $result = ($ty - $fy) / ($tx - $fx);
        return $result;
    }

    /**
     * 知道两个地理坐标形成的直线斜率，求该直线与水平直线形成的夹角
     *
     * @author yezi
     *
     * @param $slope
     * @return float|int
     */
    public function lineAngle($slope)
    {
        $result = atan($slope) / (2 * M_PI) * 360;
        return abs($result);
    }

    public function angleToRad($angle)
    {
        return ($angle * M_PI / 180);
    }

    /**
     * 求剩余角度
     *
     * @author yezi
     *
     * @param $angle
     * @return int
     */
    public function otherAngle($angle)
    {
        return abs(90 - $angle);
    }

    /**
     * 已知直角跟对边的(length / (sin(90))),求bc的长度
     *
     * @author yezi
     *
     * @param $value
     * @param $angle
     * @return mixed
     */
    public function ACLength($value,$angle)
    {
        $rad = $this->angleToRad($angle);
        return ($value * (sin($rad)));
    }

    /**
     * 已知直角跟对边的(length / (sin(90))),求ac的长度
     *
     * @author yezi
     *
     * @param $value
     * @param $angle
     * @return mixed
     */
    public function BCLength($value,$angle)
    {
        $rad = $this->angleToRad($angle);
        return ($value * sin($rad));
    }

    public function location($fx,$fy,$tx,$slope,$ac,$bc)
    {
        if($slope < 0){
            if($fx > $tx){
                $x = $fx - $ac;
                $y = $fy + $bc;
            }else{
                $x = $fx + $ac;
                $y = $fy - $bc;
            }
        }else{
            if($fx > $tx){
                $x = $fx - $ac;
                $y = $fy - $bc;
            }else{
                $x = $fx + $ac;
                $y = $fy + $bc;
            }
        }

        return ['x'=>$x,'y'=>$y];
    }

    /**
     * 获取两地理坐标直线上的任意距离的地理坐标点
     * 
     * @author yezi
     * 
     * @param $fx
     * @param $fy
     * @param $tx
     * @param $ty
     * @param $dis
     * @return array
     */
    public function getLocationPoint($fx,$fy,$tx,$ty,$dis)
    {
        $lineSlope = $this->lineSlope($fx,$fy,$tx,$ty);
        $angle = $this->lineAngle($lineSlope);
        $otherAngle = $this->otherAngle($angle);
        $bc = $this->BCLength($dis,$angle);
        $ac = $this->ACLength($dis,$otherAngle);
        $locationPoint = $this->location($fx,$fy,$tx,$lineSlope,$ac,$bc);
        
        return $locationPoint;
    }

    /**
     * 计算两点间的距离
     *
     * @author yezi
     *
     * @param $fx
     * @param $fy
     * @param $tx
     * @param $ty
     * @return float
     */
    public function distanceBetweenPoint($fx,$fy,$tx,$ty)
    {
        $distance = sqrt((($fx-$tx)*($fx-$tx))+(($fy-$ty)*($fy-$ty)));
        return $distance;
    }

    /**
     * 步数转化成米单位
     *
     * @author yezi
     *
     * @param $step
     * @return mixed
     */
    public function stepToMeter($step)
    {
        return $step * 0.55;
    }

}