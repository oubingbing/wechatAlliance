<?php

namespace Tests\Unit;

use App\Http\Service\Http;
use App\Http\Service\MathService;
use App\Http\Service\StepTravelService;
use App\Http\Service\TravelService;
use App\Models\RunStep;
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

    public function testMap()
    {
        $result = app(MathService::class)->lineAngle(0.611);

        dd($result);
    }

    public function testLocation()
    {
        $fx = 108.2045;
        $fy = 26.2304;
        $tx = 100;
        $ty = 20;
        $dis = 2;
        $result = app(MathService::class)->getLocationPoint($fx,$fy,$tx,$ty,$dis);
        dd($result);
    }

    public function testDistance()
    {
        $fx = 108.2045;
        $fy = 26.2304;
        $tx = 100;
        $ty = 20;
        $result = app(MathService::class)->distanceBetweenPoint($fx,$fy,$tx,$ty);
        dd($result);
    }

    public function testStepToMeter()
    {
        $result = app(MathService::class)->stepToMeter(3671);
        dd($result);
    }

    public function testGetDist()
    {
        $result = app(MathService::class)->getDistance(29.21229, 103.324520, 26.21229, 108.58195102022);
        dd($result);
    }

    public function testTowPointDistance()
    {
        $result = app(MathService::class)->towPointDistance(29.21229, 103.324520, 26.21229, 108.58195102022);
        dd($result);
    }

    public function testMoveTravel()
    {
        $userId = 5318;
        $stepData = app(StepTravelService::class)->getUserAllRunData($userId);
        $result = app(TravelService::class)->travelLog(5318,$stepData);

        dd($result);
    }

    public function testUpdate()
    {
        $arr = [
            ['id'=>34,'status'=>2],
            ['id'=>35,'status'=>2],
            ['id'=>37,'status'=>2],
        ];
        //$result = RunStep::updateBatch(RunStep::class,$arr);
        dd();
    }

    public function testStas()
    {
        $result = app(TravelService::class)->statisticsTravel(37);

        dd($result);
    }

    public function testPoi()
    {
        $result = app(TravelService::class)->statisticsPoi(37);

        dd($result);
    }
}
