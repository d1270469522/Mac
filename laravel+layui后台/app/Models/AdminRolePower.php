<?php

/**
 * @description        : 角色 - 权限 - 分配
 *
 * @Author             : 天尽头流浪
 * @E-mail             : 1270469522@qq.com
 * @Date               : 2019-08-08 16:58:29
 * @Last Modified by   : 天尽头流浪
 */

namespace App\Models;

class AdminRolePower extends Common
{

    protected $table = 'admin_role_power';

    /**
     * [getTreeLists 权限树]
     *
     * 角色管理 - 给角色分配权限
     *
     * @return [type] [description]
     */
    protected function getTreeLists($role_id = 0)
    {
        //权限：角色拥有的权限
        $role_power    = static::getAllWithParam(['role_id' => $role_id, 'status' => 1]);
        $power_checked = array_column($role_power, 'power_id');

        //权限列表
        $power_lists   = AdminPower::getAllWithParam([],['order_id'=>'ASC', 'id'=>'ASC']);

        $arr = [];
        //根据用户所拥有的权限，处理checked
        foreach ($power_lists as $key => $value) {
            $arr[$key]['name']     = __('menu.'.$value['power_name']);
            $arr[$key]['value']    = $value['id'];
            $arr[$key]['pid']      = $value['parent_id'];
            $arr[$key]['checked']  = in_array($value['id'], $power_checked) ? '1' : '';
            $arr[$key]['disabled'] = $value['status'] == 1 ? '' : 'true';
        }

        return static::formatTree($arr);
    }


    /**
     * [formatTree 权限树格式化]
     *
     * @param  array  $paramArr [description]
     * @return [type]           [description]
     */
    private function formatTree ($paramArr = []) {

        //第一步 构造数据
        $temp_arr = [];

        foreach ($paramArr as $value) {

            $temp_arr[$value['value']] = $value;
        }

        //第二部 遍历数据 生成树状结构
        $tree = [];

        foreach ($temp_arr as $key => $value) {

            if ($value['pid'] == 0) {

                unset($temp_arr[$key]['pid']);
                $tree[] = &$temp_arr[$key];

            } else if (isset($temp_arr[$value['pid']])) {

                unset($temp_arr[$key]['pid']);
                $temp_arr[$value['pid']]['list'][] = &$temp_arr[$key];
            }
        }
        return $tree;
    }

    /**
     * [savePowerForRole 为角色保存权限]
     * @param  integer $role_id   [description]
     * @param  array   $power_ids [description]
     * @return [type]             [description]
     */
    protected function savePowerForRole($role_id = 0, $power_ids = [])
    {
        if ($role_id == 0 || empty($power_ids)) {

            return false;
        }

        $bloon = static::updateByWhere(['role_id' => $role_id], ['status' => 0]);

        $where = [];
        $fieldArr = [];

        foreach ($power_ids as $key => $value) {

            $where = [
                'role_id'  => $role_id,
                'power_id' => $value,
            ];
            $fieldArr = [
                'role_id'  => $role_id,
                'power_id' => $value,
                'status'   => 1,
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
