<?php
/**
 * 评估活动：controller
 * author：天尽头流浪
 */

namespace App\Controllers;

use App\Utils;
use App\Common\Constants;
use App\Common\Controller;
use App\Models\EnterpriseNotification;
use App\Models\EnterpriseElRelationsImporter;
use App\Models\EnterpriseAppraisal as Appraisal;

class EnterpriseAppraisal extends Controller
{
    /**
     * 评估创建
     */
    public function createAction($record)
    {
        $appraisalModel = new Appraisal($this->app);

        $record = $record->toArray();

        // 查询创建的编号是否重复
        $is_exists_no = $appraisalModel->getExistsNo($record['no']);

        if ($is_exists_no) {
            return self::SPECIAL_SUBJECT_NO_USED;
        }

        // 创建
        $result = $appraisalModel->create($record);

        $this->setOutput('id', $result);
        if ($result) {
            return Constants::SYS_SUCCESS;
        }
        return Constants::SYS_ERROR_DEFAULT;
    }

    /**
     * 评估编辑
     */
    public function updateAction($id, $record)
    {
        $appraisalModel = new Appraisal($this->app);

        $record = $record->toArray();

        // 查询的编号是否重复：编辑时排除本身
        $is_exists_no = $appraisalModel->getExistsNo($record['no'], $id);

        if($is_exists_no) {
            return self::SPECIAL_SUBJECT_NO_USED;
        }

        // 编辑
        $result = $appraisalModel->update($id, $record);

        if ($result) {
            return Constants::SYS_SUCCESS;
        }
        return Constants::SYS_ERROR_DEFAULT;
    }

    /**
     * 评估列表
     */
    public function listAction($type = 0, $keyword = '', $pg)
    {
        $appraisalModel = new Appraisal($this->app);

        $result = $appraisalModel->list($type, $keyword, $pg, $total);

        $this->setOutput('list', $result);
        $this->setOutput('total', $total);
        return Constants::SYS_SUCCESS;
    }

    /**
     * 评估详情
     */
    public function detailAction($id)
    {
        $appraisalModel = new Appraisal($this->app);

        $result = $appraisalModel->detail($id);

        $this->setOutput('info', $result);
        return Constants::SYS_SUCCESS;
    }

    /**
     * 评估删除
     */
    public function deleteAction($id)
    {
        $appraisalModel = new Appraisal($this->app);

        $result = $appraisalModel->delete($id);

        if ($result) {
            return Constants::SYS_SUCCESS;
        }
        return Constants::SYS_ERROR_DEFAULT;
    }

    /**
     * 选择问卷，设定评估关系
     */
    public function setRelationsAction($id, $questionnaire, $relations)
    {
        $appraisalModel = new Appraisal($this->app);

        $result = $appraisalModel->setRelations($id, $questionnaire, $relations);

        if ($result) {
            return Constants::SYS_SUCCESS;
        }
        return Constants::SYS_ERROR_DEFAULT;
    }

    /**
     * 评估关系详情创建
     */
    public function createRelationsInfoAction($a_id, $record)
    {
        $appraisalModel = new Appraisal($this->app);

        $record = $record->toArray();

        $result = $appraisalModel->createRelationsInfo($a_id, $record);

        if ($result) {
            $this->setOutput('id', $result);
            return Constants::SYS_SUCCESS;
        }
        return Constants::SYS_ERROR_DEFAULT;
    }

    /**
     * 评估关系详情列表
     */
    public function relationsInfoListAction($a_id, $keyword, $pg)
    {
        $appraisalModel = new Appraisal($this->app);

        $result = $appraisalModel->relationsInfoListList($a_id, $keyword, $pg, $total);

        $this->setOutput('list', $result);
        $this->setOutput('total', $total);
        return Constants::SYS_SUCCESS;
    }

    /**
     * 评估关系详情删除
     */
    public function deleteRelationsInfoAction($id)
    {
        $appraisalModel = new Appraisal($this->app);

        $result = $appraisalModel->deleteRelationsInfo($id);

        if ($result) {
            return Constants::SYS_SUCCESS;
        }
        return Constants::SYS_ERROR_DEFAULT;
    }

    /**
     * 评估关系详情导入
     */
    public function importRelationInfoAction($appraisal_id, $type, $file)
    {
        set_time_limit(600);

        $importerModel = new EnterpriseElRelationsImporter($this->app);

        // 设置评估ID
        $importerModel->setAppraisalId($appraisal_id);
        // 设置 type：开放方式：1、内部；2、外部
        $importerModel->setType($type);
        // 开始导入
        $res = $importerModel->exec($file->full_name);

        // 输出成功数据
        $this->setOutput('valid_rows', $importerModel->getValidRows());
        // 输出错误数据
        $this->setOutput('invalid_rows', $importerModel->getInvalidRows());
        // 返回结果
        $this->setOutput('res', $res);

        return Constants::SYS_SUCCESS;
    }

    /**
     * 评估活动发布
     */
    public function publishAction($appraisal_id)
    {
        $appraisalModel = new Appraisal($this->app);


        $result = $appraisalModel->publish($appraisal_id);

        if ($result) {

            return Constants::SYS_SUCCESS;
        }
        return Constants::SYS_ERROR_DEFAULT;
    }

    /**
     * 评估活动结束
     */
    public function setOverAction($appraisal_id)
    {
        $appraisalModel = new Appraisal($this->app);

        $result = $appraisalModel->setOver($appraisal_id);

        if ($result) {

            return Constants::SYS_SUCCESS;
        }
        return Constants::SYS_ERROR_DEFAULT;
    }

    /**
     * 评估通知创建
     */
    public function createNotificationAction($appraisal_id, $record)
    {
        $notificationModel = new EnterpriseNotification($this->app);

        $result = $notificationModel->sendNotification($appraisal_id, $record);

        if ($result) {
            $this->setOutput('id', $result);
            return Constants::SYS_SUCCESS;
        }
        return Constants::SYS_ERROR_DEFAULT;
    }


/** = = = = = = = = =  = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = **
 *                                  下方是移动端接口                                    *
 ** = = = = = = = = = = = = = =  = = = = = = = = = = = = = = = = = = = = = = = = = = **/

    /**
     * 评估列表：查询“我”作为评估人时，将要评估的活动
     *
     * @param $type = 0 【状态：0-全部；1-未开始；2-进行中；3-已结束】
     * @param $is_answer = 0 【是否回答：0-全部；1-已回答；2-未回答】
     * @param $keywords = '' 【关键字：活动标题】
     * @param $pg = null 【分页】
     */
    public function getMyAppraisalsV1Action($type, $is_answer, $keywords, $pg)
    {
        $appraisalModel = new Appraisal($this->app);

        $list = $appraisalModel->getMyAppraisalsV1($type, $is_answer, $keywords, $pg, $total);

        $this->setOutput('list', $list);
        $this->setOutput('total', $total);
        return Constants::SYS_SUCCESS;
    }

    /**
     * 某一个【评估活动】详情
     *
     * @param $a_id【评估ID】
     */
    public function getOneAppraisalInfoV1Action($a_id)
    {
        $appraisalModel = new Appraisal($this->app);

        $info = $appraisalModel->getOneAppraisalInfoV1($a_id);

        $this->setOutput('info', $info);
        return Constants::SYS_SUCCESS;
    }

    /**
     * 评估详情：下方的评估对象列表：该评估下，我作为评估人需要评估的所有对象
     *
     * @param $a_id 【评估ID】
     * @param $pg = null【分页】
     */
    public function getRelationsV1Action($a_id, $pg)
    {
        $appraisalModel = new Appraisal($this->app);

        $list = $appraisalModel->getRelationsV1($a_id, $pg, $total);

        $this->setOutput('list', $list);
        $this->setOutput('total', $total);
        return Constants::SYS_SUCCESS;
    }

    /**
     * 评估活动页面的所有题目
     *
     * @param $a_id 【评估ID】
     * @param $type 【是否获取答案：0-不获取；1-获取】
     */
    public function allQuestionsV1Action($a_id, $type)
    {
        $appraisalModel = new Appraisal($this->app);

        $list = $appraisalModel->allQuestionsV1($a_id, $type);

        $this->setOutput('list', $list);
        return Constants::SYS_SUCCESS;
    }

    /**
     * 评估活动：提交答案
     *
     * @param $a_id【评估ID】
     * @param $record 【答案数据】
     */
    public function createAnswersV1Action($a_id, $record)
    {
        $appraisalModel = new Appraisal($this->app);

        $result = $appraisalModel->createAnswersV1($a_id, $record);

        if($result) {
            return Constants::SYS_SUCCESS;
        }
        return Constants::SYS_ERROR_DEFAULT;
    }

    /**
     * 查看【评估活动】某个被评估人 - 综合所有关系 - 计算最终得分
     *
     * @param $a_id 评估活动 ID
     * @param $no 被评估人 工号
     *
     * @return int 分数
     */
    public function getAppraisalFinalScoreV1Action($a_id)
    {
        $appraisalModel = new Appraisal($this->app);

        $result = $appraisalModel->getAppraisalFinalScoreV1($a_id);

        $this->setOutput('result', $result);
        if($result) {
            return Constants::SYS_SUCCESS;
        }
        return Constants::SYS_ERROR_DEFAULT;
    }

    /**
     * 评估活动-统计各个评估关系【应评数量、实评数量】
     */
    public function relationResultV1Action($a_id)
    {
        $appraisalModel = new Appraisal($this->app);

        $result = $appraisalModel->relationResultV1($a_id);

        $this->setOutput('result', $result);
        if($result) {
            return Constants::SYS_SUCCESS;
        }
        return Constants::SYS_ERROR_DEFAULT;
    }

    /**
     * 评估活动：各个要素【他评均分、自评得分、差值】
     */
    public function getEveryElementFinalScoreV1Action($a_id)
    {
        $appraisalModel = new Appraisal($this->app);

        $result = $appraisalModel->getEveryElementFinalScoreV1($a_id);

        $this->setOutput('result', $result);
        if($result) {
            return Constants::SYS_SUCCESS;
        }
        return Constants::SYS_ERROR_DEFAULT;
    }

    /**
     * 评估活动：各个要素【各种关系】得分
     */
    public function getEveryElementWithRelationFinalScoreV1Action($a_id)
    {
        $appraisalModel = new Appraisal($this->app);

        $result = $appraisalModel->getEveryElementWithRelationFinalScoreV1($a_id);

        $this->setOutput('result', $result);
        if($result) {
            return Constants::SYS_SUCCESS;
        }
        return Constants::SYS_ERROR_DEFAULT;
    }

    /**
     * 评估活动：指定要素-所有题目-【他评均分、自评得分、差值】
     */
    public function getQuestionFinalScoreV1Action($a_id)
    {
        $appraisalModel = new Appraisal($this->app);

        $result = $appraisalModel->getQuestionFinalScoreV1($a_id);

        $this->setOutput('result', $result);
        if($result) {
            return Constants::SYS_SUCCESS;
        }
        return Constants::SYS_ERROR_DEFAULT;
    }

    /**
     * 评估活动：指定要素-所有题目-【所有关系】-得分
     */
    public function getQuestionWithRelationFinalScoreV1Action($a_id)
    {
        $appraisalModel = new Appraisal($this->app);

        $result = $appraisalModel->getQuestionWithRelationFinalScoreV1($a_id);

        $this->setOutput('result', $result);
        if($result) {
            return Constants::SYS_SUCCESS;
        }
        return Constants::SYS_ERROR_DEFAULT;
    }

    /**
     * 评估活动：优势项、劣势项展示【以题为单位】
     */
    public function getEveryQuestionOrderV1Action($a_id, $type)
    {
        $appraisalModel = new Appraisal($this->app);

        $result = $appraisalModel->getEveryQuestionOrderV1($a_id, $type);

        $this->setOutput('result', $result);
        if($result) {
            return Constants::SYS_SUCCESS;
        }
        return Constants::SYS_ERROR_DEFAULT;
    }

    /**
     * 评估活动：文本描述题 - 统计
     */
    public function getEveryQuestionTextTopicV1Action($a_id)
    {
        $appraisalModel = new Appraisal($this->app);

        $result = $appraisalModel->getEveryQuestionTextTopicV1($a_id);

        $this->setOutput('result', $result);
        if($result) {
            return Constants::SYS_SUCCESS;
        }
        return Constants::SYS_ERROR_DEFAULT;
    }
}
