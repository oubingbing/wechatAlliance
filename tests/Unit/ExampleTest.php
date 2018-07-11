<?php

namespace Tests\Unit;

use App\Http\Service\Http;
use App\Http\Service\MathService;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->assertTrue(true);
    }


    public function testDemo()
    {

        $image1 = "http://image.kucaroom.com/%E5%BE%AE%E4%BF%A1%E5%9B%BE%E7%89%87_20180604170505.jpg";
        $image2 = "http://image.kucaroom.com/%E5%BE%AE%E4%BF%A1%E5%9B%BE%E7%89%87_20180604155126.jpg";

        $result = app(Http::class)->compareFace($image1,$image2,$imageType=0);

        dd($result);
    }

    public function testRand()
    {
        $arr = [
            [0,0,0,0,0,0],
            [0,0,0,0,0,0],
            [0,0,0,0,0,0],
            [0,0,0,0,0,0],
            [0,0,0,0,0,0],
            [0,0,0,0,0,0]
        ];
        $tempArr = $arr;

        //模拟随机点开花木兰拼图
        for ($i = 1 ; $i <= 360 ; $i++){
            foreach ($tempArr as $firstKey => $firstItem){
                foreach ($firstItem as $secondKey => $secondItem){
                    if($secondItem == 1){
                        unset($tempArr[$firstKey][$secondKey]);
                    }
                }
            }
            $one_index = array_rand($tempArr);
            $two_index = array_rand($arr[$one_index]);

            $arr[$one_index][$two_index] = 1;
        }

    }

    public function testCreateJigsaw()
    {
        $array = [0,0,0,1,0,1,1,1,1,1];
        $num = 0;
        foreach ($array as $item){
            if($item == 1){
                $num++;
            }
        }

        dd($num);
    }

    public function testTime()
    {
        dd(date('Y-m-d H:i:s',time()));
    }

    /**
     *         {
    points: [{
    longitude: 113.3245211,
    latitude: 23.10229
    },
    {
    longitude: 109.324520,
    latitude: 26.21229
    },
    {
    longitude: 103.324520,
    latitude: 29.21229
    }],
    color: "#66CDAA",
    width: 2,
    dottedLine: false
     */
    public function testMap()
    {
        $result = app(MathService::class)->locationY(23.1022,113.3245,29.2122,103.3245,26);

        dd($result);
    }
}
