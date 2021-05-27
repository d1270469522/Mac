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
        'username' => 'UserName',
        'status'   => [
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
        'username'    => 'UserName',
        'password'    => 'PassWord',
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

    'info' => [
        'username' => 'UserName',
        'phone'    => 'Phone',
        'email'    => 'Email',
        'img'      => 'Head portrait',
        'img_msg'  => 'Click upload the head portrait',
        'address'  => [
            'address'  => 'Address',
            'province' => 'Province',
            'city'     => 'City',
            'county'   => 'County',
            'detail'   => 'Detail',
        ],
        'hobby' => [
            'hobby' => 'Hobby',
            'write' => 'Write',
            'read'  => 'Read',
            'game'  => 'Game',
        ],
        'desc'   => 'desc',
        'submit' => 'submit',
        'reset'  => 'reset',
        'verify' => [
            'not_login'        => 'Not logged in, cannot save!',
            'password_mistake' => 'Passwords must be 6 to 12 bits long and no Spaces can appear!',
        ],
    ],

    'password' => [
        'old'  => 'The old password',
        'new'  => 'The new password',
        'new2' => 'Confirm password',
        'verify' => [
            'password'      => 'Passwords must be 6 to 12 bits long and no Spaces can appear!！',
            'old_password'  => 'Old password input error!',
            'new_password'  => 'The new password cannot be the same as the old password!',
            'new2_password' => 'Two different inputs!',
        ],
        'success_msg' => 'Password changed successfully, please log in again!',
        'btn_msg'     => 'I Know',
    ],
];
