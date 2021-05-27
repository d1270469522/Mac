<?php
/**
 * 要素管理：路由
 * author：天尽头流浪
 */
return [

    'POST /element' => [
        'description' => '要素创建',
        'contributors' => '天尽头流浪',
        'controller' => '/AppraisalElement/create',
        'inputs' => [
            'record' => [
                'type' => 'AppraisalElement',
                'description' => '要素创建时：字段详细验证',
                'required' => 1,
            ],
        ],
    ],

    'GET /element' => [
        'description' => '要素列表',
        'contributors' => '天尽头流浪',
        'controller' => '/AppraisalElement/list',
        'inputs' => [
            'enable' => [
                'type' => 'int',
                'description' => '是否启用：0-全部；1-已启用；2-已禁用',
                'default' => 0,
            ],
            'sort' => [
                'type' => 'int',
                'description' => '排序：1-正序；2-倒序',
                'default' => 2,
            ],
            'keyword' => [
                'type' => 'string',
                'description' => '搜索关键字：要素名称',
            ]
            'pg' => [
                'type' => 'pagination',
                'description' => '分页',
            ],
        ],
    ],

    'GET /element/(:id)' => [
        'description' => '要素详情',
        'contributors' => '天尽头流浪',
        'controller' => '/AppraisalElement/detail',
        'inputs' => [
            'id' => [
                'type' => 'int',
                'description' => '要素ID',
                'required' => 1,
            ],
        ],
    ],

    'PUT /element/(:id)' => [
        'description' => '要素编辑',
        'contributors' => '天尽头流浪',
        'controller' => '/AppraisalElement/update',
        'inputs' => [
            'id' => [
                'type' => 'int',
                'description' => '要素ID',
                'required' => 1,
            ],
            'record' => [
                'type' => 'AppraisalElement',
                'description' => '要素编辑时：字段详细验证',
                'required' => 1,
            ],
        ],
    ],

    'DELETE /element/(:id)' => [
        'description' => '删除要素',
        'contributors' => '天尽头流浪',
        'controller' => '/AppraisalElement/delete',
        'inputs' => [
            'id' => [
                'type' => 'int',
                'description' => '要素ID',
                'required' => 1,
            ],
        ],
    ],

/** = = = = = =下面部分：要素题 = = = = = = **/

    'POST /element/(:element_id)/question' => [
        'description' => '要素题创建',
        'contributors' => '天尽头流浪',
        'controller' => '/AppraisalElement/createQuestion',
        'inputs' => [
            'element_id' => [
                'type' => 'int',
                'description' => '要素ID',
                'required' => 1,
            ],
            'record' => [
                'type' => 'array',
                'subtype' => 'EnterpriseQuestion',
                'description' => '要素题创建时：字段详细验证',
                'required' => 1,
            ],
        ],
    ],

    'GET /element/(:element_id)/question' => [
        'description' => '要素题列表',
        'contributors' => '天尽头流浪',
        'controller' => '/AppraisalElement/detailQuestion',
        'inputs' => [
            'element_id' => [
                'type' => 'int',
                'description' => '要素题ID',
                'required' => 1,
            ],
            'pg' => [
                'type' => 'pagination',
                'description' => '分页',
            ],
        ],
    ],

    'PUT /element/(:element_id)/question' => [
        'description' => '要素题编辑',
        'contributors' => '天尽头流浪',
        'controller' => '/AppraisalElement/updateQuestion',
        'inputs' => [
            'element_id' => [
                'type' => 'int',
                'description' => '要素ID',
                'required' => 1,
            ],
            'record' => [
                'type' => 'array',
                'subtype' => 'EnterpriseQuestion',
                'description' => '要素题编辑时：字段详细验证',
                'required' => 1,
            ],
        ],
    ],

    'POST /element/(:element_id)/question/import' => [
        'description' => '要素题导入',
        'controller' => '/AppraisalElement/importQuestion',
        'inputs' => [
            'element_id' => [
                'type' => 'int',
                'description' => '要素ID',
                'required' => 1,
            ],
            'file' => [
                'type' => 'file',
                'description' => '上传文件',
                'required' => 1,
                'exts' => 'xls,xlsx',
                'folder' => 'element_question_import'
            ]
        ]
    ],
];
