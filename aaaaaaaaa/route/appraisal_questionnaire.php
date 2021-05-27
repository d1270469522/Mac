<?php
/**
 * 问卷管理：路由
 * author：天尽头流浪
 */
return [

    'POST /questionnaire' => [
        'description' => '问卷创建',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseQuestionnaire/create',
        'inputs' => [
            'record' => [
                'type' => 'EnterpriseQuestionnaire',
                'description' => '问卷字段的详细验证',
                'required' => 1,
            ],
        ]
    ],

    'GET /questionnaires' => [
        'description' => '问卷列表',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseQuestionnaire/list',
        'inputs' => [
            'keyword' => [
                'type' => 'string',
                'description' => '关键字',
            ],
            'pg' => [
                'type' => 'pagination',
                'description' => '分页',
            ],
        ]
    ],

    'GET /questionnaire/(:id)' => [
        'description' => '问卷详情',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseQuestionnaire/detail',
        'inputs' => [
            'id' => [
                'type' => 'int',
                'description' => '问卷ID',
                'required' => 1,
            ],
        ]
    ],

    'PUT /questionnaire/(:id)' => [
        'description' => '问卷编辑',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseQuestionnaire/update',
        'inputs' => [
            'id' => [
                'type' => 'int',
                'description' => '问卷ID',
                'required' => 1,
            ],
            'record' => [
                'type' => 'EnterpriseQuestionnaire',
                'description' => '问卷字段的详细验证',
                'required' => 1
            ],
        ]
    ],

    'DELETE /questionnaire/(:id)' => [
        'description' => '问卷删除',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseQuestionnaire/delete',
        'inputs' => [
            'id' => [
                'type' => 'int',
                'description' => '问卷ID',
                'required' => 1,
            ],
        ]
    ],

/** = = = = = = = = = = = = 下方是问卷设置：选择模型 = = = = = = = = = = = **/

    'PUT /questionnaire/(:id)/setting' => [
        'description' => '问卷编辑',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseQuestionnaire/updateSetting',
        'inputs' => [
            'id' => [
                'type' => 'int',
                'description' => '问卷ID',
                'required' => 1,
            ],
            'models' => [
                'type' => 'array',
                'description' => '模型信息',
                'display' => '[[id:1, model_name, weight:100], ....]',
                'required' => 1
            ],
        ]
    ],

    'GET /questionnaires/(:id)/setting' => [
        'description' => '问卷设置：设置详情 - 设置列表 - 预览接口',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseQuestionnaire/getSetting',
        'inputs' => [
            'id' => [
                'type' => 'int',
                'description' => '问卷ID',
                'required' => 1,
            ],
        ]
    ],
];
