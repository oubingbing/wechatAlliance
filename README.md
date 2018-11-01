小情书是一个开源项目，你可以使用叶子的后台服务，也可以自己搭建后台服务。本文档为独立部署小情书后台服务的教程。

### 项目环境要求

    PHP 7.0以上
    
    MySQL 5.7

### 一、下载后台源码

##### 1.1直接下载后台源码

github源码地址：https://github.com/oubingbing/wechatAlliance.git

直接点击download下载源码即可

## 别忘了点star哈
<img src="http://article.qiuhuiyi.cn/Fl9uqGovXBsPWr7LoianLXAjJS3w" alt="Build Status">

##### 1.2使用git获取源码

在Git输入命令

    git clone https://github.com/oubingbing/wechatAlliance.git

二、安装PHP包管理工具composer

安装 - Windows

使用安装程序
这是将 Composer 安装在你机器上的最简单的方法。

下载并且运行 Composer-Setup.exe，它将安装最新版本的 Composer ，并设置好系统的环境变量，因此你可以在任何目录下直接使用 composer 命令。

composer下载地址：https://getcomposer.org/Composer-Setup.exe

直接下载运行即可。

安装完成后在终端输入

composer -v看到下图就说明安装成功了

<img src="http://article.qiuhuiyi.cn/FlPyixDSp7YQPNRHwi8mrpm7gIiX" alt="Build Status">

### 三、安装PHP的laravel框架

php的laravel框架是一款非常优秀的php框架，如果有兴趣的可以学一下。

这是中文文档的网站：https://laravelacademy.org/

源码下载后进入项目根目录使用cmd命令行工具或者其他的终端工具都是可以，数据命令:

    composer install

安装laravel框架
<img src="http://article.qiuhuiyi.cn/Fgau-lnInun7-SdCP330bCrIe-xG" alt="Build Status">

等他安装完成就可以了。

安装完成后输入

    composer dump-autoload

### 四、配置项目

将项目根目录下的.env.example文件重命名为 **.env** 文件

    APP_NAME=小情书
    APP_ENV=local(开发的时候是local,部署的时候改为prod)
    APP_KEY=(laravel 的secret key) 
    APP_DEBUG=true(开发的时候是false,部署的时候改为true)
    APP_LOG_LEVEL=debug
    APP_LOG=daily
    APP_URL=https://kucaroom.com
    
    DB_CONNECTION=mysql
    DB_HOST=(数据库所在的主机IP地址)
    DB_PORT=3306
    DB_DATABASE=
    DB_USERNAME=
    DB_PASSWORD=
    
    BROADCAST_DRIVER=log
    CACHE_DRIVER=file
    SESSION_DRIVER=file
    SESSION_LIFETIME=120
    QUEUE_DRIVER=sync
    
    REDIS_HOST=
    REDIS_PASSWORD=
    REDIS_PORT=6379
    
    MAIL_DRIVER=smtp
    MAIL_HOST=smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=null
    MAIL_PASSWORD=null
    MAIL_ENCRYPTION=null
    
    PUSHER_APP_ID=
    PUSHER_APP_KEY=
    PUSHER_APP_SECRET=
    
    API_PREFIX=api
    JWT_SECRET=
    
    QI_NIU_ACCESS_KEY=
    QI_NIU_SECRET_KEY=
    BUCKET_NAME=
    
    SEND_CLOUD_API_USER=
    SEND_CLOUD_APP_KEY=
    
    YUN_PIAN_SINGLE_URL=
    YUN_PIAN_MULTI=
    YUN_PIAN_KEY=
    
    ALI_ID=
    ALI_SECRET=
    ALI_URL=


##### 1、生成app_key 
终端输入：php artisan key:generate
然后会显示一下信息，复制 [] 中括号的字符串贴到.env的APP_KEY就行了
Application key [base64:3ZYAJ6R5fzNcQpc1kfEuhMQJZU06HUXt93BS92UK8Pc=] set successfully.

##### 2、APP_URL=https://kucaroom.com，应用的域名，发邮箱激活链接用的，填上你的项目域名即可

##### 3、数据库，输入你数据对应的信息即可

        DB_CONNECTION=mysql
        DB_HOST=(数据库所在的主机IP地址)
        DB_PORT=3306
        DB_DATABASE=
        DB_USERNAME=
        DB_PASSWORD=

##### 4、redis缓存，配置你的Redis账号密码

        REDIS_HOST=
        REDIS_PASSWORD=
        REDIS_PORT=6379

##### 5、生成jwt key，用dingo api

输入命令：php artisan jwt:secret

结果如下，复制中括号的字符串到    JWT_SECRET= 即可

    jwt-auth secret [pV7G5egB2TfcLwpc3J8xEqiudof5SxyM] set successfully.

##### 6、七牛配置

在七牛注册一个账号，获取到七牛的access_key，和secret_key以及存储桶的名字填到下面就行了，用于小程序的图片上传，存储区域最好选择华南区也就是 Z2。

        QI_NIU_ACCESS_KEY=
        QI_NIU_SECRET_KEY=
        BUCKET_NAME=

##### 7、sendcloud邮件配置

注册一个sendcloud账号，配置下面的信息

        SEND_CLOUD_API_USER=
        SEND_CLOUD_APP_KEY=


##### 8、配置云片短信

用于短信的发送

        YUN_PIAN_SINGLE_URL=
        YUN_PIAN_MULTI=
        YUN_PIAN_KEY=

短信验证码的文字信息在
    wechatAlliance\app\Http\Service\YunPianService.php中的sendMessageCode($phone)修改。

表白帖子的短信文本信息在
    wechatAlliance\app\Http\Controllers\Wechat\PostController.php中的store()修改

需要你在云片备案相关的短信模板，根据自己的需求，填入模板的信息。

##### 9、配置阿里云开发者信息

    ALI_ID=
    ALI_SECRET=
    ALI_URL=

阿里人脸识别地址：

    https://data.aliyun.com/product/face?spm=5176.10609282.1146454.885.21d538010MzGRj#face-verify


### 五、生成数据库表

在项目根目录运行

    php artisan migrate

进行数据表迁移，MySQL一定要5.7以上，否则会报错

数据表文件在这个目录下：**wechatAlliance\database\migrations**

### 六、修改框架源码

#### 1、修改AuthenticatesUsers代码
文件路径：
##### wechatAlliance\vendor\laravel\framework\src\Illuminate\Foundation\Auth\AuthenticatesUsers.php

整个文件的代码用下面的替换掉
    
    <?php
    
    namespace Illuminate\Foundation\Auth;
    
    use App\Models\Admin;
    use App\Models\AdminApps;
    use App\Models\WechatApp;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Validation\ValidationException;
    
    trait AuthenticatesUsers
    {
        use RedirectsUsers, ThrottlesLogins;
    
        /**
         * Show the application's login form.
         *
         * @return \Illuminate\Http\Response
         */
        public function showLoginForm()
        {
            return view('auth.login');
        }
    
        protected function redirectTo()
        {
            return '/admin';
        }
    
        /**
         * Handle a login request to the application.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
         */
        public function login(Request $request)
        {
            $email = $request->input('email');
            $password = $request->input('password');
    
            $status = Admin::query()
                ->where(Admin::FIELD_EMAIL,$email)
                ->value(Admin::FIELD_STATUS);
            if($status === Admin::ENUM_STATUS_SLEEP){
                return webResponse('账号未激活，请先登录邮箱激活您的账号！',404);
            }
    
            if(Auth::guard('admin')->attempt(['email' => $email, 'password' => $password])){
                $user = Auth::guard('admin')->user();
                if($user->{Admin::FIELD_STATUS} === Admin::ENUM_STATUS_SLEEP){
                    //账号未激活
                    return webResponse('账号未激活，请登录邮箱激活账号！',200,asset('login'));
                }
    
                $app = AdminApps::query()->where(AdminApps::FIELD_ID_ADMIN,Auth::guard('admin')->user()->id)->first();
                if(!$app){
                    //新建APP
                    return webResponse('登录成功,跳转中...！',200,asset("admin/create_app"));
                }else{
                    //直接去到后台管理页面
                    return webResponse('登录成功,跳转中...！',200,asset('/admin'));
                }
            }
    
            return webResponse('邮箱或密码不正确！',404);
        }
    
        /**
         * Validate the user login request.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return void
         */
        protected function validateLogin(Request $request)
        {
            $this->validate($request, [
                $this->username() => 'required|string',
                'password' => 'required|string',
            ]);
        }
    
        /**
         * Attempt to log the user into the application.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return bool
         */
        protected function attemptLogin(Request $request)
        {
            return $this->guard()->attempt(
                $this->credentials($request), $request->filled('remember')
            );
        }
    
        /**
         * Get the needed authorization credentials from the request.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return array
         */
        protected function credentials(Request $request)
        {
            return $request->only($this->username(), 'password');
        }
    
        /**
         * Send the response after the user was authenticated.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        protected function sendLoginResponse(Request $request)
        {
            $request->session()->regenerate();
    
            $this->clearLoginAttempts($request);
    
            return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
        }
    
        /**
         * The user has been authenticated.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  mixed  $user
         * @return mixed
         */
        protected function authenticated(Request $request, $user)
        {
            //
        }
    
        /**
         * Get the failed login response instance.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Symfony\Component\HttpFoundation\Response
         *
         * @throws ValidationException
         */
        protected function sendFailedLoginResponse(Request $request)
        {
            throw ValidationException::withMessages([
                $this->username() => [trans('auth.failed')],
            ]);
        }
    
        /**
         * Get the login username to be used by the controller.
         *
         * @return string
         */
        public function username()
        {
            return 'email';
        }
    
        /**
         * Log the user out of the application.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function logout(Request $request)
        {
            $this->guard()->logout();
    
            $request->session()->invalidate();
    
            return redirect('/login');
        }
    }
    
    

#### 2、修改RegistersUsers文件代码

文件所在路径：

**wechatAlliance\vendor\laravel\framework\src\Illuminate\Foundation\Auth\RegistersUsers.php**

整个文件替换下面的代码

    
    <?php
    
    namespace Illuminate\Foundation\Auth;
    
    use App\Exceptions\ApiException;
    use App\Http\Service\EmailService;
    use App\Models\Admin;
    use Exception;
    use Illuminate\Http\Request;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\Auth;
    
    trait RegistersUsers
    {
        use RedirectsUsers;
    
        /**
         * Show the application registration form.
         *
         * @return \Illuminate\Http\Response
         */
        public function showRegistrationForm()
        {
            return view('auth.register',['title'=>'注册']);
        }
    
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
                throw new ApiException($exception);
            }
    
            if($result){
    			$content = "您好，请点击链接激活您的小情书账号,".env('APP_URL')."/active?token={$result->{Admin::FIELD_ACTIVE_TOKEN}}";
                app(EmailService::class)->sendRegisterEmail($request->input('email'),$content);
            }
    
            return webResponse('注册成功,请登录您的邮箱激活账号',201,$result);
        }
    
        public function createAdmin($username,$email,$password)
        {
            $admin = Admin::create([
                Admin::FIELD_USER_NAME => $username,
                Admin::FIELD_EMAIL=>$email,
                Admin::FIELD_PASSWORD=>bcrypt($password),
                Admin::FIELD_AVATAR=>[Admin::USER_AVATAR],
                Admin::FIELD_ACTIVE_TOKEN=>str_random('18'),
                Admin::FIELD_TOKEN_EXPIRE=>Carbon::now()->addMonth()
            ]);
    
            return $admin;
        }
    
        public function validRegister($request)
        {
            $rules = [
                'username' => 'required|min:2|max:16',
                'email' => 'required|email|unique:admins',
                'password' => 'required|min:6|max:225'
            ];
            $message = [
                'username.required' => '用户名不能为空！',
                'username.min' => '用户名必须是2~16个字符！',
                'username.max' => '用户名必须是2~16个字符！',
                'email.required' => '邮箱不能为空！',
                'email.email' => '邮箱格式不正确',
                'password.required' => '密码不能为空！',
                'password.min' => '密码必须是6~16个字符！',
                'password.max' => '密码必须是6~16个字符！',
                'password_confirmation'=>'两次输入密码不一致！',
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
         * Get the guard to be used during registration.
         *
         * @return \Illuminate\Contracts\Auth\StatefulGuard
         */
        protected function guard()
        {
            return Auth::guard();
        }
    
        /**
         * The user has been registered.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  mixed  $user
         * @return mixed
         */
        protected function registered(Request $request, $user)
        {
            //
        }
    }
    
    


#### 3、修改Authenticate文件的handle方法

文件路径：

**wechatAlliance\vendor\laravel\framework\src\Illuminate\Auth\Middleware\Authenticate.php**

添加以下代码：

array_push($guards,'admin');

如图所示：
<img src="http://article.qiuhuiyi.cn/Fm6Co0R8ZTqxwwuyV4mp13YYvYPI" alt="">

### 七、运行项目

在项目根目录运行

php artian serve

项目就可以跑起来的，在浏览器输入

http://127.0.0.1:8000

就可以访问项目了

## 正式部署到linux上后，只需要把80或者443端口的请求指向项目的public目录就可以了


