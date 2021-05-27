<?php

/**
 * @description        : 语言配置：管理员
 *
 * @Author             : 天尽头流浪
 * @E-mail             : 1270469522@qq.com
 * @Date               : 2019-08-12 15:47:10
 * @Last Modified by   : 天尽头流浪
 */

return [

    'conditions' => [
        'username' => '账号',
        'status'   => [
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
        'allot'   => '分配角色',
        'edit'    => '编辑',
        'delete'  => '删除',
        'valid'   => '有效',
        'invalid' => '无效'
    ],

    'field' => [
        'username'    => '账号',
        'password'    => '密码',
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

    'info' => [
        'username' => '账号',
        'phone'    => '手机',
        'email'    => '邮箱',
        'img'      => '头像',
        'img_msg'  => '点击上传头像',
        'address'  => [
            'address'  => '地址',
            'province' => '省份',
            'city'     => '市区',
            'county'   => '县城',
            'detail'   => '详细地址',
        ],
        'hobby' => [
            'hobby' => '爱好',
            'write' => '写作',
            'read'  => '阅读',
            'game'  => '游戏',
        ],
        'desc'   => '自我简介',
        'submit' => '保存',
        'reset'  => '重置',
        'verify' => [
            'not_login'        => '未登录，不能保存！',
            'password_mistake' => '密码必须6到12位，且不能出现空格',
        ],
    ],

    'password' => [
        'old'  => '旧密码',
        'new'  => '新密码',
        'new2' => '确认密码',
        'verify' => [
            'password'      => '密码必须6到12位，且不能出现空格！',
            'old_password'  => '旧密码输入错误！',
            'new_password'  => '新密码与旧密码不能一样！',
            'new2_password' => '两次输入不一致！',
        ],
        'success_msg' => '密码修改成功，请重新登录！',
        'btn_msg'     => '知道了',
    ],
];
