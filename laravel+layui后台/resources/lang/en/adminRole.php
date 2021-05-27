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
        'name'   => 'Name',
        'desc'   => 'Desc',
        'status' => [
            'status'  => 'Status',
            'all'     => 'All',
            'valid'   => 'Valid',
            'invalid' => 'Invalid',
        ],
        'startDate' => 'Start Date',
        'endDate'   => 'End Date',
        'search'    => 'Search',
        'reset'     => 'Reset',
    ],

    'tpl' => [
        'add'     => 'Add',
        'allot'   => 'Allot',
        'edit'    => 'Edit',
        'delete'  => 'Delete',
        'valid'   => 'Valid',
        'invalid' => 'Invalid'
    ],

    'field' => [
        'name'        => 'Name',
        'desc'        => 'Desc',
        'status'      => 'Status',
        'create_time' => 'Create Time',
        'update_time' => 'Update Time',
        'operation'   => 'Operation',
    ],

    'message' => [
        'is_delete'      => 'Are you sure you want to delete it?',
        'delete_success' => 'Deletion successful!',
        'switch_success' => 'Switch successful!',
        'failure'        => 'Operation failed!',
        'network_error'  => 'Network exception!',
        'request_error'  => 'Data request failed!',
    ],

    'save' => [
        'submit' => 'Submit',
        'reset'  => 'Reset',
    ],

];
