<?php

/**
 * @description        : 用户 - 角色 - 分配
 *
 * @Author             : 天尽头流浪
 * @E-mail             : 1270469522@qq.com
 * @Date               : 2019-08-09 11:33:10
 * @Last Modified by   : 天尽头流浪
 */

namespace App\Models;

class AdminUserRole extends Common
{

    protected $table = 'admin_user_role';

    /**
     * [getAllRoleListsByUserId 根据用户ID获取对应的所有角色]
     *
     * @param  integer $userId [description]
     * @return [type]          [description]
     */
    protected function getAllRoleListsByUserId ($user_id = 0)
    {
        if ($user_id == 0) {

            return array();
        }

        $role_ids = static::getAllWithParam(['user_id' => $user_id, 'status' => 1]);

        return array_column($role_ids, 'role_id');
    }

    /**
     * [getEffectiveRoleListsByUserId 根据用户ID获取对应的有效的角色]
     * @param  integer $user_id [description]
     * @return [type]           [description]
     */
    protected function getEffectiveRoleListsByUserId ($user_id = 0)
    {
        if ($user_id == 0) {

            return array();
        }

        $result_obj = static::leftJoin('admin_role', 'admin_user_role.role_id', '=', 'admin_role.id')->where([
                                    'user_id'                => $user_id,
                                    'admin_user_role.status' => 1,
                                    'admin_role.status'      => 1,
                                ])->get();

        $role_ids = empty($result_obj) ? array() : $result_obj->toArray();

        return array_column($role_ids, 'role_id');
    }

    /**
     * [getSelects 角色列表]
     *
     * 管理员 - 给管理员分配角色，下拉菜单
     *
     * @param  integer $user_id [description]
     * @return [type]           [description]
     */
    protected function getRoleSelects ($user_id = 0)
    {
        //获取用户所拥有的角色列表
        $role_selected = static::getEffectiveRoleListsByUserId($user_id);

        //获取全部角色列表
        $role_lists = AdminRole::getAllWithParam();

        $arr = [];
        //根据用户所拥有的角色，处理checked
        foreach ($role_lists as $key => $value) {
            $arr[$key]['id']        = $value['id'];
            $arr[$key]['role_name'] = $value['role_name'];
            $arr[$key]['selected']  = in_array($value['id'], $role_selected) ? 'true' : 'false';
            $arr[$key]['disabled']  = $value['status'] == 1 ? 'false' : 'true';
        }

        return $arr;
    }

    /**
     * [saveRoleForUser 为管理员保存角色]
     *
     * @param  integer $user_id  [description]
     * @param  string  $role_ids [description]
     * @return [type]            [description]
     */
    protected function saveRoleForUser ($user_id = 0, $role_ids = '')
    {
        if ($user_id == 0 || empty($role_ids)) {

            return false;
        }

        $bloon = static::updateByWhere(['user_id' => $user_id], ['status' => 0]);

        $where = [];
        $fieldArr = [];

        foreach (explode(',', $role_ids) as $key => $value) {

            $where = [
                'user_id' => $user_id,
                'role_id' => $value,
            ];
            $fieldArr = [
                'user_id' => $user_id,
                'role_id' => $value,
                'status'  => 1,
            ];

            if (static::updateOrinsert($where, $fieldArr)) {

                continue;
            } else {

                return false;
            }
        }
        return true;
    }

}
