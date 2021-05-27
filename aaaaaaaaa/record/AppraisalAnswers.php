<?php
/**
 * 评估提交答案字段的详细验证
 * author：天尽头流浪
 */
namespace App\Records;

use Key\Abstracts\BaseRecord;

class EnterpriseAppraisalAnswers extends BaseRecord
{
    protected $fields = [
        'questionnaire_id' => [
            'type' => 'int',
            'description' => '问卷ID',
            'required' => 1,
        ],
        'model_id' => [
            'type' => 'int',
            'description' => '模型ID',
            'required' => 1,
        ],
        'dimension_id' => [
            'type' => 'int',
            'description' => '纬度ID',
            'required' => 1,
        ],
        'element_id' => [
            'type' => 'int',
            'description' => '要素ID',
            'required' => 1,
        ],
        'question_id' => [
            'type' => 'int',
            'description' => '要素题ID',
            'required' => 1,
        ],
        'question_type' => [
            'type' => 'int',
            'description' => '题型',
            'required' => 1,
        ],
        'question_name' => [
            'type' => 'string',
            'description' => '题目标题',
            'required' => 1,
        ],
        'question_desc' => [
            'type' => 'string',
            'description' => '题目描述',
        ],
        'question_options' => [
            'type' => 'array',
            'description' => '选项 [[id:1, name:选项A, content: 李白, score: 1], [id:1, name:选项B, content: 杜甫, score: 2], ....]',
        ],
        'question_answer' => [
            'type' => 'string',
            'description' => '答案',
        ],
        'question_score' => [
            'type' => 'int',
            'description' => '答案的分数',
            'default' => 0,
        ],
    ];
}
