<?php
/**
 * 评估活动通知
 * author：天尽头流浪
 */

namespace App\Records;

use Key\Abstracts\BaseRecord;

class EnterpriseAppraisalNotification extends BaseRecord
{
    protected $fields = [
        'type' => [
            'type' => 'int',
            'description' => '通知类型：1、邮件；2、站内信',
            'required' => 1,
        ],
        'title' => [
            'type' => 'string',
            'description' => '通知标题',
            'required' => 1,
        ],
        'content' => [
            'type' => 'string',
            'description' => '通知内容',
        ],
        'range' => [
            'type' => 'int',
            'description' => '通知范围',
            'display' => '1、全部人员；2、未参评人员；3、指定人员',
            'required' => 1,
        ],
        'eids' => [
            'type' => 'array',
            'description' => '指定通知人的eid，当「通知范围」是「指定人员」的时候',
            'display' => '[1, 2, 3, 4, .....]',
            'default' => [],
        ],
        'is_send_now' => [
            'type' => 'int',
            'description' => '通知时间：1、立即发送；2、定时发送',
            'required' => 1,
        ],
        'send_time' => [
            'type' => 'string',
            'description' => '通知时间，定时发送的时候不需要填写',
            'default' => '',
        ],
    ];
}
