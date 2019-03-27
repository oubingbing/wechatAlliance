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
        $iv            = request()->input('iv');
        $code          = request()->input('code');
        $encryptedData = request()->input('encrypted_data');
        $appId         = request()->input("app_id");

        try{
            DB::beginTransaction();

            $result = $this->wechatLogin($appId,$code,$iv,$encryptedData);

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
    public function weChatLogin($appId,$code,$iv,$encryptedData)
    {
        $weChatApp = WechatApp::query()->where(WechatApp::FIELD_ALLIANCE_KEY,$appId)->first();
        if(!$weChatApp){
            throw new ApiException('不是有效的key',6000);
        }
        $userInfo = app(WeChatService::class)->getSessionInfo($weChatApp,$code,$iv,$encryptedData);
        $token    = $this->tokenService->createApiToken($weChatApp->id,$userInfo);

        return $token;
    }

}