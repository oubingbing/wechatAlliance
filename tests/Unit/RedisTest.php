<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2018/3/18
 * Time: 下午2:48
 */

namespace Tests\Unit;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class RedisTest extends TestCase
{
    /**
     * 测试redis
     *
     * @test
     */
    public function store()
    {
        Redis::set('username','志彬');

        $name = Redis::get('username');

        self::assertNotEmpty($name);
    }

    /**
     * @test
     */
    public function storeCache()
    {
        Cache::put('test_value',18,10);
        $value = Cache::get('test_value');
        self::assertNotEmpty($value);

        dd($value);
    }

}