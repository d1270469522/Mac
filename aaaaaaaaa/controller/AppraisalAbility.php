<?php
/**
 * 能力模型：路由
 * author：天尽头流浪
 */
namespace App\Controllers;

use App\Utils;
use App\Common\Constants;
use App\Common\Controller;
use App\Models\EnterpriseAbility as Ability;

class EnterpriseAbility extends Controller
{
    /**
     * 模型创建
     */
    public function createAction($record)
    {
        $enterpriseAbilityModel = new Ability($this->app);

        $data = $record->toArray(true);

        $result = $enterpriseAbilityModel->create($data);

        if($result) {
            $this->setOutput('id', $result);
            return Constants::SYS_SUCCESS;
        }
        return Constants::SYS_ERROR_DEFAULT;
    }

    /**
     * 模型列表
     */
    public function abilityListAction($keyword, $sort, $pg)
    {
        $enterpriseAbilityModel = new Ability($this->app);

        $result = $enterpriseAbilityModel->abilityList($keyword, $sort, $pg, $total);

        $this->setOutput('list', $result);
        $this->setOutput('total', $total);

        return Constants::SYS_SUCCESS;
    }

    /**
     * 模型详情
     */
    public function detailAction($id)
    {
        $enterpriseAbilityModel = new Ability($this->app);

        $res = $enterpriseAbilityModel->detail($id);

        $this->setOutput('info', $res);
        return Constants::SYS_SUCCESS;
    }

    /**
     * 模型编辑
     */
    public function updateAction($id, $record)
    {
        $enterpriseAbilityModel = new Ability($this->app);

        $data = $record->toArray(true);

        $result = $enterpriseAbilityModel->update($id,$data);

        if($result) {
            $this->setOutput('id', $result);
            return Constants::SYS_SUCCESS;
        }
        return Constants::SYS_ERROR_DEFAULT;
    }

    /**
     * 模型删除
     */
    public function deleteAction($id)
    {
        $enterpriseAbilityModel = new Ability($this->app);

        $result = $enterpriseAbilityModel->delete($id);

        if($result) {
            return Constants::SYS_SUCCESS;
        }
        return Constants::SYS_ERROR_DEFAULT;
    }

/** = = = = = = = = 下方的部分：纬度设置 = = = = = = = = = **/

    /**
     * 获取某一模型下的所有纬度
     */
    public function dimensionListAction($am_id)
    {
        $enterpriseAbilityModel = new Ability($this->app);

        $res = $enterpriseAbilityModel->dimensionList($am_id);

        $this->setOutput('list', $res);
        $this->setOutput('am_id', $am_id);

        return Constants::SYS_SUCCESS;
    }

    /**
     * 纬度创建
     */
    public function createDimensionAction($am_id, $level, $record)
    {
        $enterpriseAbilityModel = new Ability($this->app);

        $result = $enterpriseAbilityModel->createOrUpdateDimension($am_id, $level, $record);

        if($result) {
            return Constants::SYS_SUCCESS;
        }
        return Constants::SYS_ERROR_DEFAULT;
    }

    /**
     * 纬度编辑
     */
    public function updateDimensionAction($am_id, $level, $record)
    {
        $enterpriseAbilityModel = new Ability($this->app);

        $result = $enterpriseAbilityModel->createOrUpdateDimension($am_id, $level, $record);

        if($result) {
            return Constants::SYS_SUCCESS;
        }
        return Constants::SYS_ERROR_DEFAULT;
    }
}
