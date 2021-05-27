<?php
/**
 * 要素管理：控制器
 * author：天尽头流浪
 */
namespace App\Controllers;

use App\Common\Constants;
use App\Common\Controller;
use App\Models\EnterpriseElement as Element;
use App\Models\EnterpriseElQuestionImporter;

class EnterpriseElement extends Controller
{
    /**
     * 要素创建
     *
     * @param $record 要素创建时：字段详细验证
     */
    public function createAction($record)
    {
        $enterpriseElementModel = new Element($this->app);

        $data = $record->toArray(true);
        $result = $enterpriseElementModel->create($data);

        if ($result) {
            $this->setOutput('id', $result);
            return Constants::SYS_SUCCESS;
        }
        return Constants::SYS_ERROR_DEFAULT;
    }

    /**
     * 要素列表
     *
     * @param $enable 是否启用：0-全部；1-已启用；2-已禁用
     * @param $sort 排序：1-正序；2-倒序
     * @param $keyword 搜索关键字：要素名称
     * @param $pagination 分页
     */
    public function listAction($enable, $sort, $keyword, $exclude_ids, $pagination)
    {
        $enterpriseElementModel = new Element($this->app);

        $list = $enterpriseElementModel->list($enable, $sort, $keyword, $pagination, $total);

        if ($list) {
            $this->setOutput('list', $list);
            $this->setOutput('total', $total);
            return Constants::SYS_SUCCESS;
        }
        return Constants::SYS_ERROR_DEFAULT;
    }

    /**
     * 要素详情
     *
     * @param $id 要素ID
     */
    public function detailAction($id)
    {
        $enterpriseElementModel = new Element($this->app);

        $info = $enterpriseElementModel->detail($id);

        if ($info) {
            $this->setOutput('info', $info);
            return Constants::SYS_SUCCESS;
        }
        return Constants::SYS_ERROR_DEFAULT;
    }

    /**
     * 要素编辑
     *
     * @param $id 要素ID
     * @param $record 要素编辑时：字段详细验证
     */
    public function updateAction($id, $record)
    {
        $enterpriseElementModel = new Element($this->app);

        $data = $record->toArray(true);

        $result = $enterpriseElementModel->update($id, $data);

        if ($result) {
            $this->setOutput('id', $result);
            return Constants::SYS_SUCCESS;
        }
        return Constants::SYS_ERROR_DEFAULT;
    }

    /**
     * 要素删除
     *
     * @param $id 要素ID
     */
    public function deleteAction($id)
    {
        $enterpriseElementModel = new Element($this->app);

        $result = $enterpriseElementModel->delete($id);

        if ($result) {
            $this->setOutput('id', $result);
            return Constants::SYS_SUCCESS;
        }
        return Constants::SYS_ERROR_DEFAULT;
    }

/** = = = = = =下面部分：要素题 = = = = = = **/

    /**
     * 要素题创建
     *
     * @param $element_id 要素ID
     * @param $record 要素题创建时：字段详细验证
     */
    public function createQuestionAction($element_id, $record)
    {
        $enterpriseElementModel = new Element($this->app);

        $result = $enterpriseElementModel->createQuestion($element_id, $record);

        if($result) {
            return Constants::SYS_SUCCESS;
        }
        return Constants::SYS_ERROR_DEFAULT;
    }

    /**
     * 要素题列表
     *
     * @param $element_id 要素ID
     * @param $pg 分页
     */
    public function detailQuestionAction($element_id, $pg)
    {
        $enterpriseElementModel = new Element($this->app);

        $list = $enterpriseElementModel->detailQuestion($element_id, $pg, $total);

        if ($list) {
            $this->setOutput('list', $list);
            $this->setOutput('total', $total);
            return Constants::SYS_SUCCESS;
        }
        return Constants::SYS_ERROR_DEFAULT;
    }

    /**
     * 要素题编辑
     *
     * @param $element_id 要素ID
     * @param $record 要素题编辑时：字段详细验证
     */
    public function updateQuestionAction($element_id, $record)
    {
        $enterpriseElementModel = new Element($this->app);

        $result = $enterpriseElementModel->updateQuestion($element_id, $record);

        if($result) {
            return Constants::SYS_SUCCESS;
        }
        return Constants::SYS_ERROR_DEFAULT;
    }

    /**
     * 要素题导入
     *
     * @param $element_id 要素ID
     * @param $file 要素题导入文件
     */
    public function importQuestionAction($element_id, $file)
    {
        set_time_limit(600);

        $EnterpriseElQuestionImporterModel = new EnterpriseElQuestionImporter($this->app);

        // 设置 em_id
        $EnterpriseElQuestionImporterModel->setElementId($element_id);

        // 导入要素题
        $res = $EnterpriseElQuestionImporterModel->exec($file->full_name);

        // 输出正确导入行数
        $this->setOutput('valid_rows', $EnterpriseElQuestionImporterModel->getValidRows());
        // 输出错误导入行数
        $this->setOutput('invalid_rows', $EnterpriseElQuestionImporterModel->getInvalidRows());

        $this->setOutput('res', $res);

        return Constants::SYS_SUCCESS;
    }

}
