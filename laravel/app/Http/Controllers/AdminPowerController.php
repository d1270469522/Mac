<?php

/**
 * @description        : 权限管理
 *
 * @Author             : 天尽头流浪
 * @E-mail             : 1270469522@qq.com
 * @Date               : 2019-07-26 14:43:00
 * @Last Modified by   : 天尽头流浪
 */

namespace App\Http\Controllers;

use App\Consts\Common;
use App\Models\AdminPower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class AdminPowerController extends Controller
{
    /**
     * [__construct 构造函数]
     */
    public function __construct ()
    {
        parent::__construct();
        static::$model = new AdminPower;

        //设置默认展开的左侧菜单
        Config::set('currentMenu', 'system');
    }

    /**
     * [adminPowerLists 权限路由 --- 渲染页面]
     * @return [type]           [description]
     */
    public function adminPowerLists ()
    {
        return view('AdminPower/adminPowerLists');
    }

    /**
     * [adminPowerListsData 权限路由 --- 动态获取数据]
     * @return [type]           [description]
     */
    public function adminPowerListsData ()
    {
        return [
            'code' => 0,
            'msg'  => 'OK',
            'data' => static::$model::getAllWithParam([],['order_id'=>'ASC', 'id'=>'ASC'])
        ];
    }

    /**
     * [adminPowerSave 权限路由 --- 添加、编辑页面]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function adminPowerSave (Request $request)
    {
        $data = [];
        //权限列表
        $data['tree'] = static::$model::getTreeLists();

        if ($id = $request->get('id')) {
            //编辑的数据
            $data['data'] = static::$model::getOneByWhere(['id' => $id]);
        }

        return view('AdminPower/adminPowerSave')->with($data);
    }

    /**
     * [adminPowerSavePro 权限路由 --- 执行添加、编辑]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function adminPowerSavePro (Request $request)
    {
        //来自 AJAX 请求
        if (request()->ajax()) {

            $paramArr = json_decode($request->post('paramJson'), true);

            //保存数据
            $res = static::saveData($paramArr);

            if ($res !== false) {

                return formatReturn(Common::SUCCESS, __('common.success'));
            }
        }

        return formatReturn();
    }
}
