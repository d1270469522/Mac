<?php

namespace App\Console\Commands;

use App\Consts\ResultCode;

use App\Models\AllocationRule;
use App\Models\LoanOrder;
use App\Models\LoanOrderTrack;
use App\Models\LoanRiskControlRecord;
use App\Models\LoanTrialFinal;
use App\Models\OrderRefuseTime;
use App\Models\OrderStatus;
use App\Models\OrderUserBasicInfo;
use App\Models\RefuseDelayRecord;
use App\Models\UserVipLevel;
use App\Models\BlackList;
use App\Models\UserAccess;
use App\Models\OrderUserBasicInfo1;
use App\Models\YinniRiskLog;
use App\Models\UserDevice;
use App\Models\AdminUser;
use App\Models\AdminAssign;
use App\Models\OrderProductInfo;
use App\Models\UserIziData;
use App\Models\LoanTrialPhone;

use App\Service\OrderFactory;
use App\Server\Assign;  // Jobs
// use App\Service\Assign;  // Console
use App\Server\BlackOrderHandle;
use App\Server\DateUtil;
use App\Server\advance\ApiClientFile;
use App\Server\advance\CurlClient;
use App\Server\AdvanceRisk;
// use App\Server\Mcrypter; // Jobs
use App\Service\Mcrypter; // Console
use App\Server\iziRisk;

use App\Events\RiskControlRefuse;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Models\PushToken;
use App\Server\PushMessage;
use App\Server\OverMessage;


class AutoTestJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AutoTestJobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '2、对接风控引擎';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('测试信审分配');
        $assignStatus = Assign::assign(1, 'phone', [['id' => 1]]);
        $this->info('完事了');
        // $this->info("发送短信...081316542924");
        // OverMessage::repayment('081316542924', 20000000, 1);
    }


    public function fengkong ()
    {

        $this->info("发送短信...081316542924");
        OverMessage::repayment('081316542924', 20000000, 1);

        $this->info("发送短信...81293353995");
        OverMessage::repayment('81293353995', 20000000, 1);

        die;

        // 在命令行打印一行信息

        // 读取【风控队列中】状态的订单
        $where = ['order_status' => 16];

        LoanOrder::walk($where, function ($order) {

            $this->info("开始风控......".$order->id);
            $hour = env('DELAY_PERIOD', 2);

            $orderBasicInfo   = OrderUserBasicInfo::getOne($order->basic_id);
            $orderProductInfo = OrderProductInfo::getOne($order->basic_id);
            $userAccess       = UserAccess::getByUserId($order->uid);

            $uuid = $userAccess->uuid;
            $relative_phone_first  = ltrim(preg_replace('/\D/','',str_replace('+62','',$orderBasicInfo->relative_phone_first)), '0');
            $relative_phone_second = ltrim(preg_replace('/\D/','',str_replace('+62','',$orderBasicInfo->relative_phone_second)), '0');
            $user_type   = $orderProductInfo->user_type;
            $bank_number = $orderBasicInfo->bank_number;
            $bank_name   = $orderBasicInfo->bank_name;


            // 危险职业直接拒绝拉黑 career
            $dangerousJobHandle = $this->dangerousJobHandle($orderBasicInfo->ktp_number, $order->id, $order->uid, $orderBasicInfo->career);
            if ($dangerousJobHandle) {
                $this->info("风控拒绝： 危险职业");
            } else {
                $this->info("风控通过： 职业不危险");
            }

            // 职业 career
            $careerHandle = $this->careerHandle($orderBasicInfo->ktp_number, $order->id, $order->uid, $orderBasicInfo->career);
            if ($careerHandle) {
                $this->info("风控拒绝： 职业");
            } else {
                $this->info("风控通过： 职业");
            }

            // 居住地 area
            $areaHandle = $this->areaHandle($orderBasicInfo->ktp_number, $order->id, $order->uid, $orderBasicInfo->district);
            if ($areaHandle) {
                $this->info("风控拒绝： 居住地");
            } else {
                $this->info("风控通过： 居住地");
            }

            // 学位
            $degreeHandle = $this->degreeHandle($orderBasicInfo->ktp_number, $order->id, $order->uid, $orderBasicInfo->education);
            if ($degreeHandle) {
                $this->info("风控拒绝： 学位");
            } else {
                $this->info("风控通过： 学位");
            }

            // 孩子数量
            $childNumHandle = $this->childNumHandle($orderBasicInfo->ktp_number, $order->id, $order->uid, $orderBasicInfo->children_number);
            if ($childNumHandle) {
                $this->info("风控拒绝： 孩子数量");
            } else {
                $this->info("风控通过： 孩子数量");
            }

            // 居住地时长
            $CheckResidenceLengthHandle = $this->CheckResidenceLengthHandle($orderBasicInfo->ktp_number, $order->id, $order->uid, $orderBasicInfo->living_period);
            if ($CheckResidenceLengthHandle) {
                $this->info("风控拒绝： 居住地时长");
            } else {
                $this->info("风控通过： 居住地时长");
            }

            // 工作时长
            $workingLifeHandle = $this->workingLifeHandle($orderBasicInfo->ktp_number, $order->id, $order->uid, $orderBasicInfo->work_period);
            if ($workingLifeHandle) {
                $this->info("风控拒绝： 工作时长");
            } else {
                $this->info("风控通过： 工作时长");
            }

            // 收入水平
            $imcomeLevelHandle = $this->imcomeLevelHandle($orderBasicInfo->ktp_number, $order->id, $order->uid, $orderBasicInfo->income_level);
            if ($imcomeLevelHandle) {
                $this->info("风控拒绝： 收入水平");
            } else {
                $this->info("风控通过： 收入水平");
            }

            // 年龄
            $ageHandle = $this->ageHandle($orderBasicInfo->ktp_number, $order->id, $order->uid);
            if ($ageHandle) {
                $this->info("风控拒绝： 年龄");
            } else {
                $this->info("风控通过： 年龄");
            }
            die;
        });

        $this->info("开始结束！！");
    }

    /**
     * [dangerousJobHandle 危险职业直接拒绝拉黑]
     *
     * @param  [type]  $ktp_number [description]
     * @param  integer $order_id   [description]
     * @param  integer $user_id    [description]
     * @param  integer $career     [description]
     * @return [type]              [description]
     */
    public function dangerousJobHandle($ktp_number, $order_id = 0, $user_id = 0, $career = 0)
    {
        $params = [
            10, //军人/士兵
            13, //警察
            33, //国家官员／国家组织者
            36, //信贷公司
            37, //律师
            38, //催收
            40  //记者
        ];

        if (in_array($career, $params)) {
            return true;
            // $this->refuseReasonDefine($order_id, 'RC_REFUSE_PULL_BLACKLIST_WHEN_RISK_OCCUPATION');
            // return $this->addDelayRecord($ktp_number, $order_id, $user_id);
        }

        return false;
    }

    /**
     * [careerHandle 职业]
     *
     * @param  [type]  $ktp_number [description]
     * @param  integer $order_id   [description]
     * @param  integer $user_id    [description]
     * @param  integer $career     [description]
     * @return [type]              [description]
     */
    public function careerHandle($ktp_number, $order_id = 0, $user_id = 0, $career = 0)
    {
        $params = [
            24,
            28,
            32,
        ];

        if (in_array($career, $params)) {
            return true;
            // $this->refuseReasonDefine($order_id, 'RC_REFUSE_CHECK_RISK_OCCUPATION');
            // return $this->addDelayRecord($ktp_number, $order_id, $user_id);
        }

        return false;
    }


    /**
     * [areaHandle 居住地]
     *
     * @param  [type]  $ktp_number [description]
     * @param  integer $order_id   [description]
     * @param  integer $user_id    [description]
     * @param  string  $area       [description]
     * @return [type]              [description]
     */
    public function areaHandle($ktp_number, $order_id = 0, $user_id = 0, $area = '')
    {
        $params = [
            "kalimantan",
            "papua",
            "sulawesi",
            "sumatra",
            "medan",
            "West Papua"
        ];

        if (in_array($area, $params)) {
            return true;
            // $this->refuseReasonDefine($order_id, 'RC_REFUSE_CHECK_RISK_AREA');
            // return $this->addDelayRecord($ktp_number, $order_id, $user_id);
        }

        return false;
    }


    /**
     * [degreeHandle 学位]
     *
     * @param  [type]  $ktp_number [description]
     * @param  integer $order_id   [description]
     * @param  integer $user_id    [description]
     * @param  integer $degree     [description]
     * @return [type]              [description]
     */
    public function degreeHandle($ktp_number, $order_id = 0, $user_id = 0, $degree = 0)
    {
        $params = [
            4
        ];

        if (in_array($degree, $params)) {
            return true;
            // $this->refuseReasonDefine($order_id, 'RC_REFUSE_CHECK_DEGREE');
            // return $this->addDelayRecord($ktp_number, $order_id, $user_id);
        }

        return false;
    }


    /**
     * [childNumHandle 孩子数量]
     *
     * @param  [type]  $ktp_number [description]
     * @param  integer $order_id   [description]
     * @param  integer $user_id    [description]
     * @param  integer $childNum   [description]
     * @return [type]              [description]
     */
    public function childNumHandle($ktp_number, $order_id = 0, $user_id = 0, $childNum = 0)
    {
        $params = [
            5,
            6
        ];

        if (in_array($childNum, $params)) {
            return true;
            // $this->refuseReasonDefine($order_id, 'RC_REFUSE_CHECK_CHILD_NUM');
            // return $this->addDelayRecord($ktp_number, $order_id, $user_id);
        }

        return false;
    }

    /**
     * [CheckResidenceLengthHandle 检查居住时长]
     *
     * @param  [type]  $ktp_number [description]
     * @param  integer $order_id   [description]
     * @param  integer $user_id    [description]
     * @param  integer $childNum   [description]
     * @return [type]              [description]
     */
    public function CheckResidenceLengthHandle($ktp_number, $order_id = 0, $user_id = 0, $residenceLength = 0)
    {
        $params = [
            1,
            2
        ];

        if (in_array($residenceLength, $params)) {
            return true;
            // $this->refuseReasonDefine($order_id, 'RC_REFUSE_CHECK_RESIDENCE_LENGTH');
            // return $this->addDelayRecord($ktp_number, $order_id, $user_id);
        }

        return false;
    }

    /**
     * [workingLifeHandle 工作时长]
     *
     * @param  [type]  $ktp_number [description]
     * @param  integer $order_id   [description]
     * @param  integer $user_id    [description]
     * @param  integer $childNum   [description]
     * @return [type]              [description]
     */
    public function workingLifeHandle($ktp_number, $order_id = 0, $user_id = 0, $workingLife = 0)
    {
        $params = [
            1
        ];

        if (in_array($workingLife, $params)) {
            return true;
            // $this->refuseReasonDefine($order_id, 'RC_REFUSE_CHECK_WORKING_LIFE');
            // return $this->addDelayRecord($ktp_number, $order_id, $user_id);
        }

        return false;
    }


    /**
     * [imcomeLevelHandle 收入水平]
     *
     * @param  [type]  $ktp_number [description]
     * @param  integer $order_id   [description]
     * @param  integer $user_id    [description]
     * @param  integer $childNum   [description]
     * @return [type]              [description]
     */
    public function imcomeLevelHandle($ktp_number, $order_id = 0, $user_id = 0, $imcomeLevel = 0)
    {
        $params = [
            1
        ];

        if (in_array($imcomeLevel, $params)) {
            return true;
            // $this->refuseReasonDefine($order_id, 'RC_CHECK_IMCOME_LEVEL');
            // return $this->addDelayRecord($ktp_number, $order_id, $user_id);
        }

        return false;
    }


    /**
     * [ageHandle 年龄]
     *
     * @param  [type]  $ktp_number [description]
     * @param  integer $order_id   [description]
     * @param  integer $user_id    [description]
     * @return [type]              [description]
     */
    public function ageHandle($ktp_number, $order_id = 0, $user_id = 0)
    {
        $age = $this->getAge($ktp_number);

        if ($age <= 20 || $age >= 55) {
            return true;
            // $this->refuseReasonDefine($order_id, 'RC_REFUSE_CHECK_AGE');
            // return $this->addDelayRecord($ktp_number, $order_id, $user_id);
        }

        return false;
    }


    /**
     * [getAge 根据ktp获取年龄]
     *
     * @param  [type] $ktp_number [description]
     * @return [type]             [description]
     */
    public function getAge($ktp_number)
    {
        $age = '';
        if ($ktp_number) {
            $birthYear = substr($ktp_number, 10, 2);
            if (!$birthYear)
                return $age;

            if ($birthYear>11) {
                $birthYear = '19'.$birthYear;
            } else {
                $birthYear = '20'.$birthYear;
            }

            $age = date('Y')-$birthYear;
        }

        return $age;
    }












    /**
     * [app_loan_count 现金贷 APP（1）]
     *
     * @return [type] [description]
     */
    public function app_loan_count ()
    {
        $res = DB::table('user_device')
                    ->whereIn('uid', function ($query) {
                        return $query->from('loan_order')
                                    ->select('uid')
                                    ->where('order_status', 11);
                                    // ->where('order_status', 12)
                                    // ->whereRaw('loan_repayment_date < loan_overdue_date');
                    })
                    ->select('app_list','phone_message')
                    ->limit(100)
                    ->get();

        foreach ($res as $key => $value) {
            $app_list = json_decode(Mcrypter::decrypt($value->app_list), true);
            $phone_message = json_decode(Mcrypter::decrypt($value->phone_message), true);

            if ($app_list && $phone_message) {
                echo count($app_list);

                $laon_arr = $this->laon_arr($phone_message, $app_list);
                if ($laon_arr) {
                    echo ' ' . count($laon_arr) . '<br>';
                } else {
                    echo ' 0<br>';
                }
            }
        }
    }

    /**
     * [laon_arr 现金贷 APP（2）]
     *
     * @param  [type] $phone_message [description]
     * @param  [type] $app_list      [description]
     * @return [type]                [description]
     */
    public function laon_arr($phone_message, $app_list)
    {

        $arr1 = $arr2 = [];
        $loan_arr =  [
            'loan',
            'pinjam',
            'dana',
            'cash',
            'rupiah',
            'uang',
            'kredi',
            'tunai',
            'telah melewati',
            'telah terlambat',
            'telah perpanjang',
            'lebih jth tempo',
        ];
        foreach ($phone_message as $key => $value) {
            foreach ($loan_arr as $k => $v) {
                if (strpos($value['body'], $v)) {
                    $arr1[] = $value['body'];
                    break;
                }
            }
        }

        foreach ($arr1 as $key => $value) {
            foreach ($app_list as $k => $v) {
                if (strpos($value, $v['appName'])) {
                    $arr2[] = $v['appName'];
                    break;
                }
            }
        }
        return array_unique($arr2);
    }

    /**
     * [bai_content_black 白名单正常还款，通讯录黑名单]
     * @return [type] [description]
     */
    public function bai_content_black ()
    {
        //白名单 - 正常还款
        $order_ids = DB::table('loan_order')
                        ->leftJoin('user_access', 'loan_order.uid', '=', 'user_access.user_id')
                        ->leftJoin('sms_post', 'user_access.phone', '=', 'sms_post.phone')
                        ->whereIn('user_access.platform', [
                            'DompetEmas',
                            'DompetHarimau',
                            'DompetKaya',
                        ])
                        ->where('order_status', 12)
                        ->whereRaw('loan_repayment_date < loan_overdue_date')
                        ->select('loan_order.uid')
                        ->groupBy('loan_order.uid')
                        ->get()
                        ->toArray();

        //黑名单列表
        $result_black = DB::table('blacklist')
                            ->where('type', '!=', 1)
                            ->where('phone', '!=', '')
                            ->select('phone')
                            ->groupBy('phone')
                            ->get()
                            ->toArray();

        $black_arr = $result_black ? array_column($result_black, 'phone') : [];

        echo '<pre><h1>白名单正常还款 -- 通讯录黑名单检测</h1><hr>';
        file_put_contents('/srv/bai.html', '<pre><h1>白名单正常还款 -- 通讯录黑名单检测</h1><hr>' . PHP_EOL, FILE_APPEND | LOCK_EX);
        foreach ($order_ids as $key => $value) {

            $UserDevice = UserDevice::where(['uid' => $value->uid])->orderByDesc('id')->first();

            echo '用户ID：' . $value->uid;
            file_put_contents('/srv/bai.html', '用户ID：' . $value->uid . PHP_EOL, FILE_APPEND | LOCK_EX);

            //检查通讯录在黑名单里的数量
            $mobile_arr = json_decode(Mcrypter::decrypt($UserDevice->phone_contact), true);

            if($mobile_arr) {

                foreach($mobile_arr as &$v) {

                    $mobile_temp = isset($v['mobile']) ? $v['mobile'] : (isset($v['phone']) ? $v['phone'] : '');
                    $v['mobild_new'] = ltrim(preg_replace('/\D/', '', str_replace('+62', '', $mobile_temp)), '0');
                }

                $arrs = array_unique(array_column($mobile_arr, 'mobild_new'));

                echo '<br>通讯录个数：'. count($arrs);
                file_put_contents('/srv/bai.html', '<br>通讯录个数：'. count($arrs) . PHP_EOL, FILE_APPEND | LOCK_EX);

                $in_black_arr = array_intersect($arrs, $black_arr);
                $numbers_black = count($in_black_arr);

                if ($numbers_black >= 3) {

                    echo '<br>命中灰名单数量：'. $numbers_black . '；<br>';
                    file_put_contents('/srv/bai.html', '<br>命中灰名单数量：'. $numbers_black . '；<br>' . PHP_EOL . implode(',', $in_black_arr) . PHP_EOL, FILE_APPEND | LOCK_EX);
                    print_r($in_black_arr);
                } else {

                    echo '<br>通讯录黑名单过关；';
                    file_put_contents('/srv/bai.html', '<br>通讯录黑名单过关；' . PHP_EOL, FILE_APPEND | LOCK_EX);
                }
            }

            echo '<hr>';
            file_put_contents('/srv/bai.html', '<hr>' . PHP_EOL, FILE_APPEND | LOCK_EX);
        }
    }
}
