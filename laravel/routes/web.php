<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//路由 - 需要验证
Route::group(array('middleware' => ['auth', 'permission']), function(){

    //首页展示
    Route::get('/', 'IndexController@index');
    //首页展示 -- 内容
    Route::get('/indexShow', 'IndexController@indexShow');
    //语言配置
    Route::get('/switchLocale/{locale}', 'IndexController@switchLocale');


    /********** 权限管理 **********/

    //权限管理【列表】 - 渲染页面
    Route::get('/AdminPower/adminPowerLists', 'AdminPowerController@adminPowerLists');
    //权限管理【列表】 - 动态加载数据
    Route::get('/AdminPower/adminPowerListsData', 'AdminPowerController@adminPowerListsData');
    //权限管理【添加、编辑】 - 弹窗展示
    Route::get('/AdminPower/adminPowerSave', 'AdminPowerController@adminPowerSave');
    //权限管理【添加、编辑】 - 执行操作
    Route::post('/AdminPower/adminPowerSavePro', 'AdminPowerController@adminPowerSavePro');


    /********** 角色管理 **********/

    //角色管理【列表】 - 渲染页面
    Route::get('/AdminRole/adminRoleLists', 'AdminRoleController@adminRoleLists');
    //角色管理【列表】 - 动态加载数据 | 条件搜索、动态更新
    Route::post('/AdminRole/adminRoleListsData', 'AdminRoleController@adminRoleListsData');
    //角色管理【添加、编辑】 - 弹窗展示
    Route::get('/AdminRole/adminRoleSave', 'AdminRoleController@adminRoleSave');
    //角色管理【添加、编辑】 - 执行操作
    Route::post('/AdminRole/adminRoleSavePro', 'AdminRoleController@adminRoleSavePro');
    //角色管理【分配权限】 - 渲染页面
    Route::get('/AdminRole/adminRoleAllot', 'AdminRoleController@adminRoleAllot');
    //角色管理【分配权限】 - 动态加载数据
    Route::post('/AdminRole/adminRoleAllot', 'AdminRoleController@adminRoleAllot');
    //角色管理【分配权限】 - 执行操作
    Route::post('/AdminRole/adminRoleAllotPro', 'AdminRoleController@adminRoleAllotPro');


    /********** 管理员 **********/

    //管理员【列表】 - 渲染页面
    Route::get('/AdminUser/adminUserLists', 'AdminUserController@adminUserLists');
    //管理员【列表】 - 动态加载数据 | 条件搜索、动态更新
    Route::post('/AdminUser/adminUserListsData', 'AdminUserController@adminUserListsData');
    //管理员【添加、编辑】 - 弹窗展示
    Route::get('/AdminUser/adminUserSave', 'AdminUserController@adminUserSave');
    //管理员【添加、编辑】 - 执行操作
    Route::post('/AdminUser/adminUserSavePro', 'AdminUserController@adminUserSavePro');
    //管理员【分配角色】 - 渲染页面、加载数据
    Route::get('/AdminUser/adminUserAllot', 'AdminUserController@adminUserAllot');
    //管理员【分配角色】 - 执行操作
    Route::post('/AdminUser/adminUserAllotPro', 'AdminUserController@adminUserAllotPro');
    //管理员【右上角】 - 信息编辑 - 页面展示
    Route::get('/AdminUser/adminUserInfo', 'AdminUserController@adminUserInfo');
    //管理员【右上角】 - 信息编辑 - 执行操作（保存）
    Route::post('/AdminUser/adminUserInfo', 'AdminUserController@adminUserInfo');
    //管理员【右上角】 - 修改密码 - 页面展示
    Route::get('/AdminUser/changePassword', 'AdminUserController@changePassword');
    //管理员【右上角】 - 修改密码 - 执行操作（保存）
    Route::post('/AdminUser/changePassword', 'AdminUserController@changePassword');


    //图片上传
    Route::post('/uploadImg', 'Controller@uploadImg');
    //图片裁剪
    Route::get('/image/imageCropp', 'ImageController@imageCropp');
    //图片放大
    Route::get('/image/imageMagnifier', 'ImageController@imageMagnifier');

});

//登陆 - 页面
Route::get('/login','LoginController@login')->name('login');
//登陆 - 执行登陆操作
Route::post('/login','LoginController@loginPro');
//退出登陆
Route::get('/logout', 'LoginController@logout');


/**
 * 当且仅当路由不存在的时候，被调用；
 * 这个路由的定义，必须放在最后
 */
Route::fallback(function () {
    // return '路由信息不存在！';
    // return back()->withInput();
    return response('Hello World', 200)
                  ->header('Content-Type', 'text/plain');
});





