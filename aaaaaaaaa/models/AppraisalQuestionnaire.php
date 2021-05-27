<?php
/**
 * 问卷管理：model
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
use App\Models\EnterpriseAbility;
use App\Models\EnterpriseElement;

class EnterpriseQuestionnaire extends BaseModel
{
    // 报名方式[sign_type]：1、微信；2、手机号
    const SIGN_WECHAT = 1;
    const SIGN_PHONE = 2;

    const EVENT_UNIQUE_REDIS = 'EVENT_UNIQUE:';

    protected $redis;

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

        $res = $db->fetchRow(Constants::COLL_ENTERPRISE_QUESTIONNAIRE, $cond);

        return $res ?: 0;
    }

    /**
     * 问卷创建
     */
    public function create($record)
    {
        $db = $this->getMongoMasterConnection();

        $default_data = [
            'id' =>  Sequence::getSeparateId(Constants::COLL_ENTERPRISE_QUESTIONNAIRE, $this->aid),
            'eid' => $this->eid,
            'aid' => $this->aid,
            'status' => 1,
            'created' => Mongodb::getMongoDate(),
            'updated' => Mongodb::getMongoDate(),
        ];

        $data = array_merge($default_data, $record);
        $result = $db->insert(Constants::COLL_ENTERPRISE_QUESTIONNAIRE, $data);

        if ($result) {
            return $data['id'];
        }
        return false;
    }

    /**
     * 问卷列表
     */
    public function getList($keyword = '', $pg = null, &$total)
    {
        $db = $this->getMongoMasterConnection();

        if (! $pg) {
            $pg = new Pagination();
            $pg->setPage(0);
            $pg->setItemsPerPage(0);
        }

        $cond = [
            'aid' => $this->aid,
            'status' => 1,
        ];

        // 关键字
        if ($keyword) {
            $cond['title'] = new Regex($keyword, 'im');
        }

        $result = $db->fetchAll(Constants::COLL_ENTERPRISE_QUESTIONNAIRE, $cond, $pg->getOffset(), $pg->getItemsPerPage(), ['updated' => -1]);
        Utils::convertMongoDateToTimestamp($result);

        // 问卷列表 - 总条数
        $total = $db->count(Constants::COLL_ENTERPRISE_QUESTIONNAIRE, $cond);

        return $result ?: [];
    }

    /**
     * 问卷详情
     */
    public function detail($id)
    {
        $db = $this->getMongoMasterConnection();

        $cond = [
            'id' => $id,
            'aid' => $this->aid,
            'status' => 1,
        ];

        $result = $db->fetchRow(Constants::COLL_ENTERPRISE_QUESTIONNAIRE, $cond);
        Utils::convertMongoDateToTimestamp($result);

        return $result ?: [];
    }

    /**
     * 问卷编辑
     */
    public function update($id, $data)
    {
        $db = $this->getMongoMasterConnection();

        $cond = [
            'aid' => $this->aid,
            'status' => 1,
            'id' => $id,
        ];

        $data['updated'] = Mongodb::getMongoDate();

        $result = $db->update(Constants::COLL_ENTERPRISE_QUESTIONNAIRE, $cond, ['$set' => $data]);

        return $result ?: [];
    }

    /**
     * 问卷删除
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

        $result = $db->update(Constants::COLL_ENTERPRISE_QUESTIONNAIRE, $cond, ['$set' => $set]);

        return $result ?: [];
    }

/** = = = = = = = = = = = = 下方是问卷设置：选择模型 = = = = = = = = = = = **/

    /**
     * 设置模型
     */
    public function updateSetting($id, $models)
    {
        $db = $this->getMongoMasterConnection();

        $cond = [
            'id' => $id,
            'aid' => $this->aid,
            'status' => 1,
        ];

        // 计算问卷总题数
        $qs_num_arr = array_column($models, 'qs_num');
        $qs_num_total = array_sum($qs_num_arr);

        $set = [
            'mdoels' => $models,
            'qs_num_total' => $qs_num_total,
            'updated' => Mongodb::getMongoDate(),
        ];

        $result = $db->update(Constants::COLL_ENTERPRISE_QUESTIONNAIRE, $cond, ['$set' => $set]);

        return $result ?: [];
    }

    /**
     * 设置详情 - 设置列表 - 预览接口
     */
    public function getSetting($id)
    {
        $db = $this->getMongoMasterConnection();

        $ability_model = new EnterpriseAbility($this->app);
        $element_model = new EnterpriseElement($this->app);

        $questionnaire = $this->detail($id);

        // 遍历问卷调查
        foreach ($questionnaire['mdoels'] as $questionnaire_k => $questionnaire_v) {

            // 模型详情
            $ability = $ability_model->detail($questionnaire_v['id']);

            $questionnaire['models_info'][$questionnaire_k] = [
                'id' => $ability['id'],
                'name' => $ability['name'],
                'desc' => $ability['desc'],
                'qs_num' => $ability['qs_num'],
                'weight' => $questionnaire_v['weight'],
            ];

            // 纬度列表
            $dimension = $ability_model->dimensionList($questionnaire_v['id']);

            foreach ($dimension as $dimension_k => $dimension_v) {

                $questionnaire['models_info'][$questionnaire_k]['dimension'][$dimension_k] = [
                    'id' => $dimension_v['id'],
                    'level' => $dimension_v['level'],
                    'level_name' => $dimension_v['level_name'],
                    'weight' => $dimension_v['weight'],
                ];

                foreach ($dimension_v['ab_element'] as $dimension_v_k => $dimension_v_v) {

                    // 获取要素详情
                    $element = $element_model->detail($dimension_v_v['id']);

                    // 获取要素详情
                    $question = $element_model->detailQuestion($element['id'], '', $total);

                    $questionnaire['models_info'][$questionnaire_k]['dimension'][$dimension_k]['element'][$dimension_v_k] = [
                        'id' => $element['id'],
                        'no' => $element['no'],
                        'name' => $element['name'],
                        'weight' => $dimension_v_v['weight'],
                        'desc' => $element['desc'],
                        'qs_num' => $element['qs_num'],
                        'question' => $question,
                    ];
                }
            }
        }

        return $questionnaire ?: [];
    }
}
