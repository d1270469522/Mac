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

    public function test (Request $request)
    {
        $this->phoneMcrypter();
    }


    public function test20181122 (Request $request)
    {
        $order_numbers = [
            '19102110364606919132',
            '19102112140644375870',
            '19102117050010608614',
            '19102117245718799752',
            '19102200031346624372',
            '19102205155950766208',
            '19102206580215148403',
            '19102207550552486932',
            '19102209053813239780',
            '19102209231198864025',
            '19102209272100118687',
            '19102209533247137282',
            '19102211512362739280',
            '19102219411268037655',
            '19102221483861648235',
            '19102306480891622827',
            '19102308545792664408',
            '19102310511140736581',
            '19102316035504908863',
            '19102420251062628850',
            'M2019102595T00000065',
            '19102522034875217322',
            '19102109331413056596',
            '19102111293438739763',
            '19102114200288571031',
            '19102115282412706285',
            '19102201344947673611',
            '19102205244384275458',
            '19102206593192339846',
            '19102207462622437256',
            '19102207502051327545',
            '19102208205837316866',
            '19102208352240487387',
            '19102208475477993370',
            '19102209094243951657',
            '19102210485977927827',
            '19102211143177638500',
            '19102212071918467572',
            '19102213204657995285',
            '19102213464196071384',
            '19102215085717529629',
            '19102215414280707580',
            '19102216450432228979',
            '19102219405742399929',
            '19102220040645976195',
            '19102304301019625807',
            '19102307023669531043',
            '19102307451683883647',
            '19102310511332261475',
            '19102312175519353012',
            '19102313045586928052',
            '19102317453670298554',
            '19102317540066285771',
            '19102320035592945732',
            '19102320200010976297',
            '19102400432516386330',
            '19102410235395666306',
            '19102411375058701993',
            '19102415022993036713',
            '19102415092641211852',
            'M2019102549E00000042',
            '19102515464302221640',
            '19102516031760507321',
            '19102516035693247934',
            '19102114240514631334',
            '19102112232647637234',
            '19102112475067287906',
            '19102112520395305416',
            '19102113554546984573',
            '19102110192086334347',
            '19102110135872051319',
            'M2019102148P00000053',
            '19102113141109193554',
            'M2019102160I00000098',
            'M2019102191K00000119',
            '19102116135623368714',
            '19102118021593308618',
            '19102203402986375793',
            '19102208263835793271',
            '19102209003734334063',
            'M2019102244T00000017',
            'M2019102275T00000020',
            'M2019102223J00000033',
            '19102212411943055707',
            '19102213205108439953',
            'M2019102232Z00000062',
            '19102213413459642358',
            '19102214343594448765',
            '19102216293866744869',
            '19102218490927176578',
            '19102220563019084374',
            '19102300051760311299',
            'M2019102371D00000021',
            '19102308345202976471',
            '19102308435895435875',
            'M2019102326X00000057',
            '19102312570179845217',
            'M2019102382E00000068',
            'M2019102323G00000074',
            'M2019102386H00000077',
            '19102317451054981999',
            '19102317515971995108',
            '19102317535190993001',
            '19102318022060496835',
            'M2019102312R00000114',
            '19102319495696843729',
            '19102319592092926519',
            '19102406105078852988',
            '19102407303926005994',
            '19102407534291312251',
            '19102408340752682943',
            '19102410133202872150',
            '19102410380990253672',
            '19102413182145214971',
            '19102413513340681401',
            'M2019102498B00000084',
            '19102418450470862378',
            '19102420273182182114',
            '19102420324236472228',
            '19102420555858357699',
            '19102421052784516615',
            'M2019102540H00000035',
            'M2019102584Q00000062',
            '19102515461189425199',
            '19102515570888741660',
            '19102516372651416866',
            '19102517275002601186',
            'M2019102548E00000080',
            '19102522095432302300',
            'M2019102613O00000006',
            'M2019102543D00000034',
            '19102113142054929801',
            '19102117541219883576',
            'M2019102374T00000027',
            '19102114014459871815',
            '19102309054673044871',
            '19102319140376196573',
            'M2019102556I00000090',
            '19102314535490482021',
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

            //检查通讯录在黑名单里的数量
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
     * [curlPost 现金贷相关]
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

        $str = "6/UvjiqS5+nlbw9UdETuvFLOvPkFvmP/kt1FBV5yYvoig1RlTDSm4jP8x/ZvbkXc8nsHCi/yw+kgDX4g/o2We1jnzbOKRaO0+gRsE+66moAvplwBrfWzE72hsSyrAQR0xHXv+IM7hUFK7Gt6oKt8iol7/QsRbmTV/XiLyUn0Rpl1dAcMD9PA6xNagwY0Ve8JeCr2KdqWxNWmeOEko2EMiUmhAKRvYjjBP9+g6lT+mypT6eVae1VTBQwKSsadneIkWI7LKKR/hv7n4S0jyFkD999eIHfzndDaYuUznYSAeAgc8YhW9bl2aWVRupyYmA/g4ty8sWV93SkdJqPz1way47Y9M+f2GzsdRHa6amTdOtaSRKWOolsaUex51WPxcrwp14ltQwnmFXV+voVKTbq/yfX5v4R86We4xgxzm6EuDgG1R+Dp6fQVp72tdX6Um6aNZuiAvoI0AFDOiSCR4TzRJMtsE2DlCHXqBqR0uWGwQtJe/TDgvqfv0rWyxYLAqTsDetZ0mjxovgD/miDZkzw4oZ4S1/hFofEo5Axg+FjqSMjyuRWgbx/9WN1LxqCdMdGwLPVglwEJ3melHDlcuS/89jrDqxdWln7rixqvU7cWIjPjBAN/k+IVe2Fewfr+lkpUEb9R9lNxj1tHcM2cO1SsAIhBWh4Dt+t+5HESYe2vdrD9eJOLqDuAWicGBK2s1PS9n+DWobiCS9nPZYaMH/Br/GWrkUAw6wez4flNF7Uha/3jztQrwNszuGN93U4itUZnzgWzPPF2oGL+L8IsfL3DWnrpWRb4WwyUncjvk/v9QD45GrEffBvLd8qexbqJnaV6UDeSR6QdgCfmLwwNa3lrRNvJQMAONsObbu9Pps65cdA6ZW30cnm4mx14YNbreZT6NbyLffB4mVn6MirkcK3syCbuD2+a/yr0RZoqzs/pYw6CCfty5MakUjkG9OPMuAAY+cb3fa8fqaC00vVNnNO9xQwwkow4R7ELgM2JocZ8zEA1erOiiNUHPA54clyrbAZn+q5nQ9rYQi8ci/TMiuASfl6HQRRnro1xNDCdohL3/YpaAwGnmOjOVvigVNBkCSMEgHBoeqjBx2oU+bhtJGcZxw5zS513VBFhv0eGzIKz8sCpTt+8rTnNabUX9MB1oPth+r/RqY7lEGnmN2qsp7yuyu4IwkvoHu4ancuRGFtVtSxhwNyez/Gup/pZwhamqyw1oRfjfHDDXMMyJiXp5dXXXHNDGuIg6R+hJaBIAauD4GNvnMHY+Gs4UXI5U6FHx+8KNPPoE+lcB7u9fZJc6GnZxFqU3WBwzRIRid75VpOVasTFRg8HL0+R9pkX+oZIxXgBWfnW93DXhWDaTjlInmJNXSz569tJ3pU2AaT9BtyMOOrpxA2r6EmE/9WcIi5nEVZSKYYACjmQqdXI7PG8WO04BmsRyZan1dJe3JKmev/aWPYDw+HLOdWCWeb5tKQyTpDQ6BxExs7vVmVF9lvrMWs8H8sH1y1kWTPazBQzPBmSLsLulNtLJ8tlBesSjwFCkvYgh2+r4r5pSU5W7VY/y3swU+Jlje98lhs3L2secDyZ/K9u3tkf09I39w==";

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
        $arr1 = [
            'M2019071471O00000067',
            'M2019071411F00000072',
            'M2019071348M00000115',
            'M2019071462Q00000034',
        ];

        $arr2 = [
            80 => 0,
            81 => 0,
            82 => 0,
        ];

        $arr = [];
        foreach ($arr1 as $key => &$value) {
            foreach ($arr2 as $k => &$v) {
                if ($k == 82) {
                    // $arr[$key]['order_number'] = $value;
                    // $arr[$key]['user_id'] = $k;
                    $order_id = DB::table('loan_order')->where('order_number', $value)->first()->id;
                    DB::table('loan_order_track')->where('order_id', $order_id)->update(['admin_id'=>$k,'is_remind'=>0]);
                    $arr2 = [
                        80 => 0,
                        81 => 0,
                        82 => 0,
                    ];

                    break;
                }
                if ($v == 0) {
                    // $arr[$key]['order_number'] = $value;
                    // $arr[$key]['user_id'] = $k;
                    $order_id = DB::table('loan_order')->where('order_number', $value)->first()->id;
                    DB::table('loan_order_track')->where('order_id', $order_id)->update(['admin_id'=>$k,'is_remind'=>0]);
                    $v = 1;
                    break;
                }
            }
        }
        // echo '<pre>';
        // print_r($arr);die;
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
     * [dateDiff 两个日期的时间差]
     * @param  string $time1 [description]
     * @param  string $time2 [description]
     * @return [type]        [description]
     */
    public function dateDiff ($time1 = '', $time2 = '')
    {

        $date1 = date_create($time1);
        $date2 = date_create($time2);

        return $diff = date_diff($date1,$date2);
    }
}
