<?php

/**
 * @description        : 角色管理
 *
 * @Author             : 天尽头流浪
 * @E-mail             : 1270469522@qq.com
 * @Date               : 2019-07-27 17:09:19
 * @Last Modified by   : 天尽头流浪
 */

namespace App\Http\Controllers;

use App\Consts\Common;
use App\Models\AdminRole;
use App\Models\AdminRolePower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class AdminRoleController extends Controller
{
    /**
     * [__construct 构造函数]
     */
    public function __construct ()
    {
        parent::__construct();
        static::$model = new AdminRole;

        //设置默认展开的左侧菜单
        Config::set('currentMenu', 'system');
    }

    /**
     * [adminRoleLists 角色管理 --- 渲染页面]
     * @return [type]           [description]
     */
    public function adminRoleLists ()
    {
        return view('AdminRole/adminRoleLists');
    }

    /**
     * [adminRoleListsData 角色管理 --- 动态获取数据]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function adminRoleListsData (Request $request)
    {
        return static::getListsData($request);
    }

    /**
     * [adminRoleSave 角色管理 --- 添加、编辑页面]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function adminRoleSave (Request $request)
    {
        $data = [];

        if ($id = $request->get('id')) {

            $data['data'] = static::$model::getOneByWhere(['id' => $id]);
        }

        return view('AdminRole/adminRoleSave')->with($data);
    }

    /**
     * [adminRoleSavePro 角色管理 --- 执行添加、编辑]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function adminRoleSavePro (Request $request)
    {
        //来自 AJAX 请求
        if (request()->ajax()) {

            $paramArr = json_decode($request->post('paramJson'), true);

            //保存数据
            $res = static::saveData($paramArr);

            if ($res !== false) {

                return formatReturn(Common::SUCCESS, __('common.success'));
            }
        }

        return formatReturn();
    }

    /**
     * [adminRoleAllot 给角色分配权限]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function adminRoleAllot (Request $request)
    {
        //get方式，渲染页面
        if($request->isMethod('get')){

            $data = [];

            if ($role_id = $request->get('id')) {

                return view('AdminRole/adminRoleAllot')->with(['role_id' => $role_id]);

            }
        //post方式，获取数据
        } else if ($request->isMethod('post')) {

            $role_id = $request->post('role_id');

            $data = [
                "trees" => AdminRolePower::getTreeLists($role_id)
            ];

            return formatReturn(Common::SUCCESS, __('common.success'), $data);
        }
    }

    /**
     * [adminRoleAllotPro 给角色分配权限 - 执行]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function adminRoleAllotPro (Request $request)
    {
        if ($request->isMethod('post')) {

            $role_id   = $request->post('role_id');
            $power_ids = $request->post('power_ids');

            $res = AdminRolePower::savePowerForRole($role_id, $power_ids);

            if ($res !== false) {

                return formatReturn(Common::SUCCESS, __('common.success'));
            }
        }

        return formatReturn();
    }

}
