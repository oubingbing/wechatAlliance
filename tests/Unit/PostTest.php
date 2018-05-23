<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/11/17
 * Time: 下午5:26
 */

namespace Tests\Unit;


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
     * @test
     */
    public function comment()
    {
        $post = Post::find(21);

        dd($post->comments->toArray());
    }

}