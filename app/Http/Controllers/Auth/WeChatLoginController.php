<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/19 0019
 * Time: 17:28
 */

namespace App\Http\Controllers\Auth;


use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\TokenService;
use App\Http\Service\WeChatService;
use App\Models\WechatApp;
use Illuminate\Support\Facades\DB;

class WeChatLoginController extends Controller
{
    protected $tokenService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    /**
     * 登录
     *
     * @author yezi
     * @return mixed
     * @throws \Exception
     */
    public function apiLogin()
    {
        $iv = request()->input('iv');
        $code = request()->input('code');
        $encryptedData = request()->input('encrypted_data');
        $appKey = request()->input("app_id");

        try{
            DB::beginTransaction();

            $result = $this->wechatLogin($appKey,$code,$iv,$encryptedData);

            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            throw $e;
        }

        return $result;
    }

    /**
     * 登录
     *
     * @author yezi
     * @param $appKey
     * @param $code
     * @param $iv
     * @param $encryptedData
     * @return mixed
     * @throws ApiException
     */
    private function weChatLogin($appKey,$code,$iv,$encryptedData)
    {
        $weChatApp = WechatApp::query()->where(WechatApp::FIELD_ALLIANCE_KEY,$appKey)->first();
        if(!$weChatApp){
            throw new ApiException('不是有效的key',6000);
        }

        if($weChatApp->{WechatApp::FIELD_STATUS} === WechatApp::ENUM_STATUS_TO_BE_AUDIT){
            throw new ApiException('小程序处于审核中，无法使用后台服务！',6001);
        }

        $appId = $weChatApp->id;

        $userInfo = app(WeChatService::class)->getSessionInfo($code,$iv,$encryptedData);
        $token = $this->tokenService->createApiToken($appId,$userInfo);
        return $token;
    }

}