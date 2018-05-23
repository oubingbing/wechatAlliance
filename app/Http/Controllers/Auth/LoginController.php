<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    protected $weChatLoginUrl = "https://api.weixin.qq.com/sns/jscode2session";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function Login()
    {
        $type = request()->input('type');

        if($type == 'weChat'){
            return $this->wechatLogin();
        }

    }

    public function weChatLogin()
    {
        $code = request()->input('code');

        $appId = env('WE_CHAT_APP_ID');
        $secret = env('WE_CHAT_SECRET');

        $url = $this->weChatLoginUrl.'?appid='.$appId.'&secret='.$secret.'&js_code='.$code.'&grant_type=authorization_code';

        $http = new Client;
        $response = $http->get($url);

        $result = json_decode((string) $response->getBody(), true);

        return $result['openid'];
    }
}
