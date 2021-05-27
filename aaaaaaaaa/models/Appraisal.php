<?php
/**
 * 评估活动：model
 * author：天尽头流浪
 */

namespace App\Models;

use App\Utils;
use MongoDB\BSON\Regex;
use App\Common\Sequence;
use App\Common\BaseModel;
use App\Common\Constants;
use Key\Database\Mongodb;
use Key\Records\Pagination;
use App\Models\EnterpriseQuestionnaire;

class EnterpriseAppraisal extends BaseModel
{
    /**
     * 查询编号是否存在
     */
    public function getExistsNo($no, $id = 0)
    {
        $db = $this->getMongoMasterConnection();

        $cond = [
            'id' => ['$ne' => $id],
            'aid' => $this->aid,
            'status' => 1,
            'no' => $no,
        ];

        $res = $db->fetchRow(Constants::COLL_ENTERPRISE_APPRAISAL, $cond);

        return $res ?: [];
    }

    /**
     * 评估创建
     */
    public function create($record)
    {
        $db = $this->getMongoMasterConnection();

        // 开始时间：转化成 mongodb 的时间格式
        if($record['start_time']) {
            $record['start_time'] = Mongodb::getMongoDate((string)$record['start_time'] / 1000);
        }

        // 结束时间：转化成 mongodb 的时间格式
        if($record['end_time']) {
            $record['end_time'] = Mongodb::getMongoDate((string)$record['end_time'] / 1000);
        }

        $default_data = [
            'id' =>  Sequence::getSeparateId(Constants::COLL_ENTERPRISE_APPRAISAL, $this->aid),
            'aid' => $this->aid,
            'eid' => $this->eid,
            'status' => 1,
            'is_publish' => 0,
            'is_over' => 0,
            'created' => Mongodb::getMongoDate(),
            'updated' => Mongodb::getMongoDate(),
        ];

        $data_new = array_merge($default_data, $record);

        $result = $db->insert(Constants::COLL_ENTERPRISE_APPRAISAL, $data_new);

        return $result ? $data_new['id'] : 0;
    }

    /**
     * 评估编辑
     */
    public function update($id, $record)
    {
        $db = $this->getMongoMasterConnection();

        $cond = [
            'aid' => $this->aid,
            'status' => 1,
            'id' => $id
        ];

        // 开始时间：转化成 mongodb 的时间格式
        if($record['start_time']) {
            $record['start_time'] = Mongodb::getMongoDate((string)$record['start_time'] / 1000);
        }

        // 结束时间：转化成 mongodb 的时间格式
        if($record['end_time']) {
            $record['end_time'] = Mongodb::getMongoDate((string)$record['end_time'] / 1000);
        }

        $record['updated'] = Mongodb::getMongoDate();

        $result = $db->update(Constants::COLL_ENTERPRISE_APPRAISAL, $cond, ['$set' => $record]);

        return $result ?: [];
    }

    /**
     * 评估列表
     */
    public function list($type = 0, $keyword = '', $pg = null, &$total)
    {
        $db = $this->getMongoMasterConnection();

        $cond = [
            'aid' => $this->aid,
            'status' => 1,
        ];

        // 关键字
        if ($keyword) {
            $cond['$or'] = [
                ['id' => (int)$keyword],
                ['title' => new Regex($keyword, 'im')],
            ];
        }

        // 状态：0、全部；1、未开始；2、进行中；3、已结束；4、草稿
        if (in_array($type, [1, 2, 3])) {

            // 已发布
            $cond['is_publish'] = 1;

            if ($type == 1) {
                $cond['is_over'] = 0;
                $cond['start_time'] = ['$gt' => Mongodb::getMongoDate()];
            } else if ($type == 2) {
                $cond['is_over'] = 0;
                $cond['start_time'] = ['$lt' => Mongodb::getMongoDate()];
                $cond['end_time'] = ['$gt' => Mongodb::getMongoDate()];
            } else if ($type == 3) {

                $cond['$or'] = [
                    ['is_over' => 1],
                    ['end_time' => ['$lt' => Mongodb::getMongoDate()]],
                ];
            }
        } else if ($type == 4) {

            // 草稿：未发布
            $cond['is_publish'] = 0;
            $cond['is_over'] = 0;
        }

        // 分页
        if (!$pg) {
            $pg = new Pagination();
            $pg->setPage(0);
            $pg->setItemsPerPage(0);
        }

        // 查询数据
        $result = $db->fetchAll(Constants::COLL_ENTERPRISE_APPRAISAL, $cond, $pg->getOffset(), $pg->getItemsPerPage(), ['updated' => -1]);

        foreach ($result as &$value) {
            if ($value['is_publish'] == 0) {
                $value['appraisal_status'] = '未发布';
                $value['appraisal_status_code'] = -1;
            } else if ($value['is_publish'] == 1) {
                if ($value['is_over'] == 0) {
                    if ($value['start_time'] > Mongodb::getMongoDate()) {
                        $value['appraisal_status'] = '未开始';
                        $value['appraisal_status_code'] = 1;
                    } else if ($value['start_time'] <= Mongodb::getMongoDate() && $value['end_time'] >= Mongodb::getMongoDate()) {
                        $value['appraisal_status'] = '进行中';
                        $value['appraisal_status_code'] = 2;
                    } else if ($value['end_time'] <= Mongodb::getMongoDate()) {
                        $value['appraisal_status'] = '已结束';
                        $value['appraisal_status_code'] = 3;
                    } else {
                        $value['appraisal_status'] = '活动时间异常';
                        $value['appraisal_status_code'] = -1;
                    }
                } else {
                    $value['appraisal_status'] = '已结束';
                    $value['appraisal_status_code'] = 3;
                }
            } else {
                $value['appraisal_status'] = '发布状态异常';
                $value['appraisal_status_code'] = -1;
            }
        }
        Utils::convertMongoDateToTimestamp($result);

        // 统计总数
        $total = $db->count(Constants::COLL_ENTERPRISE_APPRAISAL, $cond);

        return $result ?: [];
    }

    /**
     * 评估详情
     */
    public function detail($id)
    {
        $db = $this->getMongoMasterConnection();

        $cond = [
            'id' => $id,
            'aid' => $this->aid,
            'status' => 1,
        ];

        // 查询详情
        $result = $db->fetchRow(Constants::COLL_ENTERPRISE_APPRAISAL, $cond);
        Utils::convertMongoDateToTimestamp($result);

        return $result ?: [];
    }

    /**
     * 评估删除
     */
    public function delete($id)
    {
        $db = $this->getMongoMasterConnection();

        $cond = [
            'id' => $id,
            'aid' => $this->aid,
            'status' => 1,
        ];

        $set = [
            'status' => 0,
            'updated' => Mongodb::getMongoDate(),
        ];

        $result = $db->update(Constants::COLL_ENTERPRISE_APPRAISAL, $cond, ['$set' => $set]);

        return $result ?: 0;
    }

    /**
     * 问卷设置：添加评估关系
     */
    public function setRelations($id, $questionnaire, $relations)
    {
        $db = $this->getMongoMasterConnection();

        $cond = [
            'id' => $id,
            'aid' => $this->aid,
            'status' => 1,
        ];

        $set = [
            'questionnaire' => $questionnaire,
            'relations' => $relations,
            'updated' => Mongodb::getMongoDate(),
        ];

        $result = $db->update(Constants::COLL_ENTERPRISE_APPRAISAL, $cond, ['$set' => $set]);

        return $result ?: 0;
    }

    /**
     * 评估关系详情创建
     */
    public function createRelationsInfo($a_id, $data)
    {
        $db = $this->getMongoMasterConnection();

        $default_new = [
            'id' =>  Sequence::getSeparateId(Constants::COLL_ENTERPRISE_APPRAISAL_RELATIONS, $this->aid),
            'aid' => $this->aid,
            'eid' => $this->eid,
            'a_id' => $a_id,
            'status' => 1,
            'is_answer' => 0, // 是否评估：0、未评估；1、已评估
            'score' => 0, // 评估完成，计算分数
            'created' => Mongodb::getMongoDate(),
            'updated' => Mongodb::getMongoDate(),
        ];

        $data_new = array_merge($default_new, $data);

        $result = $db->insert(Constants::COLL_ENTERPRISE_APPRAISAL_RELATIONS, $data_new);

        return $result ? $data_new['id'] : 0;
    }

    /**
     * 评估关系详情列表
     */
    public function relationsInfoListList($a_id = 0, $keyword = '', $pg = null, &$total)
    {
        $db = $this->getMongoMasterConnection();

        $cond = [
            'aid' => $this->aid,
            'a_id' => $a_id,
            'status' => 1,
        ];

        // 关键字
        if ($keyword) {
            $cond['$or'] = [
                ['assessed_people_no' => new Regex($keyword, 'im')],
                ['assessed_people_name' => new Regex($keyword, 'im')],
            ];
        }

        // 分页
        if (!$pg) {
            $pg = new Pagination();
            $pg->setPage(0);
            $pg->setItemsPerPage(0);
        }

        // 查询结果
        $result = $db->fetchAll(Constants::COLL_ENTERPRISE_APPRAISAL_RELATIONS, $cond, $pg->getOffset(), $pg->getItemsPerPage(), ['updated' => -1]);
        Utils::convertMongoDateToTimestamp($result);

        // 统计总数
        $total = $db->count(Constants::COLL_ENTERPRISE_APPRAISAL_RELATIONS, $cond);

        return $result ?: [];
    }

    /**
     * 评估关系详情删除
     */
    public function deleteRelationsInfo($id)
    {
        $db = $this->getMongoMasterConnection();

        $cond = [
            'id' => $id,
            'aid' => $this->aid,
            'status' => 1,
        ];

        $set = [
            'status' => 0,
            'updated' => Mongodb::getMongoDate(),
        ];

        $result = $db->update(Constants::COLL_ENTERPRISE_APPRAISAL_RELATIONS, $cond, ['$set' => $set]);

        return $result ?: [];
    }

    /**
     * 评估活动发布
     */
    public function publish($id)
    {
        $db = $this->getMongoMasterConnection();

        $cond = [
            'id' => $id,
            'aid' => $this->aid,
            'status' => 1,
        ];

        $set = [
            'is_publish' => 1,
            'updated' => Mongodb::getMongoDate(),
        ];

        $res = $db->update(Constants::COLL_ENTERPRISE_APPRAISAL, $cond, ['$set' => $set]);

        return $res;
    }

    /**
     * 评估活动结束
     */
    public function setOver($id)
    {
        $db = $this->getMongoMasterConnection();

        $cond = [
            'id' => $id,
            'aid' => $this->aid,
            'status' => 1,
            'is_publish' => 1,
        ];

        $set = [
            'is_over' => 1,
            'updated' => Mongodb::getMongoDate(),
        ];

        $res = $db->update(Constants::COLL_ENTERPRISE_APPRAISAL, $cond, ['$set' => $set]);

        return $res;
    }

/** = = = = = = = = =  = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = **
 *                                     下方是移动端接口                                 *
 ** = = = = = = = = = = = = = =  = = = = = = = = = = = = = = = = = = = = = = = = = = **/

    /**
     * 评估列表：查询“我”作为评估人时，将要评估的活动
     *
     * @param $type = 0 【状态：0-全部；1-未开始；2-进行中；3-已结束】
     * @param $is_answer = 0 【是否回答：0-全部；1-已回答；2-未回答】
     * @param $keywords = '' 【关键字：活动标题】
     * @param $pg = null 【分页】
     * @param &$total  【总条数】
     *
     * @return array
     */
    public function getMyAppraisalsV1($type = 0, $is_answer = 0, $keywords = '', $pg = null, &$total)
    {
        $db = $this->getMongoMasterConnection();

        // 获取员工编号
        $employee_model = new Employee($this->app);
        $employeeInfo = $employee_model->view($this->eid);
        $no = ArrayGet($employeeInfo, 'no', '');

        $cond = [
            'aid' => $this->aid,
            'assessed_people_no' => $no ?: '',
        ];

        if ($is_answer != 0) {
            switch ($is_answer) {
                case 1:
                    $cond['is_answer'] = 1;
                    break;
                case 2:
                    $cond['is_answer'] = 0;
                    break;
            }
        }

        // 查询“我”是评估人的所有活动
        $appraisal_relations = $db->fetchAll(Constants::COLL_ENTERPRISE_APPRAISAL_RELATIONS, $cond, 0, 0, [], ['a_id' => 1]);

        // 把评估活动ID提取出来
        $appraisal_ids = array_values(array_unique(array_column($appraisal_relations, 'a_id')));

        // 查询评估活动的条件
        $cond = [
            'status' => 1,
            'is_publish' => 1,
            'aid' => $this->aid,
            'id' => [
                '$in' => $appraisal_ids,
            ],
        ];

        // 状态：0、全部；1、未开始；2、进行中；3、已结束；
        if (in_array($type, [1, 2, 3])) {
            // 已发布
            if ($type == 1) {
                $cond['is_over'] = 0;
                $cond['start_time'] = ['$gt' => Mongodb::getMongoDate()];
            } else if ($type == 2) {
                $cond['is_over'] = 0;
                $cond['start_time'] = ['$lt' => Mongodb::getMongoDate()];
                $cond['end_time'] = ['$gt' => Mongodb::getMongoDate()];
            } else if ($type == 3) {
                $cond['$or'] = [
                    ['is_over' => 1],
                    ['end_time' => ['$lt' => Mongodb::getMongoDate()]],
                ];
            }
        }

        // 关键字：评估名称
        if ($keywords) {
            $cond['title'] = new Regex($keywords, 'im');
        }

        // 分页
        if (! $pg) {
            $pg = new Pagination();
            $pg->setPage(0);
            $pg->setItemsPerPage(0);
        }

        $fields = [
            'id' => 1,
            'no' => 1,
            'desc' => 1,
            'title' => 1,
            'cover' => 1,
            'is_over' => 1,
            'end_time' => 1,
            'start_time' => 1,
            'is_publish' => 1,
        ];

        $result = $db->fetchAll(Constants::COLL_ENTERPRISE_APPRAISAL, $cond, $pg->getOffset(), $pg->getItemsPerPage(), ['updated' => -1], $fields);

        foreach ($result as &$value) {

            // 格式化【评估活动】数据：进行状态、是否已回答
            $value = $this->formatAppraisalData($value);
        }

        Utils::convertMongoDateToTimestamp($result);

        $total = $db->count(Constants::COLL_ENTERPRISE_APPRAISAL, $cond);

        return $result ?: [];
    }

    /**
     * 评估活动：某一个评估活动详情
     *
     * @param $a_id【评估ID】
     *
     * @return array
     */
    public function getOneAppraisalInfoV1($a_id)
    {
        $db = $this->getMongoMasterConnection();

        // 查询全部的时候，查询【我】是否回答
        $cond = [
            'id' => $a_id,
            'aid' => $this->aid,
            'status' => 1,
        ];

        $fields = [
            'id' => 1,
            'no' => 1,
            'desc' => 1,
            'title' => 1,
            'cover' => 1,
            'is_over' => 1,
            'end_time' => 1,
            'start_time' => 1,
            'is_publish' => 1,
        ];

        $appraisal_res = $db->fetchRow(Constants::COLL_ENTERPRISE_APPRAISAL, $cond, $fields);

        // 格式化【评估活动】数据：进行状态、是否已回答
        $result = $this->formatAppraisalData($appraisal_res);

        Utils::convertMongoDateToTimestamp($result);

        return $result;
    }

    /**
     * 格式化【评估活动】数据：进行状态、是否已回答
     *
     * 【状态：0-全部；1-未开始；2-进行中；3-已结束】
     * 【是否回答：0-全部；1-已回答；2-未回答】
     *
     * @param $appraisal_res
     *
     * @return array self
     */
    public function formatAppraisalData($original_data)
    {
        $db = $this->getMongoMasterConnection();

        // 获取员工编号
        $employee_model = new Employee($this->app);
        $employeeInfo = $employee_model->view($this->eid);
        $no = ArrayGet($employeeInfo, 'no', '');

        if ($original_data['is_publish'] == 0) {
            $original_data['appraisal_status'] = '未发布';
            $original_data['appraisal_status_code'] = -1;
        } else if ($original_data['is_publish'] == 1) {
            if ($original_data['is_over'] == 0) {
                if ($original_data['start_time'] > Mongodb::getMongoDate()) {
                    $original_data['appraisal_status'] = '未开始';
                    $original_data['appraisal_status_code'] = 1;
                } else if ($original_data['start_time'] <= Mongodb::getMongoDate() && $original_data['end_time'] >= Mongodb::getMongoDate()) {
                    $original_data['appraisal_status'] = '进行中';
                    $original_data['appraisal_status_code'] = 2;
                } else if ($original_data['end_time'] <= Mongodb::getMongoDate()) {
                    $original_data['appraisal_status'] = '已结束';
                    $original_data['appraisal_status_code'] = 3;
                } else {
                    $original_data['appraisal_status'] = '活动时间异常';
                    $original_data['appraisal_status_code'] = -1;
                }
            } else {
                $original_data['appraisal_status'] = '已结束';
                $original_data['appraisal_status_code'] = 3;
            }
        } else {
            $original_data['appraisal_status'] = '发布状态异常';
            $original_data['appraisal_status_code'] = -1;
        }

        // 查询全部的时候，查询【我】是否回答
        $relation_cond = [
            'a_id' => $original_data['id'],
            'aid' => $this->aid,
            'assessed_people_no' => $no ?: '',
            'status' => 1,
        ];

        $appraisal_res = $db->fetchRow(Constants::COLL_ENTERPRISE_APPRAISAL_RELATIONS, $relation_cond, ['is_answer' => 1]);

        $original_data['is_answer'] = $appraisal_res['is_answer'] == 1 ?: 2;

        return $original_data;
    }

    /**
     * 评估详情：下方的评估对象列表：该评估下，我作为评估人需要评估的所有对象
     *
     * @param $a_id 【评估ID】
     * @param $pg = null【分页】
     * @param &$total 【总条数】
     *
     * @return array
     */
    public function getRelationsV1($a_id, $pg = null, &$total)
    {
        $db = $this->getMongoMasterConnection();

        // 获取员工编号
        $employee_model = new Employee($this->app);
        $employeeInfo = $employee_model->view($this->eid);
        $no = ArrayGet($employeeInfo, 'no', '');

        $cond = [
            'aid' => $this->aid,
            'a_id' => $a_id,
            'assessed_people_no' => $no ?: '',
            'status' => 1,
        ];

        $fields = [
            'id' => 1,
            'a_id' => 1,
            'is_answer' => 1,
            'relation_id' => 1,
            'relation_name' => 1,
            'be_assessed_people_no' => 1,
            'be_assessed_people_name' => 1,
        ];

        // 分页
        if (! $pg) {
            $pg = new Pagination();
            $pg->setPage(0);
            $pg->setItemsPerPage(0);
        }

        // 查询“我”是评估人的所有对评估对象
        $result = $db->fetchAll(Constants::COLL_ENTERPRISE_APPRAISAL_RELATIONS, $cond, $pg->getOffset(), $pg->getItemsPerPage(), ['updated' => 1], $fields);

        foreach ($result as &$value) {
            switch ($value['is_answer']) {
                case '1':
                    $value['answer_status'] = '已评估';
                    break;
                default:
                    $value['answer_status'] = '未评估';
                    break;
            }
        }
        Utils::convertMongoDateToTimestamp($result);

        $total = $db->count(Constants::COLL_ENTERPRISE_APPRAISAL_RELATIONS, $cond);

        return $result ?: [];
    }

    /**
     * 评估活动页面的所有题目
     *
     * @param $a_id 【评估ID】
     * @param $type 【是否获取答案：0-不获取；1-获取】
     *
     * @return array
     */
    public function allQuestionsV1($a_id, $type = 0)
    {
        $db = $this->getMongoMasterConnection();

        $enterpriseQuestionnaireModel = new EnterpriseQuestionnaire($this->app);

        // 查询评估活动的条件
        $cond = [
            'status' => 1,
            'is_publish' => 1,
            'aid' => $this->aid,
            'id' => $a_id,
        ];

        $fields = [
            'id' => 1,
            'aid' => 1,
            'status' => 1,
            'srart_time' => 1,
            'end_time' => 1,
            'cover' => 1,
            'questionnaire' => 1,
        ];

        // 查询评估详情
        $result = $db->fetchRow(Constants::COLL_ENTERPRISE_APPRAISAL, $cond, $fields);
        Utils::convertMongoDateToTimestamp($result);

        // 查询该评估活动对应的问卷，所有模型-要素-题目
        foreach ($result['questionnaire'] as $questionnaire_key => $questionnaire_value) {

            // 查询问卷详情
            $questionnaire_res = $enterpriseQuestionnaireModel->getSetting($questionnaire_value['questionnaire_id'], $type);
            $result['questionnaire'][$questionnaire_key]['questionnaire_info'] = $questionnaire_res;

            // 是否返回答案
            if ($type == 1) {

                // 获取员工编号
                $employee_model = new Employee($this->app);
                $employeeInfo = $employee_model->view($this->eid);
                $no = ArrayGet($employeeInfo, 'no', '');

                // 遍历问卷下的模型
                foreach ($questionnaire_res['models_info'] as $model_key => $model_value) {
                    // 遍历能力模型下的维续
                    foreach ($model_value['dimension'] as $dimension_key => $dilemsion_value) {
                        // 遍历纬度下的要素
                        foreach ($dilemsion_value['element'] as $element_key => $element_value) {
                            // 遍历要素下的题目
                            foreach ($element_value['question'] as $question_key => $question_value) {
                                // 查询该题的答案
                                $cond = [
                                    'aid' => $this->aid,
                                    'a_id' => $a_id,
                                    'questionnaire_id' => $questionnaire_res['id'],
                                    'model_id' => $model_value['id'],
                                    'dimension_id' => $dilemsion_value['id'],
                                    'element_id' => $element_value['id'],
                                    'question_id' => $question_value['id'],
                                    'assessed_people_no' => $no,
                                    'status' => 1,
                                ];

                                $fields = [
                                    'id' => 1,
                                    'question_answer' => 1,
                                    'question_score' => 1,
                                ];
                                $answer_res = $db->fetchRow(Constants::COLL_ENTERPRISE_APPRAISAL_ANSWERS, $cond);

                                $result['questionnaire'][$questionnaire_key]['questionnaire_info']
                                    ['models_info'][$model_key]
                                    ['dimension'][$dimension_key]
                                    ['element'][$element_key]
                                    ['question'][$question_key]
                                    ['question_answer'] = $answer_res['question_answer'] ?: null;

                                $result['questionnaire'][$questionnaire_key]['questionnaire_info']
                                    ['models_info'][$model_key]
                                    ['dimension'][$dimension_key]
                                    ['element'][$element_key]
                                    ['question'][$question_key]
                                    ['question_score'] = $answer_res['question_score'] ?: null;

                            }
                        }

                    }
                }
            }
        }

        return $result ?: [];
    }

    /**
     * 评估活动：提交答案
     *
     * @param $a_id 【评估活动ID】
     * @param $record【评估答案数据】
     */
    public function createAnswersV1($a_id, $record)
    {
        $db = $this->getMongoMasterConnection();

        // 获取员工编号
        $employee_model = new Employee($this->app);
        $employeeInfo = $employee_model->view($this->eid);
        $no = ArrayGet($employeeInfo, 'no', '');

        $cond = [
            'aid' => $this->aid,
            'a_id' => $a_id,
            'assessed_people_no' => $no ?: '',
            'status' => 1,
        ];

        $relation_res = $db->fetchRow(Constants::COLL_ENTERPRISE_APPRAISAL_RELATIONS, $cond);

        // 答案表：默认数据
        $default_data = [
            'id' =>  Sequence::getSeparateId(Constants::COLL_ENTERPRISE_APPRAISAL_ANSWERS, $this->aid),
            'aid' => $this->aid,
            'eid' => $this->eid,
            'a_id' => $a_id,
            'r_id' => $relation_res['id'],
            'status' => 1,
            'relation_id' => $relation_res['relation_id'],
            'relation_weight' => $relation_res['relation_weight'],
            'be_assessed_people_no' => $relation_res['be_assessed_people_no'],
            'assessed_people_no' => $relation_res['assessed_people_no'],
            'created' => Mongodb::getMongoDate(),
            'updated' => Mongodb::getMongoDate(),
        ];

        // 处理答案
        foreach ($record as $value) {
            $item = $value->toArray();
            $new_data[] = array_merge($default_data, $item);

            // 查询是否重复提交[同一份问卷、同样的关系、属于正常状态]
            $cond = [
                'aid' => $this->aid,
                'eid' => $this->eid,
                'a_id' => $a_id,
                'r_id' => $relation_res['id'],
                'status' => 1,
            ];

            $answer_res = $db->fetchRow(Constants::COLL_ENTERPRISE_APPRAISAL_ANSWERS, $cond);

            // 如果重复提交，把历史记录设置无效【假删除】
            if ($answer_res) {

                $set = [
                    'status' => 0,
                    'updated' => Mongodb::getMongoDate(),
                ];

                $db->update(Constants::COLL_ENTERPRISE_APPRAISAL_ANSWERS, $cond, ['$set' => $set]);
            }
        }

        // 批量插入要素题答案
        $result = $db->batchInsert(Constants::COLL_ENTERPRISE_APPRAISAL_ANSWERS, $new_data);

        if ($result) {

            // 提交答案之后，将对应关系表是否回答字段更新，并计算分数
            $score_info = $this->generateScoreV1($a_id, $relation_res['id']);

            $cond = [
                'id' => $relation_res['id'],
                'aid' => $this->aid,
                'status' => 1,
            ];

            $set = [
                'is_answer' => 1,
                'score_info' => $score_info ?: '',
                'score' => $score_info['questionnaire_score'] ?: 0,
                'updated' => Mongodb::getMongoDate(),
            ];

            $db->update(Constants::COLL_ENTERPRISE_APPRAISAL_RELATIONS, $cond, ['$set' => $set]);

            return true;
        }

        return false;
    }

    /**
     * 评估活动：提交答案 - 计算得分
     */
    public function generateScoreV1($a_id, $r_id)
    {
        $db = $this->getMongoMasterConnection();

        // 评估数据
        $appraisal_res = $this->allQuestionsV1($a_id);

        // 问卷数据
        $questionnaire_res = $appraisal_res['questionnaire'][0]['questionnaire_info'];

        $result = [
            'questionnaire_id' =>$questionnaire_res['id'],
            'qs_num_total' => $questionnaire_res['qs_num_total'],
        ];

        // 问卷得分：初始化
        $questionnaire_score = 0;
        $model_total_weight = array_sum(array_column($questionnaire_res['models_info'], 'weight'));

        // 遍历：模型数组
        foreach ($questionnaire_res['models_info'] as $models_index => $models_info) {
            $result['models'][$models_index]['model_id'] = $models_info['id'];
            $result['models'][$models_index]['model_name'] = $models_info['name'];
            $result['models'][$models_index]['model_weight'] = $models_info['weight'];

            // 模型得分：初始化
            $model_score = 0;
            $dimensions_total_weight = array_sum(array_column($models_info['dimension'], 'weight'));

            // 遍历：纬度数组
            foreach ($models_info['dimension'] as $dimensions_index => $dimensions_info) {
                $result['models'][$models_index]['dimensions'][$dimensions_index]['dimensions_id'] = $dimensions_info['id'];
                $result['models'][$models_index]['dimensions'][$dimensions_index]['dimensions_name'] = $dimensions_info['level_name'];
                $result['models'][$models_index]['dimensions'][$dimensions_index]['dimensions_weight'] = $dimensions_info['weight'];

                // 纬度得分：初始化
                $dimension_score = 0;
                $element_total_weight = array_sum(array_column($dimensions_info['element'], 'weight'));

                // 遍历：要素数组
                foreach ($dimensions_info['element'] as $elements_index => $elements_info) {
                    $result['models'][$models_index]['dimensions'][$dimensions_index]['element'][$elements_index]['elements_id'] = $elements_info['id'];
                    $result['models'][$models_index]['dimensions'][$dimensions_index]['element'][$elements_index]['elements_name'] = $elements_info['name'];
                    $result['models'][$models_index]['dimensions'][$dimensions_index]['element'][$elements_index]['elements_weight'] = $elements_info['weight'];

                    $cond = [
                        'aid' => $this->aid,
                        'a_id' => $a_id,
                        'r_id' => $r_id,
                        'questionnaire_id' => $questionnaire_res['id'],
                        'model_id' => $models_info['id'],
                        'dimension_id' => $dimensions_info['id'],
                        'element_id' => $elements_info['id'],
                        'status' => 1,
                        'question_score' => [
                            '$gt' => 0,
                        ],
                    ];

                    $answers_res = $db->fetchAll(Constants::COLL_ENTERPRISE_APPRAISAL_ANSWERS, $cond, 0, 0, [], ['id' => 1, 'question_name' => 1, 'question_score' => 1]);

                    // 每道题得分 array
                    $score_arr = array_column($answers_res, 'question_score');

                    // 每个要素总得分 int
                    $element_total_score = array_sum($score_arr);

                    // 每个要素已回答总题数
                    $element_total = count($score_arr);

                    if ($element_total == 0) {
                        $element_score = 0;
                    } else {
                        // 要素多道题，平均分
                        $element_score = $element_total_score / $element_total;
                    }

                    // 得分：每个要素
                    $result['models'][$models_index]['dimensions'][$dimensions_index]['element'][$elements_index]['elements_score'] = $element_score ? sprintf("%.2f", $element_score) : 0;
                    $result['models'][$models_index]['dimensions'][$dimensions_index]['element'][$elements_index]['answers_res'] = $answers_res;

                    // 纬度得分：每个要素得分*权重 求和
                    $dimension_score += $element_score * $elements_info['weight'] / $element_total_weight;
                }

                // 得分：每个纬度
                $result['models'][$models_index]['dimensions'][$dimensions_index]['dimensions_score'] = $dimension_score ? sprintf("%.2f", $dimension_score) : 0;

                // 模型得分：每个纬度得分*权重 求和
                $model_score += $dimension_score * $dimensions_info['weight'] / $dimensions_total_weight;
            }

            // 得分：每个模型
            $result['models'][$models_index]['model_score'] = $model_score ? sprintf("%.2f", $model_score) : 0;

            // 问卷得分：每个模型*权重 求和
            $questionnaire_score += $model_score * $models_info['weight'] / $model_total_weight;
        }

        $result['questionnaire_score'] = $questionnaire_score ? sprintf("%.2f", $questionnaire_score) : 0;

        return $result;
    }

    /**
     * 评估活动：某个被评估人 - 综合所有关系 - 计算最终得分
     *
     * @param $a_id 评估活动 ID
     * @param $no 被评估人 工号
     *
     * @return int 分数
     */
    public function getAppraisalFinalScoreV1($a_id)
    {
        // 获取员工编号
        $employee_model = new Employee($this->app);
        $employeeInfo = $employee_model->view($this->eid);
        $no = ArrayGet($employeeInfo, 'no', '');

        $all_relation_fanil_score = $this->getAppraisalAllRelationFinalScore($a_id, $no);

        $final_score = 0;

        // 比重总和
        $all_weight = array_sum(array_column($all_relation_fanil_score, 'weight'));

        foreach ($all_relation_fanil_score as $key => $value) {

            $final_score += sprintf("%.2f",  $value['fanil_score'] * $value['weight'] / $all_weight);
        }

        return $final_score;
    }


    /**
     * 评估活动-统计各个评估关系【应评数量、实评数量】
     *
     * @param $a_id 评估活动 ID
     * @param $no 被评估人 工号
     *
     * @return array
     */
    public function relationResultV1($a_id)
    {
        // 获取员工编号
        $employee_model = new Employee($this->app);
        $employeeInfo = $employee_model->view($this->eid);
        $no = ArrayGet($employeeInfo, 'no', '');

        // 所有评估关系
        $relation_arr = $this->getAppraisalRelation($a_id);

        foreach ($relation_arr as $relation_key => $relation_value) {
            // 查看【评估活动】某个被评估人 - 某种关系下【应评、实评】数据
            $relation_result = $this->getAppraisalRelationResult($a_id, $no, $relation_value['role_id']);

            $relation_arr[$relation_key]['all_num'] = $relation_result['all_num'];
            $relation_arr[$relation_key]['actual_num'] = $relation_result['actual_num'];
        }

        return $relation_arr;
    }

    /**
     * 评估活动：各个要素【他评均分、自评得分、差值】
     */
    public function getEveryElementFinalScoreV1($a_id)
    {
        // 获取员工编号
        $employee_model = new Employee($this->app);
        $employeeInfo = $employee_model->view($this->eid);
        $no = ArrayGet($employeeInfo, 'no', '');

        // 获取【评估活动】所有要素
        $element_arr = $this->getAppraisalAllElements($a_id);

        foreach ($element_arr as $element_key => $element_value) {

            // 每个要素下：【他评得分】
            $other_score = $this->getOneQuestionFinalScore(
                $a_id,
                $element_value['questionnaire_id'],
                $element_value['model_id'],
                $element_value['dimension_id'],
                $element_value['element_id'],
                0, // 0-所有问题
                $no,
                0, // 0-所有关系
                2 // 2-他评
            );

            $element_arr[$element_key]['other_score'] = sprintf("%.2f", $other_score);

            // 每个要素下：【自评得分】
            $self_score = $this->getOneQuestionFinalScore(
                $a_id,
                $element_value['questionnaire_id'],
                $element_value['model_id'],
                $element_value['dimension_id'],
                $element_value['element_id'],
                0, // 0-所有问题
                $no,
                0, // 0-所有关系
                1 // 2-自评
            );

            $element_arr[$element_key]['self_score'] = sprintf("%.2f", $self_score);
            $element_arr[$element_key]['diff_score'] = sprintf("%.2f", ($other_score - $self_score));
        }

        return $element_arr;
    }

    /**
     * 评估活动：【各个要素】【各种关系】得分
     */
    public function getEveryElementWithRelationFinalScoreV1($a_id)
    {
        // 获取员工编号
        $employee_model = new Employee($this->app);
        $employeeInfo = $employee_model->view($this->eid);
        $no = ArrayGet($employeeInfo, 'no', '');

        // 获取【评估活动】所有要素
        $element_arr = $this->getAppraisalAllElements($a_id);

        // 获取【评估活动】所有关系
        $relation_arr = $this->getAppraisalRelation($a_id);

        foreach ($element_arr as $element_key => $element_value) {

            // 每个要素下，各个关系的得分
            $element_arr[$element_key]['relations'] = $relation_arr;
            foreach ($relation_arr as $relation_key => $relation_value) {

                // 每个要素下【某种关系】【含：他评、自评】
                $score = $this->getOneQuestionFinalScore(
                    $a_id,
                    $element_value['questionnaire_id'],
                    $element_value['model_id'],
                    $element_value['dimension_id'],
                    $element_value['element_id'],
                    0, // 0-所有问题
                    $no,
                    $relation_value['role_id'],
                    0 // 0-全部
                );
                $element_arr[$element_key]['relations'][$relation_key]['score'] = $score;
            }

            // 每个要素下：【他评得分】
            $other_score = $this->getOneQuestionFinalScore(
                $a_id,
                $element_value['questionnaire_id'],
                $element_value['model_id'],
                $element_value['dimension_id'],
                $element_value['element_id'],
                0, // 0-所有问题
                $no,
                0, // 0-所有关系
                2 // 2-他评
            );

            $element_arr[$element_key]['other_score'] = sprintf("%.2f", $other_score);

            // 每个要素下：【自评得分】
            $self_score = $this->getOneQuestionFinalScore(
                $a_id,
                $element_value['questionnaire_id'],
                $element_value['model_id'],
                $element_value['dimension_id'],
                $element_value['element_id'],
                0,
                $no,
                0, // 0-所有关系
                1 // 2-自评
            );

            $element_arr[$element_key]['self_score'] = sprintf("%.2f", $self_score);
            $element_arr[$element_key]['diff_score'] = sprintf("%.2f", ($other_score - $self_score));
        }

        return $element_arr;
    }

    /**
     * 评估活动：指定要素-所有题目-【他评均分、自评得分、差值】
     */
    public function getQuestionFinalScoreV1($a_id)
    {
        // 获取员工编号
        $employee_model = new Employee($this->app);
        $employeeInfo = $employee_model->view($this->eid);
        $no = ArrayGet($employeeInfo, 'no', '');

        // 获取【评估活动】- 所有要素 - 所有题目
        $all_questions = $this->getAppraisalAllQuestions($a_id);

        foreach ($all_questions as $element_key => $element_value) {

            foreach ($element_value['questions'] as $question_key => $question_value) {

                // 每个要素下：【他评得分】
                $other_score = $this->getOneQuestionFinalScore(
                    $a_id,
                    $element_value['questionnaire_id'],
                    $element_value['model_id'],
                    $element_value['dimension_id'],
                    $element_value['element_id'],
                    $question_value['id'],
                    $no,
                    0, // 0-所有关系
                    2 // 2-他评
                );

                $all_questions[$element_key]['questions'][$question_key]['other_score'] = sprintf("%.2f", $other_score);

                // 每个要素下：【自评得分】
                $self_score = $this->getOneQuestionFinalScore(
                    $a_id,
                    $element_value['questionnaire_id'],
                    $element_value['model_id'],
                    $element_value['dimension_id'],
                    $element_value['element_id'],
                    $question_value['id'],
                    $no,
                    0, // 0-所有关系
                    1 // 2-自评
                );

                $all_questions[$element_key]['questions'][$question_key]['self_score'] = sprintf("%.2f", $self_score);
                $all_questions[$element_key]['questions'][$question_key]['diff_score'] = sprintf("%.2f", ($other_score - $self_score));
            }
        }

        return $all_questions;
    }

    /**
     * 评估活动：指定要素-所有题目-所有关系-得分
     */
    public function getQuestionWithRelationFinalScoreV1($a_id)
    {
        // 获取员工编号
        $employee_model = new Employee($this->app);
        $employeeInfo = $employee_model->view($this->eid);
        $no = ArrayGet($employeeInfo, 'no', '');

        // 获取【评估活动】- 所有要素 - 所有题目
        $all_questions = $this->getAppraisalAllQuestions($a_id);

        // 获取【评估活动】所有关系
        $relation_arr = $this->getAppraisalRelation($a_id);

        foreach ($all_questions as $element_key => $element_value) {

            foreach ($element_value['questions'] as $question_key => $question_value) {

                // 每道题，各个关系的得分
                $all_questions[$element_key]['questions'][$question_key]['relations'] = $relation_arr;

                foreach ($relation_arr as $relation_key => $relation_value) {

                    // 每道题【某种关系】【含：他评、自评】
                    $score = $this->getOneQuestionFinalScore(
                        $a_id,
                        $element_value['questionnaire_id'],
                        $element_value['model_id'],
                        $element_value['dimension_id'],
                        $element_value['element_id'],
                        $question_value['id'],
                        $no,
                        $relation_value['role_id'],
                        0 // 0-全部
                    );
                    $all_questions[$element_key]['questions'][$question_key]['relations'][$relation_key]['score'] = $score;
                }

                // 每道题：【他评得分】
                $other_score = $this->getOneQuestionFinalScore(
                    $a_id,
                    $element_value['questionnaire_id'],
                    $element_value['model_id'],
                    $element_value['dimension_id'],
                    $element_value['element_id'],
                    $question_value['id'],
                    $no,
                    0, // 0-所有关系
                    2 // 2-他评
                );

                $all_questions[$element_key]['questions'][$question_key]['other_score'] = sprintf("%.2f", $other_score);

                // 每道题：【自评得分】
                $self_score = $this->getOneQuestionFinalScore(
                    $a_id,
                    $element_value['questionnaire_id'],
                    $element_value['model_id'],
                    $element_value['dimension_id'],
                    $element_value['element_id'],
                    $question_value['id'],
                    $no,
                    0, // 0-所有关系
                    1 // 2-自评
                );

                $all_questions[$element_key]['questions'][$question_key]['self_score'] = sprintf("%.2f", $self_score);
                $all_questions[$element_key]['questions'][$question_key]['diff_score'] = sprintf("%.2f", ($other_score - $self_score));
            }
        }

        return $all_questions;
    }

    /**
     * 评估活动：优势项、劣势项展示【以题为单位】
     */
    public function getEveryQuestionOrderV1($a_id, $type = 1)
    {
        $db = $this->getMongoMasterConnection();

        // 获取员工编号
        $employee_model = new Employee($this->app);
        $employeeInfo = $employee_model->view($this->eid);
        $no = ArrayGet($employeeInfo, 'no', '');

        $all_element_questions = $this->getAppraisalAllQuestions($a_id);

        $all_questions = [];
        foreach ($all_element_questions as $element_value) {
            foreach ($element_value['questions'] as $question_value) {

                // 获取答案
                $cond = [
                    'aid' => $this->aid,
                    'a_id' => $a_id,
                    'questionnaire_id' => $element_value['questionnaire_id'],
                    'model_id' => $element_value['model_id'],
                    'dimension_id' => $element_value['dimension_id'],
                    'element_id' => $element_value['element_id'],
                    'question_id' => $question_value['id'],
                    'be_assessed_people_no' => $no,
                    'status' => 1,
                ];

                $fields = [
                    'id' => 1,
                    'question_answer' => 1,
                    'question_score' => 1,
                ];

                // 查询答案数据库
                $answer_res = $db->fetchRow(Constants::COLL_ENTERPRISE_APPRAISAL_ANSWERS, $cond, $fields);

                if ($answer_res) {

                    $all_questions[] = [
                        'questionnaire_id' => $element_value['questionnaire_id'],
                        'model_id' => $element_value['model_id'],
                        'dimension_id' => $element_value['dimension_id'],
                        'element_id' => $element_value['element_id'],
                        'element_name' => $element_value['name'],
                        'question_id' => $question_value['id'],
                        'question_name' => $question_value['name'],
                        'question_answer' => $answer_res['question_answer'] ?: '',
                        'question_score' => $answer_res['question_score'] ?: 0,
                    ];
                }
            }
        }

        // 类型：1-优势项；2-劣势项
        if ($type == 1) {
            $sort = 'SORT_DESC';
        } else {
            $sort = 'SORT_ASC';
        }

        // 执行排序
        $question_score = array_column($all_questions, 'question_score');
        array_multisort($question_score, $sort, $all_questions);

        return $all_questions;
    }

    /**
     * 评估活动：文本描述题 - 统计
     */
    public function getEveryQuestionTextTopicV1($a_id)
    {
        $db = $this->getMongoMasterConnection();

        // 获取员工编号
        $employee_model = new Employee($this->app);
        $employeeInfo = $employee_model->view($this->eid);
        $no = ArrayGet($employeeInfo, 'no', '');

        // 评估活动详情：获取评估关系
        $cond = [
            'aid' => $this->aid,
            'id' => $a_id,
            'status' => 1,
        ];

        $fields = [
            'id' => 1,
            'relations' => 1,
        ];

        $appraisal_res = $db->fetchRow(Constants::COLL_ENTERPRISE_APPRAISAL, $cond, $fields);

        foreach ($appraisal_res['relations'] as $value) {

            $relstion_arr[$value['role_id']] = $value['role_name'];
        }

        // 获取所有文本题
        $all_element_questions = $this->getAppraisalAllQuestions($a_id, [3]);

        $all_questions = [];
        foreach ($all_element_questions as $element_value) {
            foreach ($element_value['questions'] as $question_value) {

                // 获取答案
                $cond = [
                    'aid' => $this->aid,
                    'a_id' => $a_id,
                    'questionnaire_id' => $element_value['questionnaire_id'],
                    'model_id' => $element_value['model_id'],
                    'dimension_id' => $element_value['dimension_id'],
                    'element_id' => $element_value['element_id'],
                    'question_id' => $question_value['id'],
                    'be_assessed_people_no' => $no,
                    'status' => 1,
                ];

                $fields = [
                    'id' => 1,
                    'relation_id' => 1,
                    'question_answer' => 1,
                ];

                // 查询答案数据库
                $answer_res = $db->fetchAll(Constants::COLL_ENTERPRISE_APPRAISAL_ANSWERS, $cond, 0, 0, [], $fields);

                foreach ($answer_res as $answer_key => &$answer_value) {
                    $answer_value['relation_name'] = $relstion_arr[$answer_value['relation_id']];
                }

                $all_questions[] = [
                    'questionnaire_id' => $element_value['questionnaire_id'],
                    'model_id' => $element_value['model_id'],
                    'dimension_id' => $element_value['dimension_id'],
                    'element_id' => $element_value['element_id'],
                    'element_name' => $element_value['name'],
                    'question_id' => $question_value['id'],
                    'question_name' => $question_value['name'],
                    'question_answer' => $answer_res ?: [],
                ];
            }
        }

        print_r($all_questions);die;
    }

    /**
     * 评估活动：所有题目
     */
    public function getAppraisalAllQuestions($a_id, $question_type = [1, 2])
    {
        $db = $this->getMongoMasterConnection();

        // 获取员工编号
        $employee_model = new Employee($this->app);
        $employeeInfo = $employee_model->view($this->eid);
        $no = ArrayGet($employeeInfo, 'no', '');

        // 获取【评估活动】所有要素
        $element_arr = $this->getAppraisalAllElements($a_id);

        foreach ($element_arr as $element_key => $element_value) {

            // 获取指定要素下，所有题目
            $condition = [
                'aid' => $this->aid,
                'em_id' => $element_value['element_id'],
                'status' => 1,
                'type' => [
                    '$in' => $question_type,
                ]
            ];

            $fields = [
                'id' => 1,
                'name' => 1,
            ];

            $questions = $db->fetchAll(Constants::COLL_ENTERPRISE_ELEMENT_QUESTION, $condition, 0, 0, [], $fields);

            $element_arr[$element_key]['questions'] = $questions;
        }

        return $element_arr;
    }

    /**
     * 查看【评估活动】所有设定关系
     *
     * @param $a_id 评估活动 ID
     *
     * @return array 评估关系
     */
    public function getAppraisalRelation($a_id)
    {
        $db = $this->getMongoMasterConnection();

        $cond = [
            'aid' => $this->aid,
            'id' => $a_id,
            'status' => 1,
        ];

        $fields = [
            'id' => 1,
            'relations' => 1,
        ];

        // 查询评估人的所有对应关系，已经回答的，得分数据
        $appraisal_res = $db->fetchRow(Constants::COLL_ENTERPRISE_APPRAISAL, $cond, $fields);

        return $appraisal_res['relations'];
    }

    /**
     * 查看【评估活动】某个被评估人 - 某种关系下【应评、实评】数据
     *
     * @param $a_id 评估活动 ID
     * @param $no 被评估人 工号
     * @param $relation_id 评估关系 ID
     *
     * @return array
     */
    public function getAppraisalRelationResult($a_id, $no, $relation_id)
    {
        $db = $this->getMongoMasterConnection();

        $cond = [
            'aid' => $this->aid,
            'a_id' => $a_id,
            'be_assessed_people_no' => $no,
            'relation_id' => $relation_id,
            'status' => 1,
        ];

        // 发出评估
        $all_num = $db->count(Constants::COLL_ENTERPRISE_APPRAISAL_RELATIONS, $cond);

        // 实际评估
        $cond['is_answer'] = 1;
        $actual_num = $db->count(Constants::COLL_ENTERPRISE_APPRAISAL_RELATIONS, $cond);

        return [
            'all_num' => $all_num,
            'actual_num' => $actual_num,
        ];
    }

    /**
     * 查看【评估活动】某个被评估人 - 各个关系下：最终得分
     *
     * @param $a_id 评估活动 ID
     * @param $no 被评估人 工号
     *
     * @return array
     */
    public function getAppraisalAllRelationFinalScore($a_id, $no)
    {
        $db = $this->getMongoMasterConnection();

        $relation_arr = $this->getAppraisalRelation($a_id);

        foreach ($relation_arr as $relation_key => $relation_value) {

            // 某种关系下：最终得分
            $one_relation_fanil_score = $this->getAppraisalOneRelationFinalScore($a_id, $no, $relation_value['role_id']);

            $relation_arr[$relation_key]['fanil_score'] = $one_relation_fanil_score;
        }

        return $relation_arr;
    }

    /**
     * 查看【评估活动】某个被评估人 - 某种关系下：最终得分
     *
     * @param $a_id 评估活动 ID
     * @param $no 被评估人 工号
     * @param $relation_id 评估关系 ID
     *
     * @return int 分数
     */
    public function getAppraisalOneRelationFinalScore($a_id, $no, $relation_id)
    {
        $db = $this->getMongoMasterConnection();

        $cond = [
            'aid' => $this->aid,
            'a_id' => $a_id,
            'be_assessed_people_no' => $no,
            'relation_id' => $relation_id,
            'status' => 1,
            'is_answer' => 1,
        ];

        $fields = [
            'id' => 1,
            'score' => 1,
        ];

        // 某个被评估人 - 某种关系下：得分 array
        $score_arr = $db->fetchAll(Constants::COLL_ENTERPRISE_APPRAISAL_RELATIONS, $cond, 0, 0, [], $fields);

        // 某个被评估人 - 某种关系下：评估数量
        $actual_num = $db->count(Constants::COLL_ENTERPRISE_APPRAISAL_RELATIONS, $cond);

        if ($actual_num == 0) {
            return 0;
        }

        // 某个被评估人 - 某种关系下：最终得分
        $final_score = sprintf("%.2f", array_sum(array_column($score_arr, 'score')) / $actual_num);

        return $final_score;
    }

    /**
     * 查看【评估活动】所有要素
     *
     * @param $a_id 评估活动 ID
     *
     * @return array 要素列表
     */
    public function getAppraisalAllElements($a_id)
    {
        $db = $this->getMongoMasterConnection();

        $questionnaire_model = new EnterpriseQuestionnaire($this->app);
        $ability_model = new EnterpriseAbility($this->app);
        $element_model = new EnterpriseElement($this->app);

        $cond = [
            'aid' => $this->aid,
            'id' => $a_id,
            'status' => 1,
        ];

        $fields = [
            'id' => 1,
            'questionnaire' => 1,
        ];

        // 查询【评估活动】详情
        $appraisal_res = $db->fetchRow(Constants::COLL_ENTERPRISE_APPRAISAL, $cond, $fields);

        // 根据问卷ID，查询问卷详情
        $questionnaire = $questionnaire_model->detail($appraisal_res['questionnaire'][0]['questionnaire_id']);

        $element_arr = [];

        // 遍历问卷调查
        foreach ($questionnaire['mdoels'] as $mdoels_v) {
            // 纬度列表
            $dimension = $ability_model->dimensionList($mdoels_v['id']);
            foreach ($dimension as $dimension_v) {
                foreach ($dimension_v['ab_element'] as $dimension_v_v) {
                    // 获取要素详情
                    $element = $element_model->detail($dimension_v_v['id']);
                    $element_arr[] = [
                        'questionnaire_id' => $questionnaire['id'],
                        'model_id' => $mdoels_v['id'],
                        'dimension_id' => $dimension_v['id'],
                        'element_id' => $element['id'],
                        'name' => $element['name'],
                        'weight' => $dimension_v_v['weight']
                    ];
                }
            }
        }

        return $element_arr ?: [];
    }

    /**
     * 查看【评估活动】某个被评估人，某一要素的下的得分情况
     *
     *
     * @param $a_id 评估活动 ID
     * @param $questionnaire_id 问卷 ID
     * @param $model_id 模型 ID
     * @param $dimension_id 纬度 ID
     * @param $element_id 要素 ID
     * @param $question_id 要素 ID
     *
     *  如果 $question_id 传值，计算【指定问题】得分
     *  如果 $question_id 无值，计算【所有问题】得分
     *
     * @param $no 被评估人 工号
     * @param $relation_id 评估关系 ID
     *
     *  如果 $relation_id 传值，计算【某一纬度 -> 某一关系】得分
     *  如果 $relation_id 无值，计算【某一纬度 -> 所有关系】得分
     *
     * @param $type 类型【0-全部；1-自己；2-他人】
     *
     * @return array
     */
    public function getOneQuestionFinalScore($a_id, $questionnaire_id = 0, $model_id = 0, $dimension_id = 0, $element_id = 0, $question_id = 0, $no, $relation_id = 0, $type = 0)
    {
        $db = $this->getMongoMasterConnection();

        $cond = [
            'aid' => $this->aid,
            'a_id' => $a_id,
            'status' => 1,
            'question_score' => ['$gt' => 0],
            'be_assessed_people_no' => $no,
        ];

        if ($questionnaire_id) {
            $cond['questionnaire_id'] = $questionnaire_id;
        }
        if ($model_id) {
            $cond['model_id'] = $model_id;
        }
        if ($dimension_id) {
            $cond['dimension_id'] = $dimension_id;
        }
        if ($element_id) {
            $cond['element_id'] = $element_id;
        }
        if ($question_id) {
            $cond['question_id'] = $question_id;
        }

        // 评估关系
        if ($relation_id) {
            $cond['relation_id'] = $relation_id;
        }

        // 类型【0-全部；1-自己；2-他人】
        if ($type == 1) {
            $cond['assessed_people_no'] = $no;
        } else if ($type == 2) {
            $cond['assessed_people_no'] = ['$ne' => $no];
        }

        $fields = [
            'id' => 1,
            'question_score' => 1,
            'relation_weight' => 1,
        ];

        // 查询【提交答案】结果
        $answer_res = $db->fetchAll(Constants::COLL_ENTERPRISE_APPRAISAL_ANSWERS, $cond, 0, 0, [], $fields);

        // 答题数量：
        $total_num = count($answer_res);

        // 如果答题数量为 0 ，表示没有答案，没有分数，0分；
        if ($total_num == 0) {

            $final_score = 0;
        } else {

            // 答题总分
            $total_score = 0;

            // 如果relation_id=0，代表全部关系，计算总得分的时候，需要✖️权重，然后求和
            if ($relation_id == 0) {

                $cond = [
                    'aid' => $this->aid,
                    'id' => $a_id,
                    'status' => 1,
                ];

                $fields = [
                    'id' => 1,
                    'relations' => 1,
                ];

                // 查询【评估活动】详情
                $appraisal_res = $db->fetchRow(Constants::COLL_ENTERPRISE_APPRAISAL, $cond, $fields);

                // 权重总和
                $all_weight = array_sum(array_column($appraisal_res['relations'], 'weight'));

                foreach ($answer_res as $answer_value) {
                    $total_score += $answer_value['question_score'] * $answer_value['relation_weight'] / $all_weight;
                }
            } else {

                $total_score = array_sum(array_column($answer_res, 'question_score'));
            }

            // 要素最终得分
            $final_score = $total_score / $total_num;
        }

        return sprintf("%.2f", $final_score);
    }

}
