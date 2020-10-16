<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/11/17
 * Time: 下午5:26
 */

namespace Tests\Unit;


use AlibabaCloud\SDK\ViapiUtils\ViapiUtils;
use AlibabaCloud\Tea\Exception\TeaUnableRetryError;
use App\Http\Service\Http;
use App\Post;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PostTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * 测试封装分页
     *
     * @author 叶子
     *
     * @test
     */
    public function paginate()
    {
        $pageParams = [
            'page_size'=>2,
            'page_number'=>2
        ];

        $query = Post::query();

        $result = paginate($query,$pageParams, '*',function($item){
            $item->name = '叶子';

            return $item;
        });

        dd(collect($result)->toArray());

    }

    /**
     * vendor/bin/phpunit tests/unit/PostTest.php --filter=comment
     *
     * @test
     */
    public function comment()
    {
        $img = "https://image.qiuhuiyi.cn/tmp/wx0f587d7c97a68e2b.o6zAJs3oh85Zb1lJE8oWix57vny0.LsG26c4Timu0b502fa7c03ff8e41d4297904e22add9b.jpg";

        $http = new Http();
        $http->compareFace($img,$img);
    }

}