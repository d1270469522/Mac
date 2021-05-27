<?php

/**
 * @description        : 语言配置：角色管理
 *
 * @Author             : 天尽头流浪
 * @E-mail             : 1270469522@qq.com
 * @Date               : 2019-08-01 16:30:51
 * @Last Modified by   : 天尽头流浪
 */

return [

    'conditions' => [
        'name'   => '名称',
        'desc'   => '描述',
        'status' => [
            'status'  => '状态',
            'all'     => '请选择',
            'valid'   => '有效',
            'invalid' => '无效',
        ],
        'startDate' => '开始日期',
        'endDate'   => '结束日期',
        'search'    => '搜索',
        'reset'     => '重置',
    ],

    'tpl' => [
        'add'     => '添加',
        'allot'   => '分配权限',
        'edit'    => '编辑',
        'delete'  => '删除',
        'valid'   => '有效',
        'invalid' => '无效'
    ],

    'field' => [
        'name'        => '名称',
        'desc'        => '描述',
        'status'      => '状态',
        'create_time' => '创建时间',
        'update_time' => '编辑时间',
        'operation'   => '操作',
    ],

    'message' => [
        'is_delete'      => '确定要删除吗？',
        'delete_success' => '删除成功！',
        'switch_success' => '切换成功！',
        'failure'        => '操作失败！',
        'network_error'  => '网络异常！',
        'request_error'  => '请求数据失败！',
    ],

    'save' => [
        'submit' => '提交',
        'reset'  => '重置',
    ],

];
