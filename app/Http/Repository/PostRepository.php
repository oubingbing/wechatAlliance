<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/12/15
 * Time: 下午2:44
 */

namespace App\Http\Repository;


use App\Post;

class PostRepository
{
    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function getPostById($id)
    {
        return $this->post->find($id);
    }

}