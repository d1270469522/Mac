<?php
/**
 * 问卷管理：controller
 * author：天尽头流浪
 */
namespace App\Controllers;

use App\Utils;
use App\Common\Constants;
use App\Common\Controller;
use App\Models\EnterpriseQuestionnaire as Questionnaire;

class EnterpriseQuestionnaire extends Controller
{
    /**
     * 问卷创建
     */
    public function createAction($record)
    {
        $questionnaireModel = new Questionnaire($this->app);

        $record = $record->toArray();

        // 查询编号是否被占用
        $is_existts = $questionnaireModel->getExistsNo($record['no']);

        if($is_existts) {
            return self::SPECIAL_SUBJECT_NO_USED;
        }

        // 问卷创建
        $result = $questionnaireModel->create($record);

        if ($result) {
            $this->setOutput('id', $result);
            return Constants::SYS_SUCCESS;
        }
        return Constants::SYS_ERROR_DEFAULT;
    }

    /**
     * 问卷列表
     */
    public function listAction($keyword, $pg)
    {
        $questionnaireModel = new Questionnaire($this->app);

        $result = $questionnaireModel->getList($keyword, $pg, $total);

        $this->setOutput('list', $result);
        $this->setOutput('total', $total);
        return Constants::SYS_SUCCESS;
    }

    /**
     * 问卷详情
     */
    public function detailAction($id)
    {
        $questionnaireModel = new Questionnaire($this->app);

        $result = $questionnaireModel->detail($id);

        $this->setOutput('info', $result);
        return Constants::SYS_SUCCESS;
    }

    /**
     * 问卷编辑
     */
    public function updateAction($id, $record)
    {
        $questionnaireModel = new Questionnaire($this->app);

        $record = $record->toArray();

        // 查询编号是否被占用（自己除外）
        $is_existts = $questionnaireModel -> getExistsNo($record['no'], $id);

        if($is_existts) {
            return self::SPECIAL_SUBJECT_NO_USED;
        }

        $result = $questionnaireModel->update($id, $record);

        if ($result) {
            return Constants::SYS_SUCCESS;
        }
        return Constants::SYS_ERROR_DEFAULT;
    }

    /**
     * 问卷删除
     */
    public function deleteAction($id)
    {
        $questionnaireModel = new Questionnaire($this->app);

        $result = $questionnaireModel->delete($id);

        if ($result) {
            return Constants::SYS_SUCCESS;
        }
        return Constants::SYS_ERROR_DEFAULT;
    }

/** = = = = = = = = = = = = 下方是问卷设置：选择模型 = = = = = = = = = = = **/

    /**
     * 问卷设置编辑
     */
    public function updateSettingAction($id, $models)
    {
        $questionnaireModel = new Questionnaire($this->app);

        $result = $questionnaireModel->updateSetting($id, $models);

        if ($result) {
            return Constants::SYS_SUCCESS;
        }
        return Constants::SYS_ERROR_DEFAULT;
    }

    /**
     * 获取没有问卷下的所有设置：设置详情 - 设置列表 - 预览接口
     */
    public function getSettingAction($id)
    {
        $questionnaireModel = new Questionnaire($this->app);

        $result = $questionnaireModel->getSetting($id);

        $this->setOutput('list', $result);
        return Constants::SYS_SUCCESS;
    }
}
