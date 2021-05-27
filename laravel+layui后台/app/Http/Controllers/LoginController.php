<?php

/**
 * @description        : 登陆
 *
 * @Author             : 天尽头流浪
 * @E-mail             : 1270469522@qq.com
 * @Date               : 2019-07-05 16:40:07
 * @Last Modified by   : 天尽头流浪
 */

namespace App\Http\Controllers;

use App\Consts\Common;
use App\Service\UserService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    /**
     * [showLoginForm 登陆首页，展示html页面]
     * @return [type] [description]
     */
    public function login()
    {
        return view('Login/login');
    }

    /**
     * [login 获取页面信息，执行登陆操作]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function loginPro(Request $request)
    {
        //来自 AJAX 请求
        if (request()->ajax()) {

            $data = json_decode($request->post('paramJson'), true);

            //登陆IP
            $paramArr = $data;
            $paramArr['login_ip'] = $request->getClientIp();

            //验证账号密码是否正确
            $user = UserService::attemptLogin($paramArr);

            //验证登陆 - 成功
            if (isset($user['id'])) {

                //验证成功，登陆
                Auth::loginUsingId($user['id']);

                return formatReturn(Common::SUCCESS, __('common.success'));
            }
        }

        return formatReturn();
    }

    /**
     * [logout 退出登陆]
     * @return [type] [description]
     */
    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }
}
