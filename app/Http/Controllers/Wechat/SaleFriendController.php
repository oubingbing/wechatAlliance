<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/11/27
 * Time: 上午11:17
 */

namespace App\Http\Controllers\Wechat;


use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\AppService;
use App\Http\Service\PaginateService;
use App\Http\Service\SaleFriendService;
use App\Http\Service\UserService;
use App\Models\SaleFriend;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\WechatApp;

class SaleFriendController extends Controller
{
    protected $saleFriendLogic;

    public function __construct(SaleFriendService $saleFriendLogic)
    {
        $this->saleFriendLogic = $saleFriendLogic;
    }

    /**
     * 新增
     *
     * @author yezi
     *
     * @param Request $request
     * @return mixed
     * @throws ApiException
     */
    public function save(Request $request)
    {
        $user        = request()->input('user');
        $name        = request()->input('name');
        $gender      = request()->input('gender');
        $major       = request()->input('major');
        $expectation = request()->input('expectation');
        $introduce   = request()->input('introduce');
        $attachments = request()->input('attachments');

        $rule = [
            'name'        => 'required',
            //'gender'      => 'required',
            //'major'       => 'required',
            //'expectation' => 'required',
            'introduce'   => 'required'
        ];

        $messages = [
            'name.required'        => '名字不能为空',
            //'gender.required'      => '性别不能为空',
            //'major.required'       => '专业不能为空',
            //'Expectation.required' => '期望不能为空',
            'introduce.required'   => '介绍不能为空',
        ];

        if(empty($gender)){
            $gender = 1;
        }

        if(empty($major)){
            $major = "无";
        }

        if(empty($expectation)){
            $expectation = "无";
        }

        $validator = \Validator::make(request()->input(), $rule,$messages);
        if ($validator->fails()) {
            $messages = $validator->errors();
            throw new ApiException($messages->first(), 60001);
        }


        $app = app(AppService::class)->getById($user->{User::FIELD_ID_APP});
        if(!$app){
            return webResponse('应用不存在！',500);
        }

        if($app->{WechatApp::FIELD_STATUS} == WechatApp::ENUM_STATUS_TO_BE_AUDIT){
            if(!empty($expectation)){
                app(AppService::class)->checkContent($user->{User::FIELD_ID_APP},$expectation);
            }
            if(!empty($major)){
                app(AppService::class)->checkContent($user->{User::FIELD_ID_APP},$major);
            }
            if(!empty($name)){
                app(AppService::class)->checkContent($user->{User::FIELD_ID_APP},$name);
            }
            if(!empty($introduce)){
                app(AppService::class)->checkContent($user->{User::FIELD_ID_APP},$introduce);
            }
    
            if(!empty($attachments)){
                app(AppService::class)->checkImage($user->{User::FIELD_ID_APP},$attachments);
            }
        }

        $qiNiuDomain = env('QI_NIU_DOMAIN');
        foreach ($attachments as &$attachment){
            $imageInfo  = getimagesize($qiNiuDomain.'/'.$attachment);
            $attachment = [
                'url'   => $attachment,
                'width' => $imageInfo[0],
                'height'=> $imageInfo[1]
            ];
        }

        $result = $this->saleFriendLogic->save($user->id,$name,$gender,$major,$expectation,$introduce,$attachments,$user->{User::FIELD_ID_COLLEGE});

        app(UserService::class)->updatePostNum($user->id);

        return $result;
    }

    /**
     * 获取
     *
     * @author yezi
     *
     * @return mixed
     */
    public function saleFriends()
    {
        $user       = request()->input('user');
        $pageSize   = request()->input('page_size',10);
        $pageNumber = request()->input('page_number',1);
        $type       = request()->input('type');
        $just       = request()->input('just');
        $orderBy    = request()->input('order_by','created_at');
        $sortBy     = request()->input('sort_by','desc');

        $pageParams = ['page_size'=>$pageSize, 'page_number'=>$pageNumber];
        $query      = $this->saleFriendLogic->builder($user,$type,$just)->sort($orderBy,$sortBy)->done();

        $saleFriends     = app(PaginateService::class)->paginate($query,$pageParams, '*',function($saleFriend)use($user){
            $attachments = $this->saleFriendLogic->convertAttachments($saleFriend->{SaleFriend::FIELD_ATTACHMENTS});
            $saleFriend->{SaleFriend::FIELD_ATTACHMENTS} = $attachments;
            $saleFriend->can_delete                      = $this->saleFriendLogic->canDeleteSaleFriend($saleFriend, $user);
            return $saleFriend;
        });

        return $saleFriends;
    }

    /**
     * 获取
     *
     * @author yezi
     *
     * @return mixed
     */
    public function saleFriendsV2()
    {
        $user       = request()->input('user');
        $pageSize   = request()->input('page_size',10);
        $pageNumber = request()->input('page_number',1);
        $type       = request()->input('type');
        $just       = request()->input('just');
        $orderBy    = request()->input('order_by','created_at');
        $sortBy     = request()->input('sort_by','desc');
        $userId     = request()->input('user_id',0);

        $pageParams = ['page_size'=>$pageSize, 'page_number'=>$pageNumber];
        $query      = $this->saleFriendLogic->builder($user,$userId,$type,$just)->sort($orderBy,$sortBy)->done();

        $selectData = [
            SaleFriend::FIELD_ID,
            SaleFriend::FIELD_ATTACHMENTS,
            SaleFriend::FIELD_ID_OWNER,
            SaleFriend::FIELD_COMMENT_NUMBER,
            SaleFriend::FIELD_INTRODUCE
        ];

        $qiNiuDomain = env('QI_NIU_DOMAIN');
        $saleFriends = paginate($query,$pageParams, $selectData,function($saleFriend)use($user,$qiNiuDomain){
            if(!is_array($saleFriend->{SaleFriend::FIELD_ATTACHMENTS}[0])){
                $saleFriend->{SaleFriend::FIELD_ATTACHMENTS} = collect($saleFriend->{SaleFriend::FIELD_ATTACHMENTS})->map(function ($item)use($qiNiuDomain){
                    $imageInfo = getimagesize($qiNiuDomain.'/'.$item);
                    if($imageInfo){
                        return [
                            'url'    => $item,
                            'width'  => $imageInfo[0],
                            'height' => $imageInfo[1]
                        ];
                    }else{
                        return [];
                    }
                })->toArray();
                $saleFriend->save();
            }

            $saleFriend->can_delete = $this->saleFriendLogic->canDeleteSaleFriend($saleFriend, $user);
            return $saleFriend;
        });

        return $saleFriends;
    }

    public function mostNewSaleFriends()
    {
        $user  = request()->input('user');
        $time  = request()->input('time');

        $query = SaleFriend::query()
            ->whereHas(SaleFriend::REL_USER,function ($query)use($user){
                $query->where(User::FIELD_ID_APP,$user->{User::FIELD_ID_APP});
            })
            ->with(['poster','comments'])
            ->where(SaleFriend::FIELD_CREATED_AT,'>=',$time)
            ->orderBy(SaleFriend::FIELD_CREATED_AT,'desc');
        if($user->{User::FIELD_ID_COLLEGE}){
            $query->where(SaleFriend::FIELD_ID_COLLEGE,$user->{User::FIELD_ID_COLLEGE});
        }

        $result = $query->get();
        $result = collect($result)->map(function ($item)use($user){
            return $this->saleFriendLogic->formatSingle($item,$user);
        });

        return $result;
    }

    /**
     * 详情
     *
     * @author yezi
     *
     * @param $id
     * @return mixed
     */
    public function detail($id)
    {
        $user       = request()->input('user');

        $saleFriend = SaleFriend::query()->with(['comments'])->find($id);

        if($saleFriend){
            return app(SaleFriendService::class)->formatSingle($saleFriend,$user);
        }else{
            return $saleFriend;
        }
    }

    /**
     * 删除
     *
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $user   = request()->input('user');

        $result = SaleFriend::where(SaleFriend::FIELD_ID,$id)->delete();

        app(UserService::class)->updatePostNum($user->id);

        return $result;
    }

}