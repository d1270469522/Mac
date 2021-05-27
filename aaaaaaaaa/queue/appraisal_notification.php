<?php
/**
 * 评估活动 - 发通知 MQ
 * author：天尽头流浪
 */

require_once 'lib/QueueHandler.php';

class AppraisalNotification extends QueueHandler
{
    /**
     * Handle the queue message.
     * 处理队列消息
     */
    public function execute($message, $tag)
    {
        // 脚本开始时间
        $start = microtime(true);

        // 加载应用程序配置
        $this->loadAppConfigure();

        // 获取帐户ID
        $aid = $message->getAccountId();
        // 获取员工ID
        $eid = $message->getEmployeeId();

        $this->container['__CONSOLE__'] = 1;
        $this->container[\App\Common\Constants::SESSION_KEY_CURRENT_ACCOUNT_ID] = $aid;
        $this->container[\App\Common\Constants::SESSION_KEY_CURRENT_EMPLOYEE_ID] = $eid;

        $data = $message->all();

        // 获取参数信息
        $q_id = ArrayGet($data, 'q_id', 0);
        $eids = ArrayGet($data, 'eids', []);
        $appraisal_id = ArrayGet($data, 'appraisal_id', 0);
        $notification_id = ArrayGet($data, 'notification_id', 0);
        $notification_info = ArrayGet($data, 'notification_info', []);

        // 判断是否有 MQ 的 ID
        if ($q_id) {

            $enterprisal_notification_model = new \App\Models\EnterpriseNotification($this->container);

            try {

                $this->queueModel = new \App\Models\BaseQueue($this->container);

                // 设置 MQ 状态：进行中
                $this->queueModel->updateQueueProgress($q_id, \App\Models\BaseQueue::PROGRESS_STARTING);

                // MQ 具体操作
                $res = $enterprisal_notification_model->operateAppraisalNotification($appraisal_id, $notification_id, $eids, $notification_info);

                // 设置 MQ 状态：执行完(成功)
                $this->queueModel->updateQueueProgress($q_id, \App\Models\BaseQueue::PROGRESS_END, [
                    'res' => $res,
                    'msg' => 'success',
                    'elapsed' => microtime(true) - $start
                ]);

                if ($res) {
                    echo 'finished' . PHP_EOL;
                } else {
                    echo 'fail' . PHP_EOL;
                }
            } catch (Exception $ex) {

                echo 'Exception : (' . $ex->getCode() . ')' . $ex->getMessage() . PHP_EOL;
                echo $ex->getTraceAsString() . PHP_EOL;

                $this->queueModel->updateQueueProgress($q_id, \App\Models\BaseQueue::PROGRESS_FAIL, [
                    'code' => $ex->getCode(),
                    'elapsed' => microtime(true) - $start
                ]);

                if ($ex->getCode() == 64) { // WriteConcernError
                    try {
                        echo 'Try again...' . PHP_EOL;
                        $this->run($data, $aid, $tag); // Retry
                    } catch (Exception $ex) {
                        echo 'Exception : (' . $ex->getCode() . ')' . $ex->getMessage() . PHP_EOL;
                        echo $ex->getTraceAsString() . PHP_EOL;
                    }
                }
            }
        } else {

            echo 'Queue ID not found!' . PHP_EOL;
        }

        // 确认一条或多条消息
        $this->ack($tag);
    }
}

new AppraisalNotification('queue_appraisal_notification');
