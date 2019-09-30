<?php

/**
 * @description        : 天尽头流浪
 *
 * @Author             : 天尽头流浪
 * @E-mail             : 1270469522@qq.com
 * @Date               : 2019-09-06 16:15:10
 * @Last Modified by   : 天尽头流浪
 */

namespace App\Http\Middleware;

use Closure;
use App\Models\AdminPower;
use App\Service\UserService;

class Permission
{

    //绿色通道，不被验证的路由
    private static $allowArr = [
        '/',
        '//',
        '/login',
        '/logout',
        '/switchLocale/en',
        '/switchLocale/zh',
        '/AdminUser/adminUserInfo',
        '/AdminUser/changePassword',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        //用户权限列表
        $power_lists = UserService::getUserAccess();

        //如果是超级管理员或admin，绿色通道，直接通过
        if ($power_lists === '*') {

            return $next($request);
        }

        //权限列表 ID
        $power_ids = array_column($power_lists, 'power_id');

        //权限全量数据
        $power_res = AdminPower::getAllWithParam(['id' => $power_ids]);

        //获取权限路由列表
        $routeArr = array_unique(array_column($power_res, 'power_url'));

        //获取当前路由
        $route = '/'.\Request::path();

        //绿色通道，不被验证的路由
        if (in_array($route, static::$allowArr)) {
            return $next($request);
        }

        //判断当前路由是不是在允许范围
        if (in_array($route, $routeArr)) {

            return $next($request);
        } else {

            //获取当前路由
            $method = \Request::method();

            if ($method == 'GET') {

                echo "<div style='height:100px; line-height:100px; text-align:center; font-size:21px; color:red;'>".__('common.noPermission')."</div>";die;
            } else {

                $data = [
                    'code' => 999,
                    'msg'  => __('common.noPermission'),
                ];
                echo json_encode($data);die;
            }

        }
    }
}
