<?php

/**
 * @description        : 天尽头流浪
 *
 * @Author             : 天尽头流浪
 * @E-mail             : 1270469522@qq.com
 * @Date               : 2019-07-02 10:55:52
 * @Last Modified by   : 天尽头流浪
 */

namespace App\Http\Controllers;

use App\Http\Controllers\RiskController as Risk;

use App\Models\LoanTrialInfoRecord;
use App\Models\LoanOrder;
use App\Models\UserDevice;
use App\Models\OrderUserBasicInfo;
use App\Models\BlackList;

use App\Service\ExcelCustomer;
use App\Service\Mcrypter;
use App\Service\VoiceService;
use App\Service\OdeoService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Cache; #控制器中使用缓存

class TestController extends Controller
{

    /**
     * [ 对外接口，调用下面的方法 ]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function test (Request $request)
    {

        $this->OrderTrack();
    }


    /**
     * [ 检查通讯录中 通讯录数量，app数量，app中三方贷款的数量 ]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function test20181122 (Request $request)
    {
        $order_numbers = [
            '19102110364606919132',
            '19102112140644375870',
            '19102117050010608614',
        ];

        $res = DB::table('loan_order')
                ->join('order_user_basic_info', 'order_user_basic_info.id', '=', 'loan_order.basic_id')
                ->select([
                    'order_number',
                    'name',
                    'ktp_number',
                    'loan_order.uid'
                ])
                ->whereIn('order_number', $order_numbers)
                ->get()
                ->toArray();

        foreach ($res as $key => $value) {

            echo $value->order_number.',';
            echo $value->name.',';
            echo $value->ktp_number.',';

            //通讯录 app
            $user_device   = UserDevice::where(['uid' => $value->uid])->orderByDesc('id')->first();
            $phone_contact = json_decode(Mcrypter::decrypt($user_device->phone_contact), true);
            $app_list      = json_decode(Mcrypter::decrypt($user_device->app_list), true);

            echo ($phone_contact ? count($phone_contact) : 0).',';
            echo ($app_list ? count($app_list) : 0).',';

            $data = $this->curlPost($value->uid, $value->ktp_number, $value->name);

            if (!empty($data['data']['total']) && !empty($data['data']['loan'])) {
                echo count($data['data']['loan']);
            } else {
                echo 0;
            }
            echo '<br>';
        }
    }

    /**
     * [xianjidnai 远程App现金贷相关]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function xianjidnai (Request $request)
    {
        $type   = $request->get('type');
        $status = $request->get('status');
        $num    = $request->get('num');

        if ($type == 11) {
            $res = DB::table('order_user_basic_info')
                        ->whereIn('id', function ($query) {
                            return $query->from('loan_order')
                                        ->leftJoin('order_product_info', 'loan_order.id', '=', 'order_product_info.order_id')
                                        ->select('basic_id')
                                        ->where('order_product_info.user_type', 1)
                                        ->where('order_status', 11);
                        })
                        ->select('name', 'ktp_number', 'uid')
                        ->orderByDesc('id')
                        ->limit(500)
                        ->get();
        } else if ($type == 12) {
            $res = DB::table('order_user_basic_info')
                        ->whereIn('id', function ($query) {
                            return $query->from('loan_order')
                                        ->leftJoin('order_product_info', 'loan_order.id', '=', 'order_product_info.order_id')
                                        ->select('basic_id')
                                        ->where('order_status', 12)
                                        ->where('order_product_info.user_type', 1)
                                        ->whereRaw('loan_repayment_date < loan_overdue_date');
                        })
                        ->select('name', 'ktp_number', 'uid')
                        ->limit(500)
                        ->get();
        } else {
            echo '参数不对';die;
        }

        foreach ($res as $key => $value) {
            $data = $this->curlPost($value->uid, $value->ktp_number, $value->name);

            if ($status == 1) {
                if (!empty($data['data']['total'])) {
                    if (!empty($data['data']['loan'])) {
                        if (count($data['data']['loan']) == $num) {
                            echo count($data['data']['loan']);
                            echo '<hr>';
                            echo '<pre>';
                            print_r(array_column($data['data']['loan'], 'appName'));die;
                        }
                    }
                }
            } else {

                if (!empty($data['data']['total'])) {
                    echo $data['data']['total'];
                    echo ' ';
                    if (!empty($data['data']['loan'])) {
                        echo count($data['data']['loan']);
                    } else {
                        echo 0;
                    }
                    echo '<br>';
                }
            }
            // die;
            // echo '<pre>';
            // print_r($data);
        }
    }



    /**
     * [getParam 现金贷相关参数处理]
     * @param  [type] $type [description]
     * @return [type]       [description]
     */
    public function getParam($type)
    {
        switch ($type) {
            case "app":
                $keywords['loan'][] = 'loan';
                $keywords['loan'][] = 'pinjam';
                $keywords['loan'][] = 'dana';
                $keywords['loan'][] = 'cash';
                $keywords['loan'][] = 'rupiah';
                $keywords['loan'][] = 'uang';
                $keywords['loan'][] = 'kredi';
                $keywords['loan'][] = 'tunai';
                $post_data = [
                    'keywords' => json_encode($keywords)
                ];
                return $post_data;
                break;
            default:
                break;
        }
    }


    /**
     * [test_loan_arr description]
     * app个数
     * 现金贷相关个数
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function test_loan_arr (Request $request)
    {
        $type = $request->get('type');
        // DB::enableQueryLog();
        if ($type == 11) {
            $res = DB::table('user_device')
                        ->whereIn('uid', function ($query) {
                            return $query->from('loan_order')
                                        ->select('uid')
                                        ->where('order_status', 11);
                                        // ->where('order_status', 12)
                                        // ->whereRaw('loan_repayment_date < loan_overdue_date');
                        })
                        ->select('app_list','phone_message','uid')
                        ->orderByDesc('id')
                        // ->groupBy('uid')
                        ->limit(500)
                        ->get();
        } else if ($type == 12) {
            $res = DB::table('user_device')
                        ->whereIn('uid', function ($query) {
                            return $query->from('loan_order')
                                        ->select('uid')
                                        // ->where('order_status', 11);
                                        ->where('order_status', 12)
                                        ->whereRaw('loan_repayment_date < loan_overdue_date');
                        })
                        ->select('app_list','phone_message')
                        ->limit(500)
                        ->get();
        } else {
            echo '参数不对';die;
        }

        // echo '<pre>';
        // print_r(DB::getquerylog());die;

        foreach ($res as $key => $value) {
            $app_list = json_decode(Mcrypter::decrypt($value->app_list), true);
            $phone_message = json_decode(Mcrypter::decrypt($value->phone_message), true);

            if ($app_list && $phone_message) {
                echo count($app_list);
                // print_r($app_list) . '<hr>';
                // echo '短信条数：' . count($phone_message) . '<br>';
                // print_r($phone_message) . '<hr>';

                $laon_arr = $this->laon_arr($phone_message, $app_list);
                if ($laon_arr) {
                    echo ' ' . count($laon_arr) . '<br>';
                    // echo '<br>'.implode(',', array_column($app_list, 'appName'));
                    echo '<br>'.implode(',', $laon_arr).'<hr>';
                    // print_r($laon_arr);
                    // echo '<hr>';
                } else {
                    echo ' 0<hr>';
                }
            }
        }
    }

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
                if (strpos($value, $v['appName']) && $v['appName'] != 'Facebook') {
                    $arr2[] = $v['appName'];
                    break;
                }
            }
        }
        return array_unique($arr2);
    }

    /**
     * [baiTohei description]
     * @return [type] [description]
     */
    public function baiTohei()
    {
        $order_ids = DB::table('risk_control_record')
                        ->where('created_at', '>', date('Y-m-d', time()).' 00:00:00')
                        ->where('refuse_id', 'DF_BLACKLIST_CONTACT')
                        ->pluck('order_id')->toArray();
        echo '<pre>';
        echo '<h1>联系人黑名单</h1><hr>';
        foreach ($order_ids as $key => $order_id) {
            $this->risk_relative($order_id);
            echo '<hr>';
        }

        $order_ids = DB::table('risk_control_record')
                        ->where('created_at', '>', date('Y-m-d', time()).' 00:00:00')
                        ->where('refuse_id', 'DF_CONTACT_IN_BLACK')
                        ->pluck('order_id')->toArray();

        echo '<h1>通讯录黑名单</h1><hr>';
        foreach ($order_ids as $key => $order_id) {
            $this->risk_contact($order_id);
            echo '<hr>';
        }
    }

    /**
     * [risk_relative 联系人黑名单]
     * @param  [type] $order_id [description]
     * @return [type]           [description]
     */
    public function risk_relative($order_id)
    {
        $order = LoanOrder::where('id', $order_id)->first();
        $orderBasicInfo = OrderUserBasicInfo::getOne($order->basic_id);

        $relative_phone_first  = ltrim(preg_replace('/\D/','',str_replace('+62','',$orderBasicInfo->relative_phone_first)),'0');
        $relative_phone_second = ltrim(preg_replace('/\D/','',str_replace('+62','',$orderBasicInfo->relative_phone_second)),'0');

        echo '订单号：'.$order->order_number.'；手机号：'.$orderBasicInfo->phone_number.'；';

        //系统黑名单第一联系人检查
        if($relative_phone_first<>'') {
            $blackPhoneRet = $this->blackCPhoneHandle($relative_phone_first);
            if($blackPhoneRet) {
                echo '第一联系人：'.$relative_phone_first.'在黑名单；';
            } else {
                echo '第二联系人：'.$relative_phone_first.'不在黑名单；';
            }
        }

        //系统黑名第二联系人检查
        if($relative_phone_second<>'') {
            $blackPhoneRet = $this->blackCPhoneHandle($relative_phone_second);
            if($blackPhoneRet) {
                echo '第二联系人：'.$relative_phone_second.'在黑名单；';
            } else {
                echo '第二联系人：'.$relative_phone_second.'不在黑名单；';
            }
        }
    }

    /**
     * [blackCPhoneHandle 判断联系人手机号是否在系统黑名单里 | 系统黑名单第一联系人检查 | 系统黑名第二联系人检查]
     * @param  [type] $ktp_number   [description]
     * @param  [type] $phone_number [description]
     * @return [type]               [description]
     */
    public function blackCPhoneHandle($phone_number)
    {
        // $blackList = BlackList::getOnePhone($phone_number);
        $blackList = BlackList::where('phone', $phone_number)->where('status', 1)->first();

        if (!empty($blackList)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * [risk_contact 通讯录黑名单]
     * @param  [type] $order_id [description]
     * @return [type]           [description]
     */
    public function risk_contact($order_id)
    {
        $order = LoanOrder::where('id', $order_id)->first();
        $orderBasicInfo = OrderUserBasicInfo::getOne($order->basic_id);
        $UserDevice = UserDevice::where(['uid' => $order->uid])->orderByDesc('id')->first();

        echo '订单号：'.$order->order_number.'；手机号：'.$orderBasicInfo->phone_number.'；';

        //检查通讯录在黑名单里的数量
        $blackContactBlackRet = $this->checkPhoneContactBlack($UserDevice->phone_contact);
    }


    /**
     * [checkPhoneContactBlack 检查通讯录有多少在黑名单中]
     * @param  [type] $str [description]
     * @return [type]      [description]
     */
    public function checkPhoneContactBlack($str)
    {
        $arrs = [];
        $mobile_arr = json_decode(Mcrypter::decrypt($str), true);

        if($mobile_arr) {

            foreach($mobile_arr as $v) {
                $v_mobile = isset($v['mobile']) ? $v['mobile'] : (isset($v['phone']) ? $v['phone'] : '');
                $mobile = ltrim(preg_replace('/\D/','',str_replace('+62','',$v_mobile)),'0');
                if(!in_array($mobile,$arrs,true)) {
                    $arrs[]=$mobile;
                }
            }

            $black_arr1=[];
            $result1 = DB::select("SELECT phone FROM `tb_blacklist` WHERE type=1 AND `phone`<>''");

            if($result1) {
                foreach($result1 as $v) {
                    $black_arr1[] =$v->phone;
                }
            }

            $in_black_arr1 = (array_intersect($arrs,$black_arr1));
            $numbers_black1 = count($in_black_arr1);

            $black_arr2=[];
            $result2 = DB::select("SELECT phone FROM `tb_blacklist` WHERE type<>1 AND `phone`<>''");

            if($result2) {
                foreach($result2 as $v) {
                    $black_arr2[] =$v->phone;
                }
            }

            $in_black_arr2=(array_intersect($arrs,$black_arr2));
            $numbers_black2 = count($in_black_arr2);

            if($numbers_black1 >= 1) {
                echo '<br>命中黑名单数量：'. $numbers_black1.'；<br>';
                print_r($in_black_arr1);
            } else if ($numbers_black2 >= 3) {
                echo '<br>命中灰名单数量：'. $numbers_black2.'；<br>';
                print_r($in_black_arr2);
            } else {
                echo '<br>通讯录黑名单过关；';
            }
        }
    }

    /**
     * [bai_content_black 白名单正常还款的通讯录]
     *
     * 白名单正常还款的通讯录
     *
     * @return [type] [description]
     */
    public function bai_content_black ()
    {
        // dd(Cache::get('black_arr'));  #获取缓存

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
                        ->limit(3)
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

        // Cache::put('black_arr', $black_arr, 3600);  #写入缓存（key，value，time）
        // $black_arr = Cache::get('black_arr');
        // echo '<pre>';
        // print_r($black_arr);die;

        echo '<pre>';
        echo '<h1>通讯录黑名单</h1><hr>';
        foreach ($order_ids as $key => $value) {

            $UserDevice = UserDevice::where(['uid' => $value->uid])->orderByDesc('id')->first();

            echo '用户ID：' . $value->uid;

            //检查通讯录在黑名单里的数量
            $mobile_arr = json_decode(Mcrypter::decrypt($UserDevice->phone_contact), true);

            if($mobile_arr) {

                foreach($mobile_arr as &$v) {

                    $mobile_temp = isset($v['mobile']) ? $v['mobile'] : (isset($v['phone']) ? $v['phone'] : '');
                    $v['mobild_new'] = ltrim(preg_replace('/\D/', '', str_replace('+62', '', $mobile_temp)), '0');
                }

                $arrs = array_unique(array_column($mobile_arr, 'mobild_new'));

                echo '<br>通讯录个数：'. count($arrs);

                $in_black_arr = array_intersect($arrs, $black_arr);
                $numbers_black = count($in_black_arr);

                if ($numbers_black >= 3) {

                    echo '<br>命中灰名单数量：'. $numbers_black . '；<br>';
                    print_r($in_black_arr);
                } else {

                    echo '<br>通讯录黑名单过关；';
                }
            }
            echo '<hr>';
        }
    }

    /**
     * [test918 测试解压]
     * @return [type] [description]
     */
    public function phoneMcrypter (){

        $str = "Jiep4XUhW7pja0aphl+gf390MO28NUmO84yf+WKDMAAVwOqifpN5FDur1v0d+Ju7rNQSX49GwU8lAeFs9iwSUKR+WXpcTFM3mxYoIXmnSEfwjAY5JqIs4sBgj9eypSvcfu/YJQSeNA4Dl+kwgpMfEsQp88T8pc+UP3lb25QxBFOuxzlPHTRaQ24Guvw6Kqi9AZ8c1SV5iqsd0xy4yMFx1V0f6hgtTkQLvBjwxqfIO1X+p80wfVZclUHMf/ngesUKzRrLOk9Z6qd3SvqiMt75095qsO6CkOVF0zYdWJYRA7qeU+THofM1jm4JGXQyGdPSYFnjShdiNpUBC0jobmuiYoLL7HFTGYU+dtNJVH3CTb+3oPVxtDxzdKvRbrfkFVcRe7lsbEx1xt3B4h9uorzwp3s0ztFlF7mjizm409we5fzPrlre4e8ByiEzWwuC4V3vN28WBFjSFsQlBwDffTxtTVvizyLfUto+/oKxFfuSTUGoqtGM8YrVzfJ0+Gyaue9FBZ5Gi9HisKoJmlM3WNJ/J3bx+4GUYa3Gj2+wwDDExjVsPyHVg9su2WYGS6gduEJmx6GC3drLjSwReUSJKnd7lVJDY5jhJbf6Znsw/mxDToTz+xURk76X/izmykmQwHAXJHgoYO7B/r/aUON9CuKfIOJKAyAK1rjBRzge6sPZ9+lgeB6EeurhbvbHWfW";

        echo Mcrypter::decrypt($str);
        echo '<hr>';
        $arr = json_decode(Mcrypter::decrypt($str), true);

        echo '<pre>';
        print_r($arr);die;
    }

    /**
     * [OrderTrack 把订单 平均分配给 催收员]
     */
    public function OrderTrack ()
    {
        /*
        $arr1 = [
            'M2019071471O00000067',
            'M2019071411F00000072',
            'M2019071348M00000115',
            'M2019071462Q00000034',
        ];
        */

        $arr1 = DB::select("SELECT order_number FROM `tb_loan_order` as a left join tb_loan_order_track as b on a.id = b.order_id where substr(loan_deadline,1,10) >= '2020-03-01' and substr(loan_deadline,1,10) <= '2020-03-07' and order_status = 11 and b.promise_time = ''");
        $arr1 = array_column($arr1, 'order_number');

        $arr2 = [
            113 => 0,
            115 => 0,
            116 => 0,
        ];

        $arr = [];
        foreach ($arr1 as $key => &$value) {

            foreach ($arr2 as $k => &$v) {

                if ($k == 116) {

                    $arr[$key]['order_number'] = $value;
                    $arr[$key]['user_id'] = $k;

                    $order_id = DB::table('loan_order')->where('order_number', $value)->first()->id;
                    DB::table('loan_order_track')->where('order_id', $order_id)->update(['admin_id'=>$k,'is_remind'=>0]);
                    $arr2 = [
                        113 => 0,
                        115 => 0,
                        116 => 0,
                    ];
                    break;
                }

                if ($v == 0) {

                    $arr[$key]['order_number'] = $value;
                    $arr[$key]['user_id'] = $k;

                    $order_id = DB::table('loan_order')->where('order_number', $value)->first()->id;
                    DB::table('loan_order_track')->where('order_id', $order_id)->update(['admin_id'=>$k,'is_remind'=>0]);
                    $v = 1;
                    break;
                }
            }
        }
                echo '<pre>';
                print_r($arr);
                echo '<hr>';
    }

    /**
     * [test_mobile 导出模型]
     * @return [type] [description]
     */
    public function test_mobile ()
    {
        $title = [
            '手机号',
            '订单号',
            '状态',
        ];
        array_unshift($arr, $title);

        $name = '贷后数据';
        $file_url = $name .'.xls';

        return (new ExcelCustomer($arr))->download($file_url, 'Xls');
    }

    /**
     * [curlPost 远程调用现金贷相关]
     * @param  [type] $uid        [description]
     * @param  [type] $ktp_number [description]
     * @param  [type] $username   [description]
     * @return [type]             [description]
     */
    public function curlPost($uid, $ktp_number, $username)
    {
        $post_data  = $this->getParam('app');
        $url = env('RISK_URL') . '/gather/review/app?' . 'ktp_number=' . $ktp_number . '&username=' . rawurlencode($username);

        $curl       = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        $data = curl_exec($curl);
        curl_close($curl);
        if ($data != false) {
            $data = json_decode($data, true);
            if (!empty($data['result_code']) && $data['result_code'] == 10000 && ($data['data']['total'] != 0 || !empty($data['data']['total']))) {

                return $data;
            } else {
                return '201';
            }
        } else {
            return '202';
        }
    }
}
