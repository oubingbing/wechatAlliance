<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\WebException;
use App\Http\Service\EmailService;
use App\Models\Admin;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RegisterController extends Controller
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
        }catch (\Exception $exception){
            \DB::rollBack();
            throw new WebException($exception);
        }

        /*if($result){
            $content = "您好，请点击链接激活您的小情书账号,".env('APP_URL')."/active?token={$result->{Admin::FIELD_ACTIVE_TOKEN}}";
            app(EmailService::class)->sendRegisterEmail($request->input('email'),$content);
        }*/

        return webResponse('注册成功',201,$result);
    }

    public function createAdmin($username,$email,$password)
    {
        $admin = Admin::create([
            Admin::FIELD_USER_NAME      => $username,
            Admin::FIELD_EMAIL          => $email,
            Admin::FIELD_PASSWORD       => bcrypt($password),
            Admin::FIELD_AVATAR         => [Admin::USER_AVATAR],
            Admin::FIELD_ACTIVE_TOKEN   => str_random('18'),
            Admin::FIELD_TOKEN_EXPIRE   => Carbon::now()->addMonth()
        ]);

        return $admin;
    }

    /**
     * 验证登录信息
     *
     * @author 叶子
     * @param $request
     * @return array
     */
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
            'email.unique'=>'邮箱已被注册！'
        ];
        $validator = \Validator::make($request->all(),$rules,$message);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return ['valid'=>false,'message'=>$errors->first()];
        }else{
            return ['valid'=>true,'message'=>'success'];
        }
    }

    /**
     * 激活账号
     *
     * @author yezi
     *
     * @return obj
     */
    public function active()
    {
        $token = request()->input('token');

        if(!$token){
            abort(404);
        }

        $result = Admin::query()
            ->where(Admin::FIELD_ACTIVE_TOKEN,$token)
            ->where(Admin::FIELD_STATUS,Admin::ENUM_STATUS_SLEEP)
            ->first();
        if(!$result){
            abort('404');
        }

        if(Carbon::now()->gt(Carbon::parse($result->{Admin::FIELD_TOKEN_EXPIRE}))){
            return redirect('login');
        }else{
            $result->{Admin::FIELD_STATUS}       = Admin::ENUM_STATUS_ACTIVATED;
            $result->{Admin::FIELD_TOKEN_EXPIRE} = Carbon::now();
            $result->save();
            return view('auth.active');
        }
    }

    public function registerView()
    {
        return view('auth.register');
    }
}
