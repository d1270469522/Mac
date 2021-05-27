<?php
/**
 * 评估关系创建
 * User: dpf
 * Date: 2021/03/29
 */
namespace App\Records;

use Aws\kendra\kendraClient;
use Key\Abstracts\BaseRecord;

class EnterpriseAppraisalRelationsInfo extends BaseRecord
{
    protected $fields = [
        'be_assessed_people_no' => [
            'type' => 'string',
            'description' => '被评估人编号',
            'required' => 1,
        ],
        'be_assessed_people_name' => [
            'type' => 'string',
            'description' => '被评估人姓名',
            'required' => 1,
        ],
        'be_assessed_people_email' => [
            'type' => 'string',
            'description' => '被评估人邮箱',
            'required' => 1,
        ],
        'be_assessed_people_department_id' => [
            'type' => 'int',
            'description' => '被评估人部门ID',
        ],
        'be_assessed_people_department_name' => [
            'type' => 'string',
            'description' => '被评估人部门名称',
        ],
        'be_assessed_people_position_id' => [
            'type' => 'int',
            'description' => '被评估人岗位ID',
        ],
        'be_assessed_people_position_name' => [
            'type' => 'string',
            'description' => '被评估人岗位名称',
        ],
        'relation_id' => [
            'type' => 'int',
            'description' => '评估关系ID',
            'required' => 1,
        ],
        'relation_name' => [
            'type' => 'string',
            'description' => '评估关系名称',
            'required' => 1,
        ],
        'relation_weight' => [
            'type' => 'int',
            'description' => '评估关系权重',
            'required' => 1,
        ],
        'assessed_people_no' => [
            'type' => 'string',
            'description' => '评估人编号',
            'required' => 1,
        ],
        'assessed_people_name' => [
            'type' => 'string',
            'description' => '评估人姓名',
            'required' => 1,
        ],
        'assessed_people_email' => [
            'type' => 'string',
            'description' => '评估人邮箱',
            'required' => 1,
        ]
    ];
}
