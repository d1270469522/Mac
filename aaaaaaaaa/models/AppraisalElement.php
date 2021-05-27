<?php
/**
 * 要素管理：model
 * author：天尽头流浪
 */
namespace App\Models;

use App\Utils;
use MongoDB\BSON\Regex;
use App\Common\Sequence;
use App\Common\Constants;
use App\Common\BaseModel;
use Key\Database\Mongodb;
use Key\Records\Pagination;

class EnterpriseElement extends BaseModel
{
    // 是否启用
    const ENABLE = 1;  // 启用
    const DISABLE = 2; // 禁用

    /**
     * 要素创建
     *
     * @param $record 要素创建时：字段详细验证
     */
    public function create($record)
    {
        $db = $this->getMongoMasterConnection();

        $default_data = [
            'id' => Sequence::getSeparateId(Constants::COLL_APPRAISAL_ELEMENT, $this->aid ?: 1),
            'aid' => $this->aid,
            'eid' => $this->eid,
            'status' => 1,
            'created' => Mongodb::getMongoDate(),
            'updated' => Mongodb::getMongoDate(),
            'questions_total' => 0,
        ];

        $data = array_merge($default_data, $record);

        $result = $db->insert(Constants::COLL_APPRAISAL_ELEMENT, $data);

        return $result ? $data['id'] : false;
    }

    /**
     * 要素列表
     *
     * @param $enable = 0 是否启用：0-全部；1-已启用；2-已禁用
     * @param $sort = 2 排序：1-正序；2-倒序
     * @param $keyword = '' 搜索关键字：要素名称
     * @param $pg 分页
     * @param &$total 总条数
     */
    public function list($enable = 0, $sort = 2, $keyword = '', $pg, &$total)
    {
        $db = $this->getMongoMasterConnection();

        $employee_model = new Employee($this->app);

        $cond = [
            'aid' => $this->aid,
            'status' => 1,
        ];

        // 是否启用：0-全部；1-已启用；2-已禁用
        if ($enable == 1) {
            $cond['enable'] = self::ENABLE;
        } else if ($enable == 2) {
            $cond['enable'] = self::DISABLE;
        }

        // 排序：1-正序；2-倒序
        if ($sort == 1) {
            $sort_field = ['updated' => 1];
        } else {
            $sort_field = ['updated' => -1];
        }

        // 搜索关键字：要素名称
        if ($keyword) {
            $cond['name'] = new Regex($keyword, 'im');
        }

        if (! $pg) {
            $pg = new Pagination();
            $pg->setPage(0);
            $pg->setItemsPerPage(0);
        }

        // 要素列表 - 查询结果
        $element_res = $db->fetchAll(Constants::COLL_APPRAISAL_ELEMENT, $cond, $pg->getOffset(), $pg->getItemsPerPage(), $sort_field);
        Utils::convertMongoDateToTimestamp($element_res);

        // 要素列表 - 总条数
        $total = $db->count(Constants::COLL_APPRAISAL_ELEMENT, $cond);

        // 要素创建人：赋值
        foreach ($element_res as $key => $value) {
            $employee_res = $employee_model->view($value['eid'], $this->aid, ['display' => 1]);
            $element_res[$key]['username'] = $employee_res['display'] ?: '';
        }

        return $element_res ?: [];
    }

    /**
     * 要素详情
     *
     * @param $id 要素ID
     */
    public function detail($id)
    {
        $db = $this->getMongoMasterConnection();

        $cond = [
            'aid' => $this->aid,
            'id' => $id,
            'status' => 1,
        ];

        $info = $db->fetchRow(Constants::COLL_APPRAISAL_ELEMENT, $cond, ['_id' => 0]);
        Utils::convertMongoDateToTimestamp($res);

        return $info ?: [];
    }

    /**
     * 要素编辑
     *
     * @param $id 要素ID
     * @param $record 要素编辑时：字段详细验证
     */
    public function update($id, $record)
    {
        $db = $this->getMongoMasterConnection();

        $cond = [
            'id' => $id,
            'aid' => $this->aid,
            'status' => 1,
        ];

        $set = $record;
        $set['updated'] = Mongodb::getMongoDate();

        $result = $db->update(Constants::COLL_APPRAISAL_ELEMENT, $cond, ['$set' => $set]);

        if ($result) {

            // 更改该要素下的要素题的状态
            $cond_question = [
                'element_id' => $id,
                'aid' => $this->aid,
                'status' => 1,
            ];

            $set_question = [
                'enable' => $record['enable'],
                'updated' => Mongodb::getMongoDate(),
            ];

            $db->update(Constants::COLL_APPRAISAL_ELEMENT_QUESTION, $cond_question, ['$set' => $set_question]);

            return $id;
        }

        return false;
    }

    /**
     * 要素删除
     *
     * @param $id 要素ID
     */
    public function delete($id)
    {
        $db = $this->getMongoMasterConnection();

        // 要素删除
        $cond = [
            'id' => $id,
            'aid' => $this -> aid,
            'status' => 1,
        ];

        $set = [
            'status' => 0,
            'updated' => Mongodb::getMongoDate(),
        ];

        $result = $db->update(Constants::COLL_APPRAISAL_ELEMENT, $cond, ['$set' => $set]);

        if ($result) {

            // 要素题删除
            $cond_question = [
                'aid' => $this->aid,
                'status' => 1,
                'element_id' => $id
            ];

            $db->update(Constants::COLL_APPRAISAL_ELEMENT_QUESTION, $cond_question, ['$set' => $set]);

            return $id;
        }

        return false;
    }

/** = = = = = =下面部分：要素题 = = = = = = **/

    /**
     * 要素题创建
     *
     * @param $element_id 要素ID
     * @param $records 要素题创建时：字段详细验证
     */
    public function createQuestion($element_id, $records)
    {
        $db = $this->getMongoMasterConnection();

        $cond = [
            'aid' => $this->aid,
            'id' => $element_id,
        ];

        $fields = ['enable' => 1, 'status' => 1];

        // 要素数据
        $element_res = $db->fetchRow(Constants::COLL_APPRAISAL_ELEMENT, $cond, );

        $data = [];

        // 要素题：处理数组
        foreach ($records as $value) {
            $item = $value->toArray();
            $data[] = [
                'id' => Sequence::getSeparateId(Constants::COLL_APPRAISAL_ELEMENT_QUESTION, $this->aid ?: 1),
                'aid' => $this->aid,
                'eid' => $this->eid,
                'element_id' => $element_id,
                'status' => $element_res['status'] ?: 1,
                'enable' => $element_res['enable'] ?: 1,
                'created' => Mongodb::getMongoDate(),
                'updated' => Mongodb::getMongoDate(),
                'type' =>  $item['type'],
                'name' => $item['name'],
                'desc' => $item['desc'],
                'options' => $item['options'] ?: [],
                'order' => $item['order'],
                'score' => $item['score'],
            ];
        }

        if ($data) {

            // 批量插入要素题
            $db->batchInsert(Constants::COLL_APPRAISAL_ELEMENT_QUESTION, $data);

            $cond = [
                'id' => $element_id,
                'aid' => $this->aid,
                'status' => 1,
            ];

            // 要素题数量
            $set = [
                'questions_total' => count($data),
                'updated' => Mongodb::getMongoDate(),
            ];

            // 更新要素表：要素题数量
            $db->update(Constants::COLL_APPRAISAL_ELEMENT, $cond, ['$set' => $set]);
        }

        return true;
    }

    /**
     * 要素题列表
     *
     * @param $element_id 要素ID
     * @param $pg 分页
     * @param &$total 总条数
     */
    public function detailQuestion($element_id, $pg, &$total)
    {
        $db = $this->getMongoMasterConnection();

        // 处理 element_id 为数组时的情况
        if (is_array($element_id)) {
            $element_id = ['$in' => $element_id];
        }

        $cond = [
            'aid' => $this->aid,
            'element_id' => $element_id,
            'status' => 1,
        ];

        if (! $pg) {
            $pg = new Pagination();
            $pg->setPage(0);
            $pg->setItemsPerPage(0);
        }

        $result = $db->fetchAll(Constants::COLL_APPRAISAL_ELEMENT_QUESTION, $cond, $pg->getOffset(), $pg->getItemsPerPage(), ['order' => 1], ['_id' => 0]);

        foreach ($result as &$value) {
            $value['type_name'] = $this->getQuestionName($value['type']);
        }

        Utils::convertMongoDateToTimestamp($value);

        // 总条数
        $total = $db->count(Constants::COLL_APPRAISAL_ELEMENT_QUESTION, $cond);

        return $result ?: [];
    }


    /**
     * 要素题编辑
     *
     * @param $element_id 要素ID
     * @param $records 要素题编辑时：字段详细验证
     */
    public function updateQuestion($element_id, $records)
    {
        $db = $this->getMongoMasterConnection();

        $cond = [
            'element_id' => $element_id,
            'status' => 1,
            'aid' => $this->aid
        ];

        // 查询该要素下，数据库中存的要素题
        $question_res = $db->fetchAll(Constants::COLL_APPRAISAL_ELEMENT_QUESTION, $cond, 0, 0, [], ['id' => 1]);

        // 数据库中：要素题ID
        $question_ids = array_column($question_res, 'id');

        $cond = [
            'element_id' => $element_id,
            'aid' => $this->aid,
            'status' => 1,
        ];

        $questions_total = 0;
        $update_ids = [];
        foreach ($records as $value) {
            $item = $value->toArray();

            // 编辑时候，更新的要素题
            if ($item['id']) {
                $cond = [
                    'id' => $item['id'],
                ];
                $item['updated'] = Mongodb::getMongoDate();
                $db->update(Constants::COLL_APPRAISAL_ELEMENT_QUESTION, $cond, ['$set' => $item]);
                $update_ids[] = $item['id'];
            // 编辑时候，新增的要素题
            } else {
                $item['id'] = Sequence::getSeparateId(Constants::COLL_APPRAISAL_ELEMENT_QUESTION, $this->aid ?: 1);
                $item['aid'] = $this->aid;
                $item['element_id'] = $element_id;
                $item['eid'] = $this->eid;
                $item['status'] = 1;
                $item['enable'] = 1;
                $item['created'] = Mongodb::getMongoDate();
                $item['updated'] = Mongodb::getMongoDate();
                $db->insert(Constants::COLL_APPRAISAL_ELEMENT_QUESTION, $item);
            }

            $questions_total++;
        }

        // 删除要素题ID：返回在 question_ids 中但是不在 update_ids 里的值
        $delete_ids = array_diff($question_ids, $update_ids);

        if (is_array($delete_ids) && $delete_ids) {
            // 要素题表
            $cond_question = [
                'id' => ['$in' => array_values($delete_ids)],
                'aid' => $this->aid,
                'element_id' => $element_id,
                'status' => 1,
            ];

            $set_question = [
                'status' => 0,
                'updated' => Mongodb::getMongoDate(),
            ];

            // 删除无用的要素题
            $db->update(Constants::COLL_APPRAISAL_ELEMENT_QUESTION, $cond_question, ['$set' => $set_question]);
        }

        // 更新要素表中，要素题的数量
        $cond_em = [
            'id' => $element_id,
            'aid' => $this->aid,
            'status' => 1,
        ];

        $set_em = [
            'questions_total' => $questions_total,
            'updated' => Mongodb::getMongoDate(),
        ];

        $db->update(Constants::COLL_APPRAISAL_ELEMENT, $cond_em, ['$set' => $set_em]);

        return true;
    }

    /**
     * 题型：ID 转文字
     */
    public function getQuestionName($type)
    {
        if (!$type) {
            return '';
        }

        switch ($type) {
            case 1:
                return '单选打分题';
            case 2:
                return '多选打分题';
            case 3:
                return '文本题';
        }

        return '';
    }

    /**
     * 批量插入要素题
     */
    public function createElementQuestion($data)
    {
        $data['id'] = Sequence::getSeparateId(Constants::COLL_APPRAISAL_ELEMENT_QUESTION, $this->aid ?: 1);
        $data['aid'] = $this->aid;

        return $this->getMongoMasterConnection()->insert(Constants::COLL_APPRAISAL_ELEMENT_QUESTION, $data);
    }

    /**
     * 批量导入时更新要素题数量
     */
    public function countQuestionNum($element_id)
    {
        $db = $this->getMongoMasterConnection();

        // 统计要素题的总数
        $cond = [
            'aid' => $this->aid,
            'element_id' => $element_id,
            'status' => 1,
        ];

        $total = $db->count(Constants::COLL_APPRAISAL_ELEMENT_QUESTION, $cond);

        // 更新要素中，要素题数量字段
        $cond = [
            'aid' => $this->aid,
            'status' => 1,
            'id' => $element_id,
        ];

        $set = [
            'questions_total' => $total,
            'updated' => Mongodb::getMongoDate(),
        ];

        $db->update(Constants::COLL_APPRAISAL_ELEMENT, $cond, ['$set' => $set]);
    }
}
