<?php

/**
 * @description        : 天尽头流浪
 *
 * @Author             : 天尽头流浪
 * @E-mail             : 1270469522@qq.com
 * @Date               : 2019-09-07 12:20:20
 * @Last Modified by   : 天尽头流浪
 */
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Config;

class ImageController extends Controller
{
    /**
     * [__construct 构造函数]
     */
    public function __construct ()
    {
        parent::__construct();

        Config::set('currentMenu', 'image');
    }

    /**
     * [adminUserLists 管理员 --- 渲染页面]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function imageCropp ()
    {
        return view('Image/imageCropp');
    }

    /**
     * [adminUserLists 管理员 --- 渲染页面]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function imageMagnifier ()
    {
        return view('Image/imageMagnifier');
    }
}
