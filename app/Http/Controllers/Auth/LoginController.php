<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\TokenService;
use App\Models\User;
use App\Models\WechatApp;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

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
    protected $tokenService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        Log::info('登录构造器');
        $this->middleware('guest')->except('logout');
        $this->tokenService = app(TokenService::class);
    }

    /**
     * 登录
     *
     * @author yezi
     *
     * @return mixed
     */
    public function Login()
    {
        $type = request()->input('type');
        $code = request()->input('code');
        $userInfo = request()->input('user_info');
        $appId = request()->input('app_id');

        try{
            DB::beginTransaction();

            if($type == 'weChat'){
                $result = $this->wechatLogin($userInfo,$code,$appId);
            }

            DB::commit();
        }catch (Exception $e){
            DB::rollBack();
            throw $e;
        }

        return $result;
    }

    /**
     * 微信登录
     *
     * @author yezi
     *
     * @return mixed
     */
    public function weChatLogin($userInfo,$code,$appId)
    {
        $weChatApp = WechatApp::query()->where(WechatApp::FIELD_ALLIANCE_KEY,$appId)->first();
        if(!$weChatApp){
            throw new ApiException('不是有效的key',6000);
        }

        $weChatAppId = $weChatApp->{WechatApp::FIELD_APP_KEY};
        $secret = $weChatApp->{WechatApp::FIELD_APP_SECRET};

        $url = $this->weChatLoginUrl.'?appid='.$weChatAppId.'&secret='.$secret.'&js_code='.$code.'&grant_type=authorization_code';

        $http = new Client;
        $response = $http->get($url);

        $result = json_decode((string) $response->getBody(), true);

        $token = $this->tokenService->createToken($userInfo,$result['openid'],$weChatApp->{WechatApp::FIELD_ID});

        return $token;
    }

}
