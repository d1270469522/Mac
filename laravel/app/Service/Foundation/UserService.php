<?php

/**
 * @description        : 管理员的基础服务
 *
 * @Author             : 天尽头流浪
 * @E-mail             : 1270469522@qq.com
 * @Date               : 2019-07-18 15:24:12
 * @Last Modified by   : 天尽头流浪
 */

namespace App\Service\Foundation;

use App\Consts\MessageCode;
use App\Models\AdminUser;
use App\Models\AdminUserRole;
use App\Models\AdminRolePower;
use App\Models\AdminPower;
use App\Models\LogAdminLogin;
use Illuminate\Support\Facades\Auth;

class UserService
{

    /**
     * [attemptLogin 尝试登陆]
     * @param  array  $paramArr  [登陆参数]
     * @return [bloon]           [登陆结果：成功、失败]
     */
    public function attemptLogin($paramArr = [])
    {
        //登陆结果【返回登陆】默认false
        $result = false;

        //登陆结果【日志表】1、成功；0、失败；
        $login_res = 0;

        if (!empty($paramArr['username'])) {

            $where['username'] = $paramArr['username'];
            $where['status']   = 1;

            $admin_user_info = AdminUser::getOneByWhere($where);

            if ($admin_user_info) {

                //登陆密码加密
                $password = self::encrypt($paramArr['password']);

                //验证密码是否正确
                if ($admin_user_info['password'] === $password) {

                    $login_res = 1;
                    $result =  $admin_user_info;
                }
            }
        }

        //登陆日志
        LogAdminLogin::saveOne([
            'username'  => $paramArr['username'],
            'login_ip'  => $paramArr['login_ip'],
            'login_res' => $login_res,
        ]);

        return $result;
    }

    /**
     * [encrypt 对密码进行加密]
     * @param  [string] $password [未加密的密码]
     * @return [string]           [加密后的密码]
     */
    public function encrypt($password)
    {
        return md5($password);
    }

    /**
     * [getMenuLists 获取左侧菜单列表]
     * @return [array] [左侧菜单]
     */
    public function getMenuLists()
    {
        $power_lists = $this->getUserAccess();

        if ($power_lists === '*') {

            return AdminPower::getMenuLists();
        }

        $power_ids = array_column($power_lists, 'power_id');

        $menu_lists = AdminPower::getMenuLists(['id' => $power_ids]);

        return $menu_lists;
    }

    /**
     * 获取用户权限列表
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getUserAccess()
    {
        $admin_id = Auth::id();

        //admin最大权限
        if ($admin_id === 1) {

            return '*';
        }

        //获取用户所有角色
        $role_lists   = AdminUserRole::getEffectiveRoleListsByUserId($admin_id);
        //超级管理员，拥有所有权限
        if (in_array(1, $role_lists)) {

            return '*';
        }

        //获取角色对应的权限
        $power_lists = AdminRolePower::getAllWithParam(['role_id' => $role_lists, 'status' => 1]);

        return $power_lists;
    }


}
