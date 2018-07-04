<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\TokenService;
use App\Models\WechatApp;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    //protected $redirectTo = '/admin';
    protected $weChatLoginUrl = "https://api.weixin.qq.com/sns/jscode2session";
    protected $tokenService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->tokenService = app(TokenService::class);
    }

    /**
     * 默认是admin guard
     *
     * @return \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard|mixed
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }

    /**
     * 登录
     *
     * @author yezi
     *
     * @return mixed
     */
    public function apiLogin()
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

        if($weChatApp->{WechatApp::FIELD_STATUS} === WechatApp::ENUM_STATUS_TO_BE_AUDIT){
            throw new ApiException('小程序处于审核中，无法使用后台服务！',6001);
        }

        $weChatAppId = $weChatApp->{WechatApp::FIELD_APP_KEY};
        $secret = $weChatApp->{WechatApp::FIELD_APP_SECRET};

        $url = $this->weChatLoginUrl.'?appid='.$weChatAppId.'&secret='.$secret.'&js_code='.$code.'&grant_type=authorization_code';

        $http = new Client;
        $response = $http->get($url);

        $result = json_decode((string) $response->getBody(), true);
        if(!isset($result['openid'])){
            throw new ApiException('小程序登录失败，请检查您的app_id和app_secret是否正确！',5000);
        }

        $token = $this->tokenService->createToken($userInfo,$result['openid'],$weChatApp->{WechatApp::FIELD_ID});

        return $token;
    }

}
