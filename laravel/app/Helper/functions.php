<?php

/**
 * @description        : 公共方法
 *
 * @Author             : 天尽头流浪
 * @E-mail             : 1270469522@qq.com
 * @Date               : 2019-08-20 17:49:30
 * @Last Modified by   : 天尽头流浪
 */

use App\Consts\Common;

/**
 * [TrimArray 去除数据中字符串的空格]
 * @param [type] $Input [description]
 */
function trimArray ($Input)
{
    if (!is_array($Input)) {

        return trim($Input);
    }

    return array_map('TrimArray', $Input);
}

/**
 * [formatReturn 公共方法：格式化返回]
 * @param  [type] $code [description]
 * @param  [type] $msg  [description]
 * @param  array  $data [description]
 * @return [type]       [description]
 */
function formatReturn ($code = '', $msg = '', $data = [])
{
    $code = !empty($code) ? $code : Common::FAILURE;
    $msg  = !empty($msg)  ? $msg  : __('common.failure');
    $data = !empty($data) ? $data : array();

    return [
        'code' => $code,
        'msg'  => $msg,
        'data' => $data,
    ];
}


/**
 * [p 格式化打印数据]
 * @param  [type] $data [description]
 * @return [type]       [description]
 */
function p ($data)
{
    echo '<pre>';
    print_r($data);die;
}
