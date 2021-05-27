<?php
/**
 * 问卷管理：字段验证
 */
namespace App\Records;

use Key\Abstracts\BaseRecord;

class EnterpriseQuestionnaire extends BaseRecord
{
    protected $fields = [
        'no' => [
            'type' => 'string',
            'description' => '问卷编号',
            'required' => 1,
        ],
        'title' => [
            'type' => 'string',
            'description' => '问卷名称',
            'required' => 1,
        ],
        'desc' => [
            'type' => 'string',
            'description' => '描述',
        ],
        'is_show_dimension' => [
            'type' => 'int',
            'description' => '是否展示纬度信息：1、是；0、否',
            'default' => 0,
        ],
        'is_shuffle' => [
            'type' => 'int',
            'description' => '是否打乱试题顺序：1、是；0、否',
            'default' => 0,
        ],
        'department_id' => [
            'type' => 'int',
            'description' => '部门ID',
            'required' => 1,
        ],
        'department_name' => [
            'type' => 'string',
            'description' => '部门名称',
            'required' => 1,
        ],
        'managers' => [
            'type' => 'array',
            'description' => '管理员信息数组',
            'display' => '[id:1, display:admin]',
        ],
    ];
}
