<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/11/17
 * Time: 下午4:37
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
use App\Models\Praise;
use App\Models\SaleFriend;
use App\Models\User;

class PraiseService
{

    /**
     * 点赞
     *
     * @author yezi
     *
     * @param $ownerId
     * @param $objId
     * @param $objType
     * @param null $collegeId
     * @return mixed
     */
    public function createPraise($ownerId, $objId, $objType, $collegeId = null)
    {
        $praise = Praise::create([
            Praise::FIELD_ID_OWNER   => $ownerId,
            Praise::FIELD_ID_OBJ     => $objId,
            Praise::FIELD_OBJ_TYPE   => $objType,
            Praise::FIELD_ID_COLLEGE => $collegeId
        ]);

        return $praise;
    }

    /**
     * 添加点赞数
     *
     * @author yezi
     *
     * @param $type
     * @param $objId
     */
    public function incrementNumber($type, $objId)
    {
        switch ($type) {
            case Praise::ENUM_OBJ_TYPE_POST:
                Post::query()->where(Post::FIELD_ID, $objId)->increment(Post::FIELD_PRAISE_NUMBER);
                break;
            case Praise::ENUM_OBJ_TYPE_SALE_FRIEND:
                SaleFriend::where(SaleFriend::FIELD_ID, $objId)->increment(SaleFriend::FIELD_PRAISE_NUMBER);
                break;
            case Praise::ENUM_OBJ_TYPE_MATCH_LOVE:
                MatchLove::where(MatchLove::FIELD_ID, $objId)->increment(MatchLove::FIELD_PRAISE_NUMBER);
                break;
        }

    }

    /**
     * 获取点赞
     *
     * @author yezi
     *
     * @param $objId
     * @param $objType
     * @return mixed
     */
    public function praise($objId, $objType)
    {
        $praise = Praise::where(Praise::FIELD_ID_OBJ, $objId)
            ->where(Praise::FIELD_OBJ_TYPE, $objType)
            ->get();

        return $praise;
    }

    /**
     * 批量格式化点赞
     *
     * @author yezi
     *
     * @param $praises
     *
     * @return static
     */
    public function formatBatchPraise($praises)
    {
        $result = collect($praises)->map(function ($item) {

            return $this->formatSinglePraise($item);

        });

        return $result;
    }

    /**
     * 格式化点赞返回的格式
     *
     * @author yeiz
     *
     * @param $praise
     * @return array
     */
    public function formatSinglePraise($praise)
    {
        $praiseUser = User::find($praise['owner_id']);

        return [
            'id'         => $praise['id'],
            'owner_id'   => $praise[ Praise::FIELD_ID_OWNER ],
            'obj_type'   => $praise[ Praise::FIELD_OBJ_TYPE ],
            'college_id' => $praise[ Praise::FIELD_ID_COLLEGE ],
            'user_id'    => $praiseUser->id,
            'nickname'   => $praiseUser->{User::FIELD_NICKNAME},
            'avatar'     => $praiseUser->{User::FIELD_AVATAR}
        ];
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
            case Praise::ENUM_OBJ_TYPE_POST:
                $obj    = Post::find($objId);
                $userId = $obj->{Post::FIELD_ID_POSTER};
                break;
            case Praise::ENUM_OBJ_TYPE_SALE_FRIEND:
                $obj    = SaleFriend::find($objId);
                $userId = $obj->{SaleFriend::FIELD_ID_OWNER};
                break;
            case Praise::ENUM_OBJ_TYPE_MATCH_LOVE:
                $obj    = MatchLove::find($objId);
                $userId = $obj->{MatchLove::FIELD_ID_OWNER};
                break;
            case  Praise::ENUM_OBJ_TYPE_COMMENT:
                $obj    = Comment::find($objId);
                $userId = $obj->{Comment::FIELD_ID_COMMENTER};
                break;
        }

        return $userId;
    }

    /**
     * 检测重复
     *
     * @author yezi
     *
     * @param $userId
     * @param $objId
     * @param $type
     *
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function checkRepeat($userId, $objId, $type)
    {
        $result = Praise::query()
            ->where(Praise::FIELD_ID_OWNER, $userId)
            ->where(Praise::FIELD_ID_OBJ, $objId)
            ->where(Praise::FIELD_OBJ_TYPE, $type)
            ->first();

        return $result;
    }

}