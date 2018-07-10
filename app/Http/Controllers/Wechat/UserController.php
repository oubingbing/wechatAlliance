<?php

namespace App\Http\Wechat;


use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\SendMessageService;
use App\Http\Service\UserService;
use App\Jobs\UserLogs;
use App\Models\Colleges;
use App\Models\User;
use App\Models\WechatApp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;

class UserController extends Controller
{
    /**
     * 获取用户信息
     *
     * @author yezi
     *
     * @return array|string
     */
    public function user($id)
    {
        $user = User::find($id);

        return $user;
    }

    /**
     * 获取个人信息
     *
     * @author yezi
     *
     * @return array|string
     */
    public function personal()
    {
        $user = request()->input('user');

        return $user;
    }

    /**
     * 获取用户所在的学校
     *
     * @author yei
     *
     * @return string
     */
    public function school()
    {
        $user = request()->input('user');

        //该接口已废弃，所以用来进行用户浏览记录接口
        $job = (new UserLogs($user))->delay(Carbon::now()->addSecond(1));
        dispatch($job)->onQueue('record_visit_log');

        $college = $user->{User::FIELD_ID_COLLEGE};

        return $college ? $college->{Colleges::FIELD_NAME} : '请选择学校';
    }

    /**
     * 获取推荐的学校
     *
     * @author yezi
     *
     * @return array
     */
    public function recommendSchool()
    {
        $colleges = Colleges::orderBy(\DB::raw('RAND()'))->take(15)->get(['id', 'name']);

        return collect($colleges)->toArray();
    }

    /**
     * 设置学校
     *
     * @author yezi
     *
     * @param $id
     *
     * @return array
     * @throws ApiException
     */
    public function setCollege($id)
    {
        $user = request()->input('user');

        $college = Colleges::find($id);

        if ($college) {
            $userObj                           = User::where(User::FIELD_ID_OPENID, $user->{User::FIELD_ID_OPENID})->first();
            $userObj->{User::FIELD_ID_COLLEGE} = $id;
            $userObj->save();
        } else {
            throw new ApiException('学校不存在', 5005);
        }

        return collect($userObj)->toArray();
    }

    /**
     * 搜索学校
     *
     * @author yezi
     *
     * @return array
     * @throws ApiException
     */
    public function searchCollege()
    {
        $user = request()->input('user');
        $name = request()->input('college');

        if (empty($name)) {
            throw new ApiException('内容不能为空', '50005');
        }

        $colleges = Colleges::where(Colleges::FIELD_NAME, 'like', '%' . $name . '%')->get(['id', 'name']);

        return collect($colleges)->toArray();
    }

    /**
     * 清除自己所在的学校
     *
     * @author yezi
     *
     * @return int
     */
    public function clearSchool()
    {
        $user = request()->input('user');

        $result = User::query()->where(User::FIELD_ID,$user->id)->update([User::FIELD_ID_COLLEGE=>null]);

        return $result;
    }

    /**
     * 更新用户信息
     *
     * @author yezi
     *
     * @return bool
     * @throws ApiException
     */
    public function updateUser()
    {
        $user = request()->input('user');
        $nickname = request()->input('nickname');
        $avatar = request()->input('avatar');

        if(empty($nickname)){
            throw new ApiException('昵称不能为空！', 500);
        }

        if(empty($avatar)){
            throw new ApiException('头像不能为空！', 500);
        }

        $user = User::query()->find($user->id);
        if(!$user){
            throw new ApiException('用户不存在！', 500);
        }

        $user->{User::FIELD_NICKNAME} = $nickname;
        $user->{User::FIELD_AVATAR} = $avatar;
        $result = $user->save();
        if(!$result){
            throw new ApiException('更新失败！', 500);
        }

        return $user;
    }

    /**
     * 保存用户资料
     *
     * @author yezi
     *
     * @return mixed
     * @throws ApiException
     */
    public function createProfile(\Illuminate\Http\Request $request)
    {
        $user = $request->input('user');
        $mobile = $request->input('mobile');
        $name = $request->input('username');
        $grade = $request->input('grade');
        $college = $request->input('college');
        $major = $request->input('major');
        $studentNumber = $request->input('student_number');
        $code = $request->input('code');

        app(SendMessageService::class)->validCode($code);

        $userService = app(UserService::class);
        $valid = $userService->validProfile($request);
        if(!$valid['valid']){
            throw new ApiException($valid['message'],500);
        }

        $validPhone = validMobile($mobile);
        if($validPhone != 1){
            throw new ApiException('手机号码格式错误',500);
        }

        try {
            \DB::beginTransaction();

            $updateResult = $userService->updateMobile($user->id,$mobile);
            if(!$updateResult){
                throw new ApiException('更新数据失败！',500);
            }

            $result = $userService->saveProfile($user,$name,$grade,$studentNumber,$major,$college);
            if(!$result){
                throw new ApiException('保存数据失败！',500);
            }

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            throw new ApiException($e, 60001);
        }

        return $result;
    }

    /**
     * 获取个人资料
     *
     * @author yezi
     *
     * @return mixed
     */
    public function profile()
    {
        $user = request()->input('user');

        $profile = app(UserService::class)->getProfileById($user->id);
        if($profile){
            $profile->phone = $user->{User::FIELD_MOBILE};
        }

        return $profile;
    }

    /**
     * 获取小程序二维码
     *
     * @author yezi
     *
     * @return mixed
     */
    public function qrCode()
    {
        $user = request()->input('user');

        $qrCode = WechatApp::query()->where(WechatApp::FIELD_ID,$user->{User::FIELD_ID_APP})->value(WechatApp::FIELD_ATTACHMENTS);

        return $qrCode;
    }

}