<?php
/**
 * 能力模型
 */
namespace App\Records;

use Key\Abstracts\BaseRecord;

class EnterpriseAbility extends BaseRecord
{
    protected $fields = [
        'no' => [
            'type' => 'string',
            'description' => '模型编号',
            'required' => 1,
        ],
        'name' => [
            'type' => 'string',
            'description' => '模型名称',
            'required' => 1,
        ],
        'desc' => [
            'type' => 'string',
            'description' => '描述',
        ],
        'level' => [
            'type' => 'int',
            'description' => '维度层级',
            'required' => 1,
        ],
    ];
}
