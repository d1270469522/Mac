<?php

/**
 * @description        : 首页
 *
 * @Author             : 天尽头流浪
 * @E-mail             : 1270469522@qq.com
 * @Date               : 2019-07-05 16:42:55
 * @Last Modified by   : 天尽头流浪
 */

namespace App\Http\Controllers;

use \App\Models\LogAdminLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class IndexController extends Controller
{

    /**
     * [index 首页]
     * @return [html] [页面]
     */
    public function index()
    {
        return view('index/index');
    }

    /**
     * [indexShow 首页展示的内容]
     * @return [type] [description]
     */
    public function indexShow ()
    {
        $paramArr = [
            'page'  => 1,
            'limit' => 10,
        ];
        $login_res = LogAdminLogin::getAllWithParam($paramArr, ['id' => 'desc']);
        return view('index/indexShow')->with([
            'login_res' => $login_res
        ]);
    }


    /**
     * [switchLocale 选择语言]
     * @param  Request $request [description]
     * @param  string  $locale  [语言参数]
     * @return [URL]            [跳转页面]
     */
    public function switchLocale(Request $request, $locale = 'zh')
    {
        Session::put('locale', $locale);

        return redirect(URL::previous());
    }

}
