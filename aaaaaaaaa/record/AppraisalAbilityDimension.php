<?php
/**
 * 纬度：路由
 * author：天尽头流浪
 */
namespace App\Records;

use Key\Abstracts\BaseRecord;

class EnterpriseAbilityDimension extends BaseRecord
{
    protected $fields = [
        'id' => [
            'type' => 'int',
            'description' => '维度ID',
        ],
        'level_name' => [
            'type' => 'string',
            'description' => '维度名称',
            'required' => 1,
        ],
        'weight' => [
            'type' => 'int',
            'description' => '维度权重',
            'default' => 100,
        ],
        'ab_element' => [
            'type' => 'array',
            'description' => '要素 [id:1, name:要素名称, weight:100]',
        ],
    ];
}
