<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/11/17
 * Time: 下午4:14
 */

namespace App\Http\Service;


use App\Http\Repository\CommentRepository;
use App\Http\Repository\MatchLoveRepository;
use App\Http\Repository\PostRepository;
use App\Http\Repository\PraiseRepository;
use App\Http\Repository\SaleFriendRepository;
use App\Models\Comment;
use App\Models\MatchLove;
use App\Models\Post;
use App\Models\SaleFriend;
use App\Models\Topic;
use App\Models\User;

class CommentService
{

    /**
     * 保存评论内容
     *
     * @author yezi
     *
     * @param $commenterId
     * @param $objId
     * @param $content
     * @param $type
     * @param $refCommentId
     * @param null $attachments
     * @param null $collegeId
     * @return mixed
     */
    public function saveComment($commenterId, $objId, $content, $type, $refCommentId, $attachments = null, $collegeId = null)
    {
        $comment = Comment::create([
            Comment::FIELD_ID_COMMENTER   => $commenterId,
            Comment::FIELD_ID_OBJ         => $objId,
            Comment::FIELD_CONTENT        => $content,
            Comment::FIELD_OBJ_TYPE       => $type,
            Comment::FIELD_ID_REF_COMMENT => $refCommentId,
            Comment::FIELD_ATTACHMENTS    => $attachments,
            Comment::FIELD_ID_COLLEGE     => $collegeId
        ]);

        return $comment;
    }

    /**
     * 评论自增
     *
     * @author yezi
     *
     * @param $type
     * @param $objId
     * @return int
     */
    public function incrementComment($type, $objId)
    {
        switch ($type) {
            case Comment::ENUM_OBJ_TYPE_POST:
                $result = Post::query()->where(Post::FIELD_ID, $objId)->increment(Post::FIELD_COMMENT_NUMBER);
                break;
            case Comment::ENUM_OBJ_TYPE_SALE_FRIEND:
                $result = SaleFriend::query()->where(SaleFriend::FIELD_ID, $objId)->increment(SaleFriend::FIELD_COMMENT_NUMBER);
                break;
            case Comment::ENUM_OBJ_TYPE_TOPIC:
                $result = Topic::query()->where(Topic::FIELD_ID, $objId)->increment(Topic::FIELD_COMMENT_NUMBER);
                break;
            default:
                $result = Post::query()->where(Post::FIELD_ID, $objId)->increment(Post::FIELD_COMMENT_NUMBER);
                break;
        }

        return $result;
    }

    /**
     * 获取评论
     *
     * @author yezi
     *
     * @param $objId
     * @param $objType
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function comments($objId, $objType)
    {
        $comments = Comment::query()
            ->where(Comment::FIELD_ID_OBJ, $objId)
            ->where(Comment::FIELD_OBJ_TYPE, $objType)
            ->get();

        return $comments;
    }

    /**
     * 批量格式化评论
     *
     * @author yezi
     *
     * @param $comments
     * @param $user
     * @param $obj
     * @return array
     */
    public function formatBatchComments($comments, $user,$obj=null)
    {
        return collect($comments)->map(function ($item) use ($user,$obj) {

            return $this->formatSingleComments($item, $user,$obj);

        })->toArray();
    }

    /**
     * 格式化单条评论
     *
     * @author yezi
     *
     * @param $comment
     * @param $user
     * @param $obj
     * @return mixed
     */
    public function formatSingleComments($comment, $user,$obj=null)
    {
        $commenter = User::find($comment['commenter_id']);

        //格式化卖舍友评论
        if ($comment['obj_type'] == Comment::ENUM_OBJ_TYPE_SALE_FRIEND || $comment['obj_type'] == Comment::ENUM_OBJ_TYPE_TOPIC) {
            $this->formatBatchComments($comment->subComments, $user);
        }

        $nickname = $commenter->{User::FIELD_NICKNAME};

        if($obj){
            if(isset($obj['private'])){
                if($obj['private'] == 1){
                    if($comment['commenter_id'] == $obj['poster_id']){
                        $nickname = '匿名の同学';
                    }
                }
            }
        }

        $comment['commenter'] = [
            'id'       => $commenter->{User::FIELD_ID},
            'nickname' => $nickname,
            'avatar'   => $commenter->{User::FIELD_AVATAR},
            'text'     => $comment[ Comment::FIELD_CONTENT ]
        ];

        if ($comment[ Comment::FIELD_ID_REF_COMMENT ]) {
            $refComment = Comment::withTrashed()->find($comment[ Comment::FIELD_ID_REF_COMMENT ]);
            if ($refComment) {
                $refComment->refCommenter = User::where(User::FIELD_ID, $refComment->{Comment::FIELD_ID_COMMENTER})->select('id', 'nickname', 'avatar')->first();

                if($refComment->refCommenter){
                    if($obj){
                        if(isset($obj['private'])){
                            if($obj['private'] == 1){
                                if($refComment->refCommenter->id == $obj['poster_id']){
                                    $refComment->refCommenter->nickname = '匿名の同学';
                                }
                            }
                        }
                    }
                }

                $comment['ref_comment']   = $refComment;
            } else {
                $comment['ref_comment'] = '';
            }
        } else {
            $comment['ref_comment'] = '';
        }

        if ($comment[ Comment::FIELD_ID_COMMENTER ] == $user->{User::FIELD_ID}) {
            $comment['can_delete'] = true;
        } else {
            $comment['can_delete'] = false;
        }

        return $comment;
    }

    /**
     * 获取评论的对象
     *
     * @param $type
     * @param $objId
     * @return string
     */
    public function getObjUserId($type, $objId)
    {
        $userId = '';
        switch ($type) {
            case Comment::ENUM_OBJ_TYPE_POST:
                $obj    = Post::find($objId);
                $userId = $obj->{Post::FIELD_ID_POSTER};
                break;
            case Comment::ENUM_OBJ_TYPE_SALE_FRIEND:
                $obj    = SaleFriend::find($objId);
                $userId = $obj->{SaleFriend::FIELD_ID_OWNER};
                break;
            case Comment::ENUM_OBJ_TYPE_MATCH_LOVE:
                $obj    = MatchLove::find($objId);
                $userId = $obj->{MatchLove::FIELD_ID_OWNER};
                break;
            case  Comment::ENUM_OBJ_TYPE_COMMENT:
                $obj    = Comment::find($objId);
                $userId = $obj->{Comment::FIELD_ID_COMMENTER};
                break;
            case  Comment::ENUM_OBJ_TYPE_TOPIC:
                $obj    = Topic::find($objId);
                $userId = $obj->{Topic::FIELD_ID_USER};
                break;
        }

        return [
            'userId'=>$userId,
            'obj'=>$obj
        ];
    }

}