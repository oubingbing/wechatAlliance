<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/11/17
 * Time: 下午4:14
 */

namespace App\Http\Wechat;


use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\AppService;
use App\Http\Service\CommentService;
use App\Http\Service\Http;
use App\Http\Service\InboxService;
use App\Http\Service\TokenService;
use App\Models\Comment;
use App\Models\Inbox;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;

class CommentController extends Controller
{
    protected $inbox;
    protected $comment;

    public function __construct(InboxService $inboxLogic,CommentService $commentLogic)
    {
        $this->inbox   = $inboxLogic;
        $this->comment = $commentLogic;
    }

    /**
     * 评论
     *
     * @author yezi
     *
     * @return mixed
     * @throws ApiException
     */
    public function store()
    {
        $user         = request()->input('user');
        $objId        = request()->input('obj_id');
        $content      = request()->input('content');
        $type         = request()->input('type');
        $refCommentId = request()->input('ref_comment_id',null);
        $attachments  = request()->input('attachments',null);

        $commenterId  = $user->{User::FIELD_ID};
        $collegeId    = $user->{User::FIELD_ID_COLLEGE};
        $objData      = $this->comment->getObjUserId($type,$objId);
        $objUserId    = $objData['userId'];
        if(!$objUserId){
            throw new ApiException('对象不存在',404);
        }

        app(AppService::class)->checkContent($user->{User::FIELD_ID_APP},$content);

        $fromId = $user->id;

        if($refCommentId){
            //回复别人,要通知该条评论的主人
            $refCommentObj = $this->comment->getObjUserId(Comment::ENUM_OBJ_TYPE_COMMENT, $refCommentId);
            $toId          = $refCommentObj['userId'];
        }else{
            $toId = $objUserId;
        }

        $objType    = Inbox::ENUM_OBJ_TYPE_COMMENT;
        $postAt     = Carbon::now();
        $actionType = Inbox::ENUM_ACTION_TYPE_COMMENT;

        try{
            \DB::beginTransaction();

            $result = $this->comment->saveComment($commenterId, $objId, $content, $type, $refCommentId, $attachments, $collegeId);
            //如果评论对象是话题就不需要投递消息盒子
            $privateType = Inbox::ENUM_NOT_PRIVATE;
            if($type != Comment::ENUM_OBJ_TYPE_TOPIC){
                if($type == Comment::ENUM_COMMENT_POST_TYPE){
                    //是不是匿名楼主回复的消息盒子
                    if(isset($objData['obj'])){
                        $obj = $objData['obj'];
                        if(isset($obj[Post::FIELD_PRIVATE])){
                            if($obj->{Post::FIELD_PRIVATE} == Post::ENUM_PRIVATE && $obj->{Post::FIELD_ID_POSTER} == $commenterId){
                                $privateType = Inbox::ENUM_PRIVATE;
                            }
                        }
                    }
                }
                $this->inbox->send($fromId,$toId,$result->id,$content,$objType,$actionType,$postAt,$privateType);
            }

            $this->comment->incrementComment($type,$objId);

            \DB::commit();
        }catch (Exception $e){

            \DB::rollBack();
            throw new ApiException($e,60001);
        }

        return $this->comment->formatSingleComments($result,$user,$objData['obj']);
    }

    /**
     * 删除评论
     *
     * @author yezi
     *
     * @param $id
     * @return mixed
     * @throws ApiException
     */
    public function delete($id)
    {
        $user = request()->input('user');

        if(empty($id)){
            throw new ApiException('404',6000);
        }

        $result = Comment::where(Comment::FIELD_ID,$id)->delete();
        return $result;
    }

}