<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/19 0019
 * Time: 17:28
 */

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Http\Service\TokenService;
use App\Http\Service\WeChatService;
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
     *
     * @return mixed
     */
    public function apiLogin()
    {
        $iv = request()->input('iv');
        $code = request()->input('code');
        $encryptedData = request()->input('encrypted_data');

        try{
            DB::beginTransaction();

            $result = $this->wechatLogin($code,$iv,$encryptedData);

            DB::commit();
        }catch (\Exception $e){
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
    public function weChatLogin($code,$iv,$encryptedData)
    {
        $userInfo = app(WeChatService::class)->getSessionInfo($code,$iv,$encryptedData);
        $token = $this->tokenService->createApiToken($userInfo);
        return $token;
    }

}