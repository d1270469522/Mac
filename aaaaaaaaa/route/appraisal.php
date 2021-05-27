<?php
/**
 * 评估活动：路由
 * author：天尽头流浪
 */
return [

    'POST /appraisal' => [
        'description' => '评估创建',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseAppraisal/create',
        'inputs' => [
            'record' => [
                'type' => 'EnterpriseAppraisal',
                'description' => '评估字段的详细验证',
                'required' => 1,
            ],
        ]
    ],

    'PUT /appraisal/(:id)' => [
        'description' => '评估编辑',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseAppraisal/update',
        'inputs' => [
            'id' => [
                'type' => 'int',
                'description' => '评估ID',
                'required' => 1
            ],
            'record' => [
                'type' => 'EnterpriseAppraisal',
                'description' => '评估字段的详细验证',
                'required' => 1
            ],
        ]
    ],

    'POST /appraisals' => [
        'description' => '评估列表',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseAppraisal/list',
        'inputs' => [
            'type' => [
                'type' => 'int',
                'description' => '筛选评估状态：0、全部；1、进行中；2、已开始；3、已结束；4、草稿',
                'default' => 0,
            ],
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

    'GET /appraisal/(:id)' => [
        'description' => '评估详情',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseAppraisal/detail',
        'inputs' => [
            'id' => [
                'type' => 'int',
                'description' => '评估ID',
                'required' => 1,
            ],
        ]
    ],

    'DELETE /appraisal/(:id)' => [
        'description' => '评估删除',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseAppraisal/delete',
        'inputs' => [
            'id' => [
                'type' => 'int',
                'description' => '评估ID',
                'required' => 1,
            ],
        ]
    ],

/** = = = = = = = = = 评估选择问卷，设定评估关系 = = = = = = = = = = = **/

    'PUT /appraisal/(:a_id)/relations' => [
        'description' => '选择问卷、设定评估关系',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseAppraisal/setRelations',
        'inputs' => [
            'a_id' => [
                'type' => 'int',
                'description' => '评估ID',
                'required' => 1,
            ],
            'questionnaire' => [
                'type' => 'array',
                'description' => '选择的问卷信息',
                'display' => '[{questionnaire_id:1, questionnaire_name:问卷名称}]',
            ],
            'relations' => [
                'type' => 'array',
                'description' => '评估对应的关系',
                'display' => '[{role_id:1, role_name:评估关系, questionnaire_name:问卷名称, weight:100}]',
            ],
        ]
    ],

    'POST /appraisal/(:a_id)/relationsInfo' => [
        'description' => '评估关系详情创建',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseAppraisal/createRelationsInfo',
        'inputs' => [
            'a_id' => [
                'type' => 'int',
                'description' => '评估主表ID',
                'required' => 1,
            ],
            'record' => [
                'type' => 'EnterpriseAppraisalRelationsInfo',
                'description' => '评估关系字段详细验证',
                'required' => 1,
            ],
        ]
    ],

    'POST /appraisal/(:a_id)/relationsInfoList' => [
        'description' => '评估关系详情列表',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseAppraisal/relationsInfoList',
        'inputs' => [
            'a_id' => [
                'type' => 'int',
                'description' => '评估ID',
                'required' => 1,
            ],
            'keyword' => [
                'type' => 'string',
                'description' => '关键字',
            ],
            'pg' => [
                'type' => 'pagination',
                'description' => '分页',
            ]
        ]
    ],

    'DELETE /appraisal/relationsInfo/(:id)' => [
        'description' => '评估关系详情删除',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseAppraisal/deleteRelationsInfo',
        'inputs' => [
            'id' => [
                'type' => 'int',
                'description' => '评估关系详情ID',
                'required' => 1,
            ],
        ]
    ],

    'POST /appraisal/(:a_id)/relationsInfo/import' => [
        'description' => '评估关系详情导入',
        'controller' => '/EnterpriseAppraisal/importRelationInfo',
        'inputs' => [
            'a_id' => [
                'type' => 'int',
                'description' => '评估ID',
                'required' => 1,
            ],
            'type' => [
                'type' => 'int',
                'description' => '开放方式：1、内部；2、外部',
                'required' => 1,
            ],
            'file' => [
                'type' => 'file',
                'description' => '上传文件',
                'required' => 1,
                'exts' => 'xls,xlsx',
                'folder' => 'appraisal_relations_info',
            ]
        ]
    ],

    'PUT /appraisal/(:id)/publish' => [
        'description' => '评估活动发布',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseAppraisal/publish',
        'inputs' => [
            'id' => [
                'type' => 'int',
                'description' => '测评id',
                'required' => 1,
            ],
        ]
    ],

    'PUT /appraisal/(:id)/setOver' => [
        'description' => '评估活动结束',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseAppraisal/setOver',
        'inputs' => [
            'id' => [
                'type' => 'int',
                'description' => '测评id',
                'required' => 1,
            ],
        ]
    ],

/** = = = = = = = = = 评估发送通知 = = = = = = = = = = = **/

    'POST /appraisal/(:a_id)/notification' => [
        'description' => '评估通知',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseAppraisal/createNotification',
        'inputs' => [
            'a_id' => [
                'type' => 'int',
                'description' => '评估ID',
                'required' => 1,
            ],
            'record' => [
                'type' => 'EnterpriseAppraisalNotification',
                'description' => '评估通知的字段的详细验证',
                'required' => 1,
            ],
        ]
    ],


/** = = = = = = = = =  = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = **
 *                                     下方是移动端接口                                 *
 ** = = = = = = = = = = = = = =  = = = = = = = = = = = = = = = = = = = = = = = = = = **/


    'GET /app/appraisal/getMyAppraisals' => [
        'description' => '评估列表：根据「评估关系」，查询“我”作为评估人时，将要评估的活动',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseAppraisal/getMyAppraisalsV1',
        'inputs' => [
            'type' => [
                'type' => 'int',
                'description' => '进行状态',
                'display' => '0-全部；1-未开始；2-进行中；3-已结束',
                'default' => 0,
            ],
            'is_answer' => [
                'type' => 'int',
                'description' => '是否回答',
                'display' => '0-全部；1-已回答；2-未回答',
                'default' => 0,
            ],
            'keywords' => [
                'type' => 'string',
                'description' => '关键字：评估名称',
            ],
            'pg' => [
                'type' => 'pagination',
                'description' => '分页',
            ],
        ]
    ],

    'GET /app/appraisal/getOneAppraisalInfo/(:a_id)' => [
        'description' => '某一个【评估活动】详情',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseAppraisal/getOneAppraisalInfoV1',
        'inputs' => [
            'a_id' => [
                'type' => 'int',
                'description' => '评估ID',
                'required' => 1,
            ],
        ]
    ],

    'GET /app/appraisal/(:a_id)/relations' => [
        'description' => '评估详情页面，下方的评估对象列表：该评估下，我作为评估人需要评估的所有对象',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseAppraisal/getRelationsV1',
        'inputs' => [
            'a_id' => [
                'type' => 'int',
                'description' => '评估ID',
                'required' => 1,
            ],
            'pg' => [
                'type' => 'pagination',
                'description' => '分页',
            ],
        ]
    ],

    'GET /app/appraisal/(:a_id)/allQuestions' => [
        'description' => '评估活动页面的所有题目',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseAppraisal/allQuestionsV1',
        'inputs' => [
            'a_id' => [
                'type' => 'int',
                'description' => '评估ID',
                'required' => 1,
            ],
            'type' => [
                'type' => 'int',
                'description' => '是否获取答案：0-不获取；1-获取',
                'default' => 0,
            ],
        ]
    ],

    'POST /app/appraisal/(:a_id)/answers' => [
        'description' => '评估活动提交答案',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseAppraisal/createAnswersV1',
        'inputs' => [
            'a_id' => [
                'type' => 'int',
                'description' => '评估ID',
                'required' => 1,
            ],
            'record' => [
                'type' => 'array',
                'subtype' => 'EnterpriseAppraisalAnswers',
                'description' => '评估提交答案字段的详细验证',
                'required' => 1,
            ],
        ]
    ],

/** = = = = = = = = =  = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = **
 *                                  下方是移动端 - 统计                                  *
 ** = = = = = = = = = = = = = =  = = = = = = = = = = = = = = = = = = = = = = = = = = **/

    'GET /app/appraisal/(:a_id)/getAppraisalFinalScore' => [
        'description' => '评估活动，最终得分',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseAppraisal/getAppraisalFinalScoreV1',
        'inputs' => [
            'a_id' => [
                'type' => 'int',
                'description' => '评估ID',
                'required' => 1,
            ],
        ]
    ],

    'GET /app/appraisal/(:a_id)/relationResult' => [
        'description' => '统计各个评估关系【应评数量、实评数量】',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseAppraisal/relationResultV1',
        'inputs' => [
            'a_id' => [
                'type' => 'int',
                'description' => '评估ID',
                'required' => 1,
            ],
        ]
    ],

    'GET /app/appraisal/(:a_id)/getEveryElementFinalScore' => [
        'description' => '评估活动：各个要素【他评均分、自评得分、差值】',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseAppraisal/getEveryElementFinalScoreV1',
        'inputs' => [
            'a_id' => [
                'type' => 'int',
                'description' => '评估ID',
                'required' => 1,
            ],
        ]
    ],

    'GET /app/appraisal/(:a_id)/getEveryElement/questionFinalScore' => [
        'description' => '评估活动：指定要素-所有题目-【他评均分、自评得分、差值】',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseAppraisal/getQuestionFinalScoreV1',
        'inputs' => [
            'a_id' => [
                'type' => 'int',
                'description' => '评估ID',
                'required' => 1,
            ],
        ]
    ],

/** = = = = = = = = =  = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = **
 *                                  下方是PC端 - 统计                                   *
 ** = = = = = = = = = = = = = =  = = = = = = = = = = = = = = = = = = = = = = = = = = **/

    'GET /app/appraisal/(:a_id)/getEveryElementWithRelationFinalScore' => [
        'description' => '评估活动：各个要素【各种关系】得分',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseAppraisal/getEveryElementWithRelationFinalScoreV1',
        'inputs' => [
            'a_id' => [
                'type' => 'int',
                'description' => '评估ID',
                'required' => 1,
            ],
        ]
    ],

    'GET /app/appraisal/(:a_id)/getEveryElement/questionWithRelationFinalScore' => [
        'description' => '评估活动：指定要素-所有题目-【所有关系】-得分',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseAppraisal/getQuestionWithRelationFinalScoreV1',
        'inputs' => [
            'a_id' => [
                'type' => 'int',
                'description' => '评估ID',
                'required' => 1,
            ],
        ]
    ],

    'GET /app/appraisal/(:a_id)/getEveryQuestionOrder' => [
        'description' => '评估活动：优势项、劣势项展示【以题为单位】',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseAppraisal/getEveryQuestionOrderV1',
        'inputs' => [
            'a_id' => [
                'type' => 'int',
                'description' => '评估ID',
                'required' => 1,
            ],
            'type' => [
                'type' => 'int',
                'description' => '类型：1-优势项；2-劣势项',
                'display' => '优势项：分数倒序；劣势项；分数正序',
                'default' => 1,
            ],
        ]
    ],

    'GET /app/appraisal/(:a_id)/getEveryQuestionTextTopic' => [
        'description' => '评估活动：文本描述题 - 统计',
        'contributors' => '天尽头流浪',
        'controller' => '/EnterpriseAppraisal/getEveryQuestionTextTopicV1',
        'inputs' => [
            'a_id' => [
                'type' => 'int',
                'description' => '评估ID',
                'required' => 1,
            ],
        ]
    ],
];
