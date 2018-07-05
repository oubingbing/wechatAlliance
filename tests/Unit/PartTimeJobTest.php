<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/5 0005
 * Time: 9:46
 */

namespace Tests\Unit;


use App\Http\Service\PartTimeJobService;
use Tests\TestCase;

class PartTimeJobTest extends TestCase
{
    public function testEmployeeComments()
    {
        $userId = 4058;
        $result = app(PartTimeJobService::class)->employeeMissionComments($userId);
    }

    public function testCountEmployeeJob()
    {
        $userId = 4058;
        $result = app(PartTimeJobService::class)->countEmployee($userId);

        self::assertNotEmpty($result);
    }

}