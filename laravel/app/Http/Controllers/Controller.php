<?php

namespace App\Http\Controllers;

use App\Consts\Common;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    //模型变量初始化
    protected static $model = NULL;

    /**
     * [__construct 构造函数]
     */
    public function __construct ()
    {

    }

    /**
     * [getListsData 公共方法：异步获取页面列表数据]
     * @param  [type] $request [description]
     * @param  [type] $model   [description]
     * @return [type]          [description]
     */
    public function getListsData ($request = NULL)
    {
        $count = 0;
        $data  = [];

        if ($request != NULL) {

            //get方式  初次加载
            if ($request->isMethod('get')) {

                $paramArr['page']  = $request->get('page');
                $paramArr['limit'] = $request->get('limit');

            //post方式  条件搜索（重载）
            } else if ($request->isMethod('post')) {

                $paramArr = $request->post();
            }

            $count = static::$model::getCountWithParam($paramArr);
            $data  = static::$model::getAllWithParam($paramArr);
        }

        return  [
            'code'  => 0,
            'count' => $count,
            'data'  => $data,
        ];
    }


    /**
     * [saveOrEditData 公共方法：添加、编辑]
     * @param  [type] $paramArr [description]
     * @param  [type] $model    [description]
     * @return [type]           [description]
     */
    public function saveData ($paramArr = [])
    {
        $res = false;

        if ($paramArr != []) {

            //如果有ID，则进行编辑
            if (isset($paramArr['id']) && $paramArr['id'] != '') {

                $where = ['id' => $paramArr['id']];
                unset($paramArr['id']);

                $res = static::$model::updateByWhere($where, trimArray($paramArr));

            //如果没有ID，则进行添加
            } else {

                unset($paramArr['id']);
                $res = static::$model::saveOne(trimArray($paramArr));
            }
        }
        return $res;
    }



    /**
     * [uploadImg 公共方法：上传图片]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function uploadImg (Request $request)
    {
        //获取图片
        if ($file = $request->file('file')) {

            $allowed_extensions = ["png", "jpg", "gif", "jpeg"];

            //图片是否是正规图片
            if ($file->getClientOriginalExtension() && !in_array($file->getClientOriginalExtension(), $allowed_extensions)) {

                $result['msg']  = __('common.success');
            }

            //文件夹
            $destinationPath = 'uploads/adminUserImg/';
            //图片扩展
            $extension = $file->getClientOriginalExtension();
            //图片名称
            $fileName = Auth::user()->username . '_' . time() . '.' . $extension;

            //进行保存
            if ($file->move($destinationPath, $fileName)) {

                $data = [
                    'path' => $destinationPath . $fileName,
                ];
                return formatReturn(Common::SUCCESS, __('common.success'), $data);
            }
        }

        return formatReturn();
    }



}
