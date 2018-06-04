<?php

namespace Tests\Unit;

use App\Http\Service\Http;
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
}
