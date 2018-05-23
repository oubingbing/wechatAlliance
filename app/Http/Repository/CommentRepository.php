<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/12/15
 * Time: 下午2:44
 */

namespace App\Http\Repository;


use App\Comment;

class CommentRepository
{
    protected $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function getCommentById($id)
    {
        return $this->comment->find($id);
    }

}