<?php
/**
 * 能力模型
 */

namespace App\Models;

use App\Utils;
use MongoDB\BSON\Regex;
use App\Common\Sequence;
use App\Common\BaseModel;
use App\Common\Constants;
use Key\Database\Mongodb;
use Key\Records\Pagination;

class EnterpriseAbility extends BaseModel
{
    /**
     * 模型创建
     */
    public function create($record)
    {
        $db = $this->getMongoMasterConnection();

        $default_data = [
            'id' => Sequence::getSeparateId(Constants::COLL_ENTERPRISE_ABILITY_MODEL, $this->aid ?: 1),
            'aid' => $this->aid,
            'eid' => $this->eid,
            'status' => 1,
            'created' => Mongodb::getMongoDate(),
            'updated' => Mongodb::getMongoDate(),
            'qs_num' => 0,
        ];

        $ability_data = array_merge($default_data, $record);

        // 添加模型
        $result = $db->insert(Constants::COLL_ENTERPRISE_ABILITY_MODEL, $ability_data);

        $dimension_data = [
            'id' => Sequence::getSeparateId(Constants::COLL_ENTERPRISE_DIMENSION_EL_RELATION, $this->aid ?: 1),
            'aid' => $this->aid,
            'eid' => $this->eid,
            'am_id' => $ability_data['id'],
            'status' => 1,
            'created' => Mongodb::getMongoDate(),
            'updated' => Mongodb::getMongoDate(),
            'level' => $ability_data['level'] ?: 1,
            'level_name' => '能力名称',
            'weight' => 100,
            'ab_element' => [],
        ];

        // 添加纬度关系
        $db->insert(Constants::COLL_ENTERPRISE_DIMENSION_EL_RELATION, $dimension_data);

        return $result ? $dimension_data['id'] : false;
    }

    /**
     * 模型列表
     */
    public function abilityList($keyword = '', $sort = 2, $pg, &$total)
    {
        $db = $this->getMongoMasterConnection();

        $condition = [
            'aid' => $this->aid,
            'status' => 1,
        ];

        // 关键字
        if ($keyword) {
            $condition['name'] = new Regex($keyword, 'im');
        }

        // 排序 1、正序；2、倒序
        if ($sort == 1) {
            $sort_field = ['updated' => 1];
        } else {
            $sort_field = ['updated' => -1];
        }

        // 分页
        if (!$pg) {
            $pg = new Pagination();
            $pg->setPage(0);
            $pg->setItemsPerPage(0);
        }

        // 查询数据
        $result = $db->fetchAll(Constants::COLL_ENTERPRISE_ABILITY_MODEL, $condition, $pg->getOffset(), $pg->getItemsPerPage(), $sort_field);
        Utils::convertMongoDateToTimestamp($result);

        // 获取总数
        $total = $db->count(Constants::COLL_ENTERPRISE_ABILITY_MODEL, $condition);

        return $result ?: [];
    }

    /**
     * 模型详情
     */
    public function detail($id)
    {
        $db = $this->getMongoMasterConnection();

        $condition = [
            'id' => $id,
            'aid' => $this->aid,
            'status' => 1,
        ];

        $result = $db->fetchRow(Constants::COLL_ENTERPRISE_ABILITY_MODEL, $condition, ['_id' => 0]);
        Utils::convertMongoDateToTimestamp($result);

        return $result ?: [];
    }

    /**
     * 模型编辑
     */
    public function update($id, $data)
    {
        $db = $this->getMongoMasterConnection();

        $cond = [
            'id' => $id,
            'aid' => $this->aid,
            'status' => 1,
        ];

        $data['updated'] = Mongodb::getMongoDate();

        // 更新模型表
        $result = $db->update(Constants::COLL_ENTERPRISE_ABILITY_MODEL, $cond, ['$set' => $data]);

        $dimension_cond = [
            'aid' => $this->aid,
            'status' => 1,
            'am_id' => $id
        ];

        $dimension_set = [
            'level' => $data['level'],
            'updated' => Mongodb::getMongoDate(),
        ];

        // 更新纬度关系表
        $db->update(Constants::COLL_ENTERPRISE_DIMENSION_EL_RELATION, $dimension_cond, ['$set' => $dimension_set]);

        return $result ? $id : false;
    }

    /**
     * 模型删除
     */
    public function delete($id)
    {
        $db = $this->getMongoMasterConnection();

        $cond = [
            'id' => $id,
            'aid' => $this -> aid,
            'status' => 1,
        ];

        $set = [
            'status' => 0,
            'updated' => Mongodb::getMongoDate(),
        ];

        // 删除模型表
        $result = $db->update(Constants::COLL_ENTERPRISE_ABILITY_MODEL, $cond, ['$set' => $set]);

        if ($result) {

            $cond2 = [
                'am_id' => $id,
                'aid' => $this->aid,
                'status' => 1,
            ];

            // 删除维度关系表
            $db->update(Constants::COLL_ENTERPRISE_DIMENSION_EL_RELATION, $cond2, ['$set' => $set]);
        }

        return $result ? $id : false;
    }

/** = = = = = = = = 下方的部分：纬度设置 = = = = = = = = = **/

    /**
     * 获取某一模型下的所有纬度
     */
    public function dimensionList($am_id, $filed = [])
    {
        $db = $this->getMongoMasterConnection();

        $condition = [
            'aid' => $this->aid,
            'am_id' => $am_id,
            'status' => 1,
        ];

        // 获取某一模型下的所有纬度
        $result = $db->fetchAll(Constants::COLL_ENTERPRISE_DIMENSION_EL_RELATION, $condition, 0, 0, ['updated' => -1], ['_id' => 0]);

        foreach ($result as &$value) {
            Utils::convertMongoDateToTimestamp($value);
        }

        return $result;
    }

    /**
     * 纬度创建 | 纬度编辑
     */
    public function createOrUpdateDimension($am_id, $level, $record)
    {
        $db = $this->getMongoMasterConnection();

        // 要素ID集合：统计题目数量
        $em_ids = [];
        // 纬度ID集合：更改不在本次提交之外的数据 - status：0
        $dimension_ids = [];
        foreach ($record as $value) {

            $item = $value->toArray();

            $data = [
                'updated' => Mongodb::getMongoDate(),
                'level' => $level ?: 1,
                'level_name' => $item['level_name'] ?: '',
                'weight' => $item['weight'] ?: 100,
                'ab_element' => $item['ab_element'] ?: [],
            ];
            // 因为创建模型的时候，会默认创建一条纬度数据
            if ($item['id']) {
                $cond = [
                    'id' => $item['id'],
                    'am_id' => $am_id,
                    'status' => 1,
                    'eid' => $this->eid,
                    'aid' => $this->aid,
                ];
                // 如果是一级纬度，只需要修改
                // 如果是二级纬度，第一条进行编辑，如果有多条，第二条开始进行创建
                $db->update(Constants::COLL_ENTERPRISE_DIMENSION_EL_RELATION, $cond, ['$set' => $data]);
                $dimension_ids[] = $item['id'];
            } else {
                $default_data = [
                    'am_id' => $am_id,
                    'status' => 1,
                    'eid' => $this->eid,
                    'aid' => $this->aid,
                    'created' => Mongodb::getMongoDate(),
                ];
                $data = array_merge($default_data, $data);
                // 如果是二级纬度，第一条进行编辑，如果有多条，第二条开始进行创建
                $data['id'] = Sequence::getSeparateId(Constants::COLL_ENTERPRISE_DIMENSION_EL_RELATION, $this->aid ?: 1);
                $db->insert(Constants::COLL_ENTERPRISE_DIMENSION_EL_RELATION, $data);
                $dimension_ids[] = $data['id'];
            }

            // 如果是一级纬度，则走一个即可
            // 如果是二级纬度，每次追加
            $em_ids = array_merge($em_ids, array_column($item['ab_element'], 'id'));
        }

        // 把不在 dimension_ids 中的其他纬度数据 status 改成0
        $cond = [
            'id' => ['$nin' => $dimension_ids],
            'am_id' => $am_id,
            'status' => 1,
            'eid' => $this->eid,
            'aid' => $this->aid,
        ];
        $set = [
            'status' => 0
        ];
        $db->update(Constants::COLL_ENTERPRISE_DIMENSION_EL_RELATION, $cond, ['$set' => $set]);

        // 统计题目数量
        $cond = [
            'aid' => $this->aid,
            'status' => 1,
            'em_id' => ['$in' => $em_ids],
        ];
        $qs_num = $db->count(Constants::COLL_ENTERPRISE_ELEMENT_QUESTION, $cond);

        // 更新模型的题目数量
        $cond = [
            'aid' => $this->aid,
            'status' => 1,
            'id' => $am_id,
        ];
        $set = [
            'qs_num' => $qs_num,
            'updated' => Mongodb::getMongoDate(),
        ];
        $db->update(Constants::COLL_ENTERPRISE_ABILITY_MODEL, $cond, ['$set' => $set]);

        return true;
    }
}
