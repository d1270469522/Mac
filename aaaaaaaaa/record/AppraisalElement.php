<?php
/**
 * 要素创建时：字段详细验证
 * author：天尽头流浪
 */

namespace App\Records;

use Key\Abstracts\BaseRecord;

class EnterpriseElement extends BaseRecord
{
    protected $fields = [
        'no' => [
            'type' => 'string',
            'description' => '要素编号',
            'required' => 1,
        ],
        'name' => [
            'type' => 'string',
            'description' => '要素名称'
            'required' => 1,
        ],
        'desc' => [
            'type' => 'string',
            'description' => '要素描述'
        ],
        'enable' => [
            'type' => 'int',
            'description' => '状态：1、启用；2、禁用'
            'required' => 1,
        ],
        'certificate_id' => [
            'type' => 'int',
            'description' => '0、没有；1：有证书'
            'display' => '证书id ',
            'default' => 0,
        ],
        'certificate_name' => [
            'type' => 'string',
            'description' => '证书名称'
        ],
    ];
}
