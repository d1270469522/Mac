<?php
/**
 * 评估字段的详细验证
 * author：天尽头流浪
 */
namespace App\Records;

use Key\Abstracts\BaseRecord;

class EnterpriseAppraisal extends BaseRecord
{
    protected $fields = [
        'no' => [
            'type' => 'string',
            'description' => '评估编号',
            'required' => 1,
        ],
        'title' => [
            'type' => 'string',
            'description' => '评估名称',
            'required' => 1,
        ],
        'desc' => [
            'description' => '描述',
            'type' => 'string',
        ],
        'start_time' => [
            'type' => 'string',
            'description' => '开始时间',
            'required' => 1,
        ],
        'end_time' => [
            'type' => 'string',
            'description' => '预计结束时间',
            'required' => 1,
        ],
        'cover' => [
            'type' => 'string',
            'description' => '封面',
            'required' => 1,
        ],
        'department_id' => [
            'type' => 'int',
            'description' => '部门ID',
        ],
        'department_name' => [
            'type' => 'string',
            'description' => '部门名称',
        ],
        'open_type' => [
            'type' => 'int',
            'description' => '开放方式：1、内部；2、外部',
            'default' => 1,
        ],

    ];
}
