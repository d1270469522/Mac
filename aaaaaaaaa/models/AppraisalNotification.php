<?php
/**
 * 评估通知：model
 * author：天尽头流浪
 */

namespace App\Models;

use App\Utils;
use MongoDB\BSON\Regex;
use App\Common\Sequence;
use App\Common\BaseModel;
use App\Common\Constants;
use Key\Database\Mongodb;
use Key\Records\Pagination;
use App\Common\QueueMessage;
use Key\Filesystem\FileFactory;
use Key\AsyncTask\DelayTaskProducer;
use Psr\Log\InvalidArgumentException;

class EnterpriseNotification extends BaseModel
{
    // 通知类型：1-邮件；2-短信
    const NOTIFICATION_EMAIL = 1;
    const NOTIFICATION_MESSAGE = 2;

    // 通知人员：1-全部人员；2-未参评人员；3-指定人员
    const ALL_EMPLOYEE = 1;
    const NO_ANSWER_EMPLOYEE = 2;
    const APPOINTL_EMPLOYEE = 3;

    /**
     * 评估通知
     */
    public function sendNotification($appraisal_id, $record)
    {
        $record = $record->toArray();

        $eids = [];

        // 整合需要通知的 eid 集合
        $eids = $this->getSendEids($appraisal_id, $record);

        // 添加一条记录：记录发送的内容，对象等基本信息
        $notification_id = $this->createNotification($appraisal_id, $record, $eids, count($eids));

        // 开启两个 MQ，
        // 第一个MQ：批量入库，为批量通知做准备
        // 第一个MQ：批量通知
        $this->appraisalNotificationQueue($appraisal_id, $notification_id, $eids, $record);

        return $notification_id;
    }

    /**
     * 获取需发送通知的eids
     */
    protected function getSendEids($appraisal_id, $record)
    {
        // 获取通知范围内的人员
        $eids = [];

        switch ($record['range']) {
            // 全部人员
            case self::ALL_EMPLOYEE:
                $cond = [
                    'aid' => $this->aid,
                    'a_id' => $appraisal_id,
                    'status' => 1,
                ];
                $eids = $this->getEids($cond);
                break;
            // 未参评人员
            case self::NO_ANSWER_EMPLOYEE:
                $cond = [
                    'aid' => $this->aid,
                    'a_id' => $appraisal_id,
                    'status' => 1,
                    'is_answer' => 0,
                ];
                $eids = $this->getEids($cond);
                break;
            // 指定人员
            case self::APPOINTL_EMPLOYEE:
                $eids = $record['eids'];
                break;
        }

        return $eids;
    }

    /**
     * 根据评估表中的 no 获取员工表中的 ID
     */
    public function getEids($cond = [])
    {
        $db = $this->getMongoMasterConnection();

        // 查询评估关系表：评估人编号
        $relations = $db->fetchAll(Constants::COLL_ENTERPRISE_APPRAISAL_RELATIONS, $cond);
        // 把编号提取出来
        $no_arr = array_values(array_unique(array_column($relations, 'assessed_people_no')));

        $cond = [
            'aid' => $this->aid,
            'no' => ['$in' => $no_arr],
            'status' => 1,
        ];
        // 查询编号对应的ID
        $employee = $db->fetchAll(Constants::COLL_EMPLOYEE, $cond);
        // 把ID提取出来
        $eid_arr = array_values(array_unique(array_column($employee, 'id')));

        return $eid_arr;
    }

    /**
     * 评估通知创建 - 添加通知记录
     */
    public function createNotification($appraisal_id, $record, $eids, $total)
    {
        $db = $this->getMongoMasterConnection();

        $employee_model = new Employee($this->app);

        // 获取员工信息
        $employee = $employee_model->view($this->eid);

        // 获取员工姓名
        $display = ArrayGet($employee, 'display', '');

        // 计划发送时间：转化成 mongodb 的时间格式
        if($record['send_time']) {
            $send_time = Mongodb::getMongoDate((string)$record['send_time'] / 1000);
        } else {
            $send_time = Mongodb::getMongoDate();
        }

        $data = [
            'id' => Sequence::getSeparateId('plan_notification', $this->aid),
            'aid' => $this->aid,
            'a_id' => $appraisal_id,
            'eid' => $this->eid,
            'status' => 1,
            'display' => $display,
            'type' => $record['type'], // 1、邮件；2、站内信
            'title' => $record['title'],
            'content' => $record['content'],
            'range' => $record['range'], // 1、全部人员；2、未参评人员；3、指定人员
            'eids' => $eids,
            'total' => $total,
            'is_send_now' => $record['is_send_now'], // 1、立即发送；2、指定时间
            'send_time' => $send_time,
            'actual_send_time' => null,
            'is_send' => 0,
            'created' => Mongodb::getMongoDate(),
            'updated' => Mongodb::getMongoDate(),
        ];

        $result = $db->insert(Constants::COLL_ENTERPRISE_APPRAISAL_NOTIFICATION, $data);

        return $result ? $data['id'] : false;
    }

    /**
     * 第一个MQ：批量入库，为批量通知做准备
     */
    public function appraisalNotificationQueue($appraisal_id, $notification_id, $eids, $record)
    {
        $queue = new BaseQueue($this->app);

        $message = new QueueMessage($this->aid, $this->eid);

        $params = [
            'appraisal_id' => $appraisal_id,
            'notification_id' => $notification_id, // 通知的综合记录（一条）
            'eids' => $eids,
            'notification_info' => $record,
        ];

        // 存储队列信息
        $q_id = $queue->store($queue::QUEUE_APPRAISAL_NOTIFICATION, $params);

        $params['q_id'] = $q_id;

        // 设置数据项
        $message->setPairs($params);

        // 向队列发布消息
        $queue->queuePublish($queue::QUEUE_APPRAISAL_NOTIFICATION, $message);

        return $q_id;
    }

    /**
     * 第一个MQ：批量入库，为批量通知做准备：具体操作
     */
    public function operateAppraisalNotification($appraisal_id, $notification_id, $eids, $notification_info)
    {
        // 批量创建通知详情列表
        $this->batchCreateNotificationDetail($appraisal_id, $notification_id, $eids, $notification_info);

        // 发送通知 is_send_now：1、立即发送；2、定时发送
        if ($notification_info['is_send_now'] == 1 ) {

            // 立即发送：第二个 MQ
            $this->sendNotificationQueue($appraisal_id, $notification_id, $eids, $notification_info);
        } else {
            echo '定时发送：operateAppraisalNotification';die;
            // 定时发送：第二个 MQ
            $job_id = $this->sendDelayTask($appraisal_id, $notification_id, $eids, $notification_info);
            $this->updateJobIdById($notification_id, $job_id);
        }

        return true;
    }

    /**
     * 第一个MQ：批量入库，为批量通知做准备：具体操作 - 创建通知详情（50条为一组进行入库）
     */
    public function batchCreateNotificationDetail($appraisal_id, $notification_id, $eids, $notification_info)
    {
        $db = $this->getMongoMasterConnection();

        $employee_model = new Employee($this->app);

        // 通知记录的默认必要数据
        $default_data = [
            'aid' => $this->aid,
            'a_id' => $appraisal_id, // 评估ID
            'notification_id' => $notification_id, // 通知综合记录的ID
            'type' => $notification_info['type'], // 通知类型：1、邮件；2、站内信
            'title' => $notification_info['title'],
            'content' => $notification_info['content'] ?: '',
            'is_send' => 0, // 是否发送
            'is_read' => 0, // 是否已读
            'errcode' => 0,
            'errmsg' => '',
            'status' => 0, // 状态：0、待发送；2、发送成功；3、定时发送；4、取消发送；5、发送成功；6、发送失败
            'created' => Mongodb::getMongoDate(),
            'updated' => Mongodb::getMongoDate(),
        ];

        // array_chunk：将一个数组分割成多个；每50个为一组，进行入库
        $chunk_employee_ids = array_chunk($eids, 50);

        $result = true;

        foreach ($chunk_employee_ids as $value) {

            $info = $employee_model->filters(['eids' => array_values($value)]);
            $data = [];

            // 组装批量入库的数据
            foreach ($info as $val) {
                $item = [
                    'eid' => ArrayGet($val, 'id', 0),
                    'employee_no' => ArrayGet($val, 'no', ''),
                    'display' => ArrayGet($val, 'display', ''),
                    'department_id' => ArrayGet($val, 'department_id', 0),
                    'department_name' => ArrayGet($val, 'department_name', ''),
                    'position_id' => ArrayGet($val, 'position_id', 0),
                    'position_name' => ArrayGet($val, 'position_name', ''),
                    'mobile' => ArrayGet($val, 'mobile', ''),
                    'email' => ArrayGet($val, 'email', ''),
                ];

                $data[] = array_merge($default_data, $item);
            }

            if ($data) {

                // 批量入库
                $result = $db->batchInsert(Constants::COLL_ENTERPRISE_APPRAISAL_NOTIFICATION_DETAIL, $data);
            }
        }

        return $result;
    }

    /**
     * 第二个MQ：通知发送 - 立即发送
     */
    public function sendNotificationQueue($appraisal_id, $notification_id, $eids = [], $notification_info = [], $is_send_now = 1)
    {
        $params = [
            'appraisal_id' => $appraisal_id,
            'notification_id' => $notification_id,
            'notification_info' => $notification_info,
            'eids' => $eids,
            'is_send_now' => $is_send_now,
        ];

        $queue = new BaseQueue($this->app);
        $message = new QueueMessage($this->aid, $this->eid, $params);

        // 批量发送通知：多个发布消息
        $q_id = $queue->multiplePublish($queue::QUEUE_APPRAISAL_SEND_NOTIFICATION, $message, 10, true);

        return $q_id;
    }

    /**
     * 第二个MQ：通知发送 - 具体操作
     */
    public function sendNotificationDetail($appraisal_id, $notification_id, $eids = [], $notification_info = [], $is_send_now = 1)
    {
        // 定时发送
        if (! $is_send_now) {
            $notification_info = $this->viewNotification($appraisal_id, 1, $notification_id);
            $eids = $this->getSendEids($appraisal_id, $notification_info);
        }

        // 如果没有 eids 则不发送，默认发送成功
        if (! $eids) {
            return true;
        }

        // 修改发送详情，设置为发送成功，以及编辑发送时间
        $this->notificationSend($notification_id, $appraisal_id);

        switch ($notification_info['type']) {
            case self::NOTIFICATION_EMAIL: // 邮件
                return $this->sendEmail($appraisal_id, $notification_id, $eids, $notification_info, $is_send_now);
                break;
            case self::NOTIFICATION_MESSAGE: // 站内信
                return true;
                break;
        }
    }

    /**
     * 修改是否发送，不体现是否成功，只表示是否发送的动作
     */
    public function notificationSend($notification_id, $appraisal_id)
    {
        $db = $this->getMongoMasterConnection();

        $cond = [
            'id' => $notification_id,
            'a_id' => $appraisal_id,
            'aid' => $this->aid,
        ];

        $set = [
            '$set' => [
                'is_send' => 1,
                'updated' => Mongodb::getMongoDate()
            ]
        ];

        $result = $db->update(Constants::COLL_ENTERPRISE_APPRAISAL_NOTIFICATION, $cond, $set);

        return $result;
    }


    /**
     * 查看通知详情 - 一条记录
     */
    public function viewNotification($appraisal_id, $notification_id = 0)
    {
        $db = $this->getMongoMasterConnection();

        $cond = [
            'id' => $notification_id,
            'aid' => $this->aid,
            'appraisal_id' => $appraisal_id,
        ];

        $result = $db->fetchRow(Constants::COLL_ENTERPRISE_APPRAISAL_NOTIFICATION, $cond);
        Utils::convertMongoDateToTimestamp($result);

        return $result ?: [];
    }

    /**
     * 发送通知邮件
     */
    public function sendEmail($appraisal_id, $notification_id, $eids, $notification_info)
    {
        $queueMail = new QueueMail($this->app);
        $employee_model = new Employee($this->app);

        // 发送邮件的 默认必填参数
        $params = [
            'send_time' => date('Y-m-d', time()),
        ];

        // 回调参数
        $callbackParams = [
            'aid' => $this->aid,
            'appraisal_id' => $appraisal_id,
            'notification_id' => $notification_id,
        ];

        // 每次发送 1000 个邮箱
        $pg = new Pagination();
        $skip = 1;
        $limit = 1000;

        do {
            // 设置分页
            $pg->setPage($skip)->setItemsPerPage($limit);

            // 设置要查询的字段
            $employee_fields = [
                'id' => 1,
                'email' => 1,
                'display' => 1
            ];

            // 查询员工信息
            $employees = $employee_model->filters(['eids' => $eids], null, $pg, $employee_fields);

            // 遍历1000个员工信息，依次发邮件
            foreach ($employees as $k => $val) {

                $email = ArrayGet($val, 'email', '');
                $params['display'] = ArrayGet($val, 'display', '');
                $callbackParams['eid'] = $val['id'];

                if ($email) {

                    $queueMail->setTo('1270469522@qq.com')
                        ->setSubject($notification_info['title'])
                        ->setParams($params)
                        ->setMessage($notification_info['content'])
                        ->setCallback('AppraisalNotification::emailCallback')
                        ->setCallbackParams($callbackParams)
                        ->publishV2();
                    $this->emailCallback(true, '', $callbackParams);
                } else {

                    $this->emailCallback(false, '邮箱缺失', $callbackParams);
                }
            }

            $skip++;

        } while ($employees);

        return true;
    }

    /**
     * 第二个MQ：通知发送 - 具体操作 - 邮件回调
     */
    public function emailCallback($is_success, $errmsg, $params)
    {
        $status = 0;

        if ($is_success) {
            $status = 1;
        }

        $this->handleEmailCallback($status, $errmsg, $params);
    }

    /**
     * notification手动email通知回调
     */
    protected function handleEmailCallback($status, $errmsg, $params)
    {
        $db = $this->getMongoMasterConnection();

        $aid = ArrayGet($params, 'aid', 0);

        $cond = [
            'aid' => $aid ?: $this->aid,
            'appraisal_id' => $params['appraisal_id'],
            'learning_type' => $params['learning_type'],
            'notification_id' => $params['notification_id'],
        ];

        if ($params['eid']) {
            $cond['eid'] = $params['eid'];
        }

        $db->update(Constants::COLL_ENTERPRISE_APPRAISAL_NOTIFICATION_DETAIL, $cond, [
            '$set' => [
                'is_send' => 1,
                'status' => $status,
                'errmsg' => $errmsg,
                'updated' => Mongodb::getMongoDate()
            ]
        ]);
    }

    /**
     * 定时发送通知
     */
    public function sendDelayTask($appraisal_id, $notification_id, $eids, $notification_info)
    {
        // 预期发送时间
        $send_time = $notification_info['send_time'] / 1000;

        if ($send_time instanceof \MongoDB\BSON\UTCDateTime) {
            $send_time = (int)$send_time->toDateTime()->format('U');
        }
        $send_time = (int)$send_time;

        if (!is_int($send_time)) {
            throw new InvalidArgumentException('Invalid notice time of the notification');
        }

        $now = time();

        // 还需要多长时间发送
        $delay = $send_time - $now;

        if ($delay > 0) {
            $producer = new DelayTaskProducer(Constants::SERVICES_LEARNING_NOTICE_DELAY, $this->app);
            $producer->put([
                'aid' => $this->aid,
                'eid' => $this->eid,
                'appraisal_id' => $appraisal_id,
                'notification_id' => $notification_id,
                'notification_info' => $notification_info,
                'eids' => $eids,
            ], $delay, 20);
            return $producer->getJobId();
        } else {
            return $this->sendNotificationQueue($appraisal_id, $notification_id, $notification_info, $eids, 1, $type, $is_auto);
        }
    }

    /**
     * 更新定时发送的job_id
     */
    public function updateJobIdById($notification_id, $job_id)
    {
        $db = $this->getMongoMasterConnection();

        $cond = [
            'aid' => $this->aid,
            'status' => static::ENABLED,
            'id' => $notification_id,
        ];

        $set = [
            '$set' => [
                'job_id' => $job_id,
                'updated' => Mongodb::getMongoDate(),
            ]
        ];

        $result = $db->update(Constants::COLL_PLAN_NOTIFICATION, $cond, $set);

        return $result ?: false;
    }
}
