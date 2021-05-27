<?php
/**
 * 要素题创建时：字段详细验证
 * author：天尽头流浪
 */

namespace App\Records;

use Key\Abstracts\BaseRecord;

class EnterpriseQuestion extends BaseRecord
{
    protected $fields = [
        'id' => [
            'type' => 'int',
            'description' => '要素题ID',
        ],
        'type' => [
            'type' => 'int',
            'description' => '题型',
            'required' => 1,
        ],
        'name' => [
            'type' => 'string',
            'description' => '题目名称：诗圣是谁？',
            'required' => 1,
        ],
        'desc' => [
            'type' => 'string',
            'description' => '题目描述：请选择谁是诗圣',
        ],
        'options' => [
            'type' => 'array',
            'description' => '选项
                [
                    [id:1, name:选项A, content: 李白, score: 1],
                    [id:1, name:选项B, content: 杜甫, score: 2],
                    ....
                ]',
        ],
        'order' => [
            'type' => 'int',
            'description' => '排序',
        ],
        'score' => [
            'type' => 'int',
            'description' => '分数',
        ],
    ];
}
