<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/10 0010
 * Time: 15:49
 */

namespace App\Http\Service;

use App\Exceptions\WebException;
use App\Models\Admin as Model;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $valid = $this->validRegister($request);
        if(!$valid['valid']){
            return webResponse($valid['message'],500);
        }

        try{
            \DB::beginTransaction();

            $result = $this->createAdmin($request->input('username'),$request->input('email'),$request->input('password'));

            \DB::commit();
        }catch (Exception $exception){
            \DB::rollBack();
            throw new WebException($exception);
        }

        if($result){
            $content = "您好，请点击链接激活您的小情书账号,".env('APP_URL')."/active?token={$result->{Model::FIELD_ACTIVE_TOKEN}}";
            app(EmailService::class)->sendRegisterEmail($request->input('email'),$content);
        }

        return webResponse('注册成功,请登录您的邮箱激活账号',201,$result);
    }

    public function createAdmin($username,$email,$password)
    {
        $admin = Model::create([
            Model::FIELD_USER_NAME    => $username,
            Model::FIELD_EMAIL        => $email,
            Model::FIELD_PASSWORD     => bcrypt($password),
            Model::FIELD_AVATAR       =>[Model::USER_AVATAR],
            Model::FIELD_ACTIVE_TOKEN => str_random('18'),
            Model::FIELD_TOKEN_EXPIRE => Carbon::now()->addMonth()
        ]);

        return $admin;
    }

    public function validRegister($request)
    {
        $rules = [
            'username' => 'required|min:2|max:16',
            'email'    => 'required|email|unique:admins',
            'password' => 'required|min:6|max:225'
        ];
        $message = [
            'username.required'     => '用户名不能为空！',
            'username.min'          => '用户名必须是2~16个字符！',
            'username.max'          => '用户名必须是2~16个字符！',
            'email.required'        => '邮箱不能为空！',
            'email.email'           => '邮箱格式不正确',
            'password.required'     => '密码不能为空！',
            'password.min'          => '密码必须是6~16个字符！',
            'password.max'          => '密码必须是6~16个字符！',
            'password_confirmation' => '两次输入密码不一致！',
            'email.unique'          => '邮箱已被注册！'
        ];
        $validator = \Validator::make($request->all(),$rules,$message);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return ['valid'=>false,'message'=>$errors->first()];
        }else{
            return ['valid'=>true,'message'=>'success'];
        }
    }

    public function getAdminByEmail($email)
    {
        $result = Model::query()->where(Model::FIELD_EMAIL,$email)->first();
        return $result;
    }

    public function attempt($email,$password)
    {
        $admin = $this->getAdminByEmail($email);
        if(!$admin){
            throw new WebException("用户不存在");
        }

        if(!Hash::check($password, $admin->{Model::FIELD_PASSWORD})){
            return false;
        }else{
            session(['admin_id' => $admin->id,'email'=>$admin->{Model::FIELD_EMAIL}]);
        }

        return true;
    }

    public function getAdminById($id)
    {
        $result = Model::query()->where(Model::FIELD_ID,$id)->first();
        return $result;
    }

    /**
     * 判断用户是否登录
     *
     * @author yezi
     * @return bool
     */
    public static function auth()
    {
        if(session()->has("admin_id")){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 获取认证用户
     *
     * @author yezi
     * @return \Illuminate\Session\SessionManager|\Illuminate\Session\Store|mixed
     */
    public static function authUser()
    {
        return session("admin_id");
    }

    /**
     * 退出登录
     *
     * @author yezi
     */
    public function clearAdmin()
    {
        session()->forget('admin_id');
    }

}