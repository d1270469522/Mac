<?php

/**
 * @description        : 天尽头流浪
 *
 * @Author             : 天尽头流浪
 * @E-mail             : 1270469522@qq.com
 * @Date               : 2019-07-18 14:58:06
 * @Last Modified by   : 天尽头流浪
 */

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class AdminUser extends Authenticatable
{

    protected $table = 'admin_user';

    /**
     * [getUpdatedAtColumn 禁用update_at自动更新]
     * @return [type] [description]
     */
    public function getUpdatedAtColumn ()
    {
        return null;
    }

    /**
     * [saveOne 添加]
     * @param  [type] $paramArr [description]
     * @return [type]           [description]
     */
    protected static function saveOne($paramArr = [])
    {
        if ($paramArr === []) {

            return false;
        }

        return static::insert($paramArr);
    }

    /**
     * [getOneById 编辑]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    protected static function updateByWhere($where = [], $paramArr = [])
    {
        if ($where === [] || $paramArr === []) {

            return false;
        }

        return static::where($where)->update($paramArr);
    }

    /**
     * [getOneByWhere 获取管理员个人信息]
     * @param  array  $paramArr [description]
     * @return [type]           [description]
     */
    protected function getOneByWhere($paramArr = [])
    {
        $where = [
            'is_del' => 0, //0:未删除
        ];

        if (!empty($paramArr)) {

            $where = array_merge($where, $paramArr);
        }

        $result_obj = static::where($where)->first();

        return empty($result_obj) ? array() : $result_obj->toArray();
    }

    /**
     * [getAll 根据条件，获取所有数据]
     * @return [type] [description]
     */
    protected static function getAllWithParam($paramArr = [], $orderBy = '')
    {
        $query = self::formatQuery($paramArr, $orderBy);

        //页码 条数
        if (isset($paramArr['limit']) && isset($paramArr['page'])) {

            $query->offset($paramArr['limit'] * ($paramArr['page'] - 1))->limit($paramArr['limit']);
        }

        $result_obj = $query->get();

        return empty($result_obj) ? array() : $result_obj->toArray();
    }

    /**
     * [getCount 根据条件，计算一共多少条]
     * @return [type] [description]
     */
    protected static function getCountWithParam($paramArr = [])
    {
        $query = self::formatQuery($paramArr);

        return $query->count();
    }



    /**
     * [formatQuery 初始化SQL对象]
     * @param  array  $paramArr [description]
     * @return [type]           [description]
     */
    private static function formatQuery ($paramArr = [], $orderBy = '')
    {
        $query = static::where('is_del', 0);

        //偏移量 和 limit 不加入条件
        $notJoinArr = ['page', 'limit', '_token'];

        $equalArr = ['id', 'status', 'type'];

        //组装条件
        if (!empty($paramArr)) {

            foreach ($paramArr as $key => $value) {

                if (is_array($value)) {

                    $query->whereIn($key, $value);

                //偏移量 和 limit 不加入条件
                //空参数不加入条件
                } else if (!in_array($key, $notJoinArr) && trim($value) !== '') {

                    if (in_array($key, $equalArr)) {

                        $query->where($key, $value);
                    } else if ($key === 'startDate') {

                        $query->where('create_time', '>=', $value);
                    } else if ($key === 'endDate') {

                        $query->where('create_time', '<', $value);
                    } else {

                        $query->where($key, 'like', '%' . $value . '%');
                    }
                }
            }
        }

        //组装排序
        if ($orderBy === '') {

            $query->orderBy('id', 'ASC');
        } else if (is_string($orderBy)) {

            $query->orderBy($orderBy, 'ASC');
        } else if (is_array($orderBy)) {

            foreach ($orderBy as $key => $value) {
                $query->orderBy($key, $value);
            }
        } else {
            $query->orderBy('id', 'ASC');
        }

        return $query;
    }
}
