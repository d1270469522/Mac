<?php

/**
 * @description        : 管理员
 *
 * @Author             : 天尽头流浪
 * @E-mail             : 1270469522@qq.com
 * @Date               : 2019-07-27 17:10:40
 * @Last Modified by   : 天尽头流浪
 */

namespace App\Http\Controllers;

use App\Consts\Common;
use App\Models\AdminUser;
use App\Models\AdminUserRole;
use App\Service\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class AdminUserController extends Controller
{
    /**
     * [__construct 构造函数]
     */
    public function __construct ()
    {
        parent::__construct();
        static::$model = new AdminUser;

        //设置默认展开的左侧菜单
        Config::set('currentMenu', 'system');
    }

    /**
     * [adminUserLists 管理员 --- 渲染页面]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function adminUserLists ()
    {
        return view('AdminUser/adminUserLists');
    }

    /**
     * [adminUserListsData 管理员 --- 动态数据]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function adminUserListsData (Request $request)
    {
        return static::getListsData($request);
    }

    /**
     * [adminUserSave 管理员 --- 添加、编辑页面]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function adminUserSave (Request $request)
    {

        $data = [];

        if ($id = $request->get('id')) {

            $data['data'] = static::$model::getOneByWhere(['id' => $id]);
        }

        return view('AdminUser/adminUserSave')->with($data);
    }

    /**
     * [adminUserSavePro 管理员 --- 执行添加、编辑]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function adminUserSavePro (Request $request)
    {
        //来自 AJAX 请求
        if (request()->ajax()) {


            $paramArr = json_decode($request->post('paramJson'), true);

            //密码加密
            if (isset($paramArr['password'])) {

                $paramArr['password'] = UserService::encrypt($paramArr['password']);
            }

            $res = static::saveData($paramArr, static::$model);

            if ($res !== false) {

                return formatReturn(Common::SUCCESS, __('common.success'));
            }
        }

        return formatReturn();
    }

    /**
     * [adminUserAllot 给管理员分配角色]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function adminUserAllot (Request $request)
    {
        //get方式，渲染页面
        if($request->isMethod('get')){

            $data = [];

            if ($user_id = $request->get('id')) {

                $data['user_id'] = $user_id;
                $data['data'] = AdminUserRole::getRoleSelects($user_id);
                return view('AdminUser/adminUserAllot')->with($data);
            }
        }
    }

    /**
     * [adminUserAllotPro 给管理员分配角色 - 执行保存]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function adminUserAllotPro (Request $request)
    {
        if ($request->isMethod('post')) {

            $user_id  = $request->post('user_id');
            $role_ids = $request->post('role_ids');

            $res = AdminUserRole::saveRoleForUser($user_id, $role_ids);

            if ($res !== false) {

                return formatReturn(Common::SUCCESS, __('common.success'));
            }
        }

        return formatReturn();
    }


    /**
     * [adminUserInfo 管理员 - 基本信息]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function adminUserInfo (Request $request)
    {
        //拉取基本信息
        if ($request->isMethod('get')) {

            $data['data'] = Auth::user();

            return view('AdminUser/adminUserInfo')->with($data);

        //保存基本信息
        } else if ($request->isMethod('post')) {

            //接收参数
            $paramArr  = json_decode($request->post('paramJson'), true);

            //处理爱好
            $hobby = [];
            if (isset($paramArr['write'])) {

                unset($paramArr['write']);
                $hobby[] = 'write';
            }
            if (isset($paramArr['read'])) {

                unset($paramArr['read']);
                $hobby[] = 'read';
            }
            if (isset($paramArr['game'])) {

                unset($paramArr['game']);
                $hobby[] = 'game';
            }

            //多余参数，图片上传的时候，layui会自动加一个input标签
            unset($paramArr['file']);

            //爱好字段，组成字符串
            $paramArr['hobby'] = implode(',', $hobby);

            $res = parent::saveData($paramArr);

            if ($res !== false) {

                return formatReturn(Common::SUCCESS, __('common.success'));
            } else {

                return formatReturn();
            }
        }
    }

    public function changePassword (Request $request)
    {
        //拉取基本信息
        if ($request->isMethod('get')) {

            $data['data'] = Auth::user();

            return view('AdminUser/changePassword')->with($data);

        //保存基本信息
        } else if ($request->isMethod('post')) {

            //接收参数
            $paramArr  = json_decode($request->post('paramJson'), true);

            //密码加密
            if (isset($paramArr['password'])) {

                $paramArr['password'] = UserService::encrypt($paramArr['password']);
            }

            //爱好字段，组成字符串
            $res = parent::saveData($paramArr);

            if ($res !== false) {

                return formatReturn(Common::SUCCESS, __('common.success'));
            } else {

                return formatReturn();
            }
        }
    }



}
