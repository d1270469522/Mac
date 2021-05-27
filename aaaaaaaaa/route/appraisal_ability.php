<?php
/**
 * 能力模型：路由
 * author：天尽头流浪
 */
return [

    'POST /ability' => [
        'description' => '模型创建',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseAbility/create',
        'inputs' => [
            'record' => [
                'type' => 'EnterpriseAbility',
                'description' => '模型字段的详细验证',
                'required' => 1,
            ]
        ]
    ],

    'GET /ability' => [
        'description' => '模型列表',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseAbility/abilityList',
        'inputs' => [
            'keyword' => [
                'type' => 'string',
                'description' => '关键字',
            ],
            'sort' => [
                'type' => 'int',
                'description' => '排序：1、正序；2、倒序',
                'default' => 2,
            ],
            'pagination' => [
                'type' => 'pagination',
                'description' => '分页',
            ],
        ]
    ],

    'GET /ability/(:id)' => [
        'description' => '模型详情',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseAbility/detail',
        'inputs' => [
            'id' => [
                'type' => 'int',
                'description' => '模型ID',
                'required' => 1
            ],
        ]
    ],

    'PUT /ability/(:id)' => [
        'description' => '模型编辑',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseAbility/update',
        'inputs' => [
            'id' => [
                'type' => 'int',
                'description' => '模型ID',
                'required' => 1,
            ],
            'record' => [
                'type' => 'EnterpriseAbility',
                'description' => '模型字段的详细验证',
                'required' => 1
            ]
        ]
    ],

    'DELETE /ability/(:id)' => [
        'description' => '模型删除',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseAbility/delete',
        'inputs' => [
            'id' => [
                'type' => 'int',
                'description' => '模型ID',
                'required' => 1,
            ]
        ]
    ],

/** = = = = = = = = 下方的部分：纬度设置 = = = = = = = = = **/

    'GET /ability/(:am_id)/dimension' => [
        'description' => '获取某一模型下的所有纬度',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseAbility/dimensionList',
        'inputs' => [
            'am_id' => [
                'type' => 'int',
                'description' => '模型ID',
                'required' => 1,
            ],
        ]
    ],

    'POST /ability/(:am_id)/dimension' => [
        'description' => '纬度创建',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseAbility/createDimension',
        'inputs' => [
            'am_id' => [
                'type' => 'int',
                'description' => '模型ID',
                'required' => 1
            ],
            'level' => [
                'type' => 'int',
                'description' => '纬度level',
                'required' => 1
            ],
            'record' => [
                'type' => 'array',
                'subtype' => 'EnterpriseAbilityDimension',
                'description' => '纬度字段的详细验证',
                'required' => 1
            ]
        ]
    ],

    'PUT /ability/(:am_id)/dimension' => [
        'description' => '纬度编辑',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseAbility/updateDimension',
        'inputs' => [
            'am_id' => [
                'type' => 'int',
                'description' => '模型ID',
                'required' => 1
            ],
            'level' => [
                'type' => 'int',
                'description' => '纬度level',
                'required' => 1
            ],
            'record' => [
                'type' => 'array',
                'subtype' => 'EnterpriseAbilityDimension',
                'description' => '纬度字段的详细验证',
                'required' => 1
            ]
        ]
    ],
];
