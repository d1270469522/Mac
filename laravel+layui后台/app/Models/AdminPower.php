<?php

/**
 * @description        : 权限管理
 *
 * @Author             : 天尽头流浪
 * @E-mail             : 1270469522@qq.com
 * @Date               : 2019-07-26 14:47:49
 * @Last Modified by   : 天尽头流浪
 */

namespace App\Models;

use Illuminate\Support\Facades\Auth;

class AdminPower extends Common
{

    protected $table = 'admin_power';

    /**
     * [getMenuLists 获取可用菜单]
     *
     * 左侧目录
     *
     * @return [type] [description]
     */
    protected function getMenuLists($paramArr = [])
    {
        $where = [
            'power_type' => 11, //左侧菜单
            'status'     => 1,  //状态：有效
        ];

        //合并条件【默认条件、参数条件】
        $where = array_merge($where, $paramArr);

        return static::getAllWithParam($where,['order_id'=>'ASC', 'id'=>'ASC']);
    }

    /**
     * [getTreeLists 权限树]
     *
     * 添加、编辑的时候，选择上级目录
     *
     * @return [type] [description]
     */
    protected function getTreeLists()
    {
        //权限列表
        $power_lists = static::getAllWithParam([],['order_id'=>'ASC', 'id'=>'ASC']);

        return static::formatTree($power_lists);
    }

    /**
     * [formatTree 权限树格式化]
     *
     * @param  paramArr  $paramArr [description]
     * @return [type]           [description]
     */
    private function formatTree ($paramArr = [], $parent_id = 0, $level = 0) {

        //声明静态数组,避免递归调用时,多次声明导致数组覆盖
        static $tree = [];

        foreach ($paramArr as $key => $value){

            //第一次遍历,找到父节点为根节点的节点 也就是parent_id=0的节点
            if ($value['parent_id'] == $parent_id){

                //父节点为根节点的节点,级别为0，也就是第一级
                $value['level'] = str_repeat('&nbsp;', $level) . '├ ';
                //把数组放到tree中
                $tree[] = $value;
                //把这个节点从数组中移除,减少后续递归消耗
                unset($paramArr[$key]);
                //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
                static::formatTree($paramArr, $value['id'], $level + 6);
            }
        }
        return $tree;
    }

}
