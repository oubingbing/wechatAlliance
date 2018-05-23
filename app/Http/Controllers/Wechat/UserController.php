<?php

namespace App\Http\Wechat;


use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Models\Colleges;
use App\Models\User;

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

        $college = $user->college;

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

}