<?php


// header("Content-type: text/html; charset=utf-8");
// header("Content-type: text/html; charset=gbk");

/***
 *
 *  █████░▒█░   ██  ▄████▄   ██ ▄█▀       ██████╗ ██╗   ██╗ ██████╗
 * ▓██   ▒ ██  ▓██▒▒██▀ ▀█   ██▄█▒        ██╔══██╗██║   ██║██╔════╝
 * ▒████ ░▓██  ▒██░▒▓█    ▄ ▓███▄░        ██████╔╝██║   ██║██║  ███╗
 * ░▓█▒  ░▓▓█  ░██░▒▓▓▄ ▄██▒▓██ █▄        ██╔══██╗██║   ██║██║   ██║
 * ░▒█░   ▒▒█████▓ ▒ ▓███▀ ░▒██▒ █▄       ██████╔╝╚██████╔╝╚██████╔╝
 *  ▒ ░   ░▒▓▒ ▒ ▒ ░ ░▒ ▒  ░▒ ▒▒ ▓▒       ╚═════╝  ╚═════╝  ╚═════╝
 *  ░     ░░▒░ ░ ░   ░  ▒   ░ ░▒ ▒░
 *  ░ ░    ░░░ ░ ░ ░        ░ ░░ ░
 *           ░     ░ ░      ░  ░
 */





/***
 * ┌───┐   ┌───┬───┬───┬───┐ ┌───┬───┬───┬───┐ ┌───┬───┬───┬───┐ ┌───┬───┬───┐ ┌───┬───┬───┬───┐
 * │Esc│   │ F1│ F2│ F3│ F4│ │ F5│ F6│ F7│ F8│ │ F9│F10│F11│F12│ │P/S│S L│P/B│ │ F │ U │ C │ K │
 * └───┘   └───┴───┴───┴───┘ └───┴───┴───┴───┘ └───┴───┴───┴───┘ └───┴───┴───┘ └───┴───┴───┴───┘
 * ┌───┬───┬───┬───┬───┬───┬───┬───┬───┬───┬───┬───┬───┬───────┐ ┌───┬───┬───┐ ┌───┬───┬───┬───┐
 * │~ `│! 1│@ 2│# 3│$ 4│% 5│^ 6│& 7│* 8│( 9│) 0│_ -│+ =│ BacSp │ │Ins│Hom│PUp│ │N L│ / │ * │ - │
 * ├───┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─────┤ ├───┼───┼───┤ ├───┼───┼───┼───┤
 * │ Tab │ Q │ W │ E │ R │ T │ Y │ U │ I │ O │ P │{ [│} ]│ | \ │ │Del│End│PDn│ │ 7 │ 8 │ 9 │   │
 * ├─────┴┬──┴┬──┴┬──┴┬──┴┬──┴┬──┴┬──┴┬──┴┬──┴┬──┴┬──┴┬──┴─────┤ └───┴───┴───┘ ├───┼───┼───┤ + │
 * │ Caps │ A │ S │ D │ F │ G │ H │ J │ K │ L │: ;│" '│ Enter  │               │ 4 │ 5 │ 6 │   │
 * ├──────┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴────────┤     ┌───┐     ├───┼───┼───┼───┤
 * │ Shift  │ Z │ X │ C │ V │ B │ N │ M │< ,│> .│? /│  Shift   │     │ ↑ │     │ 1 │ 2 │ 3 │   │
 * ├─────┬──┴─┬─┴──┬┴───┴───┴───┴───┴───┴──┬┴───┼───┴┬────┬────┤ ┌───┼───┼───┐ ├───┴───┼───┤ E││
 * │ Ctrl│    │Alt │         Space         │ Alt│    │    │Ctrl│ │ ← │ ↓ │ → │ │   0   │ . │←─┘│
 * └─────┴────┴────┴───────────────────────┴────┴────┴────┴────┘ └───┴───┴───┘ └───────┴───┴───┘
 */

/***
 *                    .::::.
 *                  .::::::::.
 *                 :::::::::::  FUCK YOU
 *             ..:::::::::::'
 *           '::::::::::::'
 *             .::::::::::
 *        '::::::::::::::..
 *             ..::::::::::::.
 *           ``::::::::::::::::
 *            ::::``:::::::::'        .:::.
 *           ::::'   ':::::'       .::::::::.
 *         .::::'      ::::     .:::::::'::::.
 *        .:::'       :::::  .:::::::::' ':::::.
 *       .::'        :::::.:::::::::'      ':::::.
 *      .::'         ::::::::::::::'         ``::::.
 *  ...:::           ::::::::::::'              ``::.
 * ```` ':.          ':::::::::'                  ::::..
 *                    '.:::::'                    ':'````..
 */







/**===================  印尼风控  ==================**\
$date1 = date_create('Dec 31, 1993');
$date2 = date_create(date('Y-m-d'));
$diff  = date_diff($date1,$date2);

echo '<pre>';
print_r($diff);die;


// echo md5('1701171101026'.'zhangfujr');die;




/**===================  印尼风控  ==================**\

$name        = 'WIRDANANI';
$idNumber    = '1371105402630002';
$phoneNumber = '+6282343444146';

$url = 'https://api.advance.ai/openapi/score/v1/credit';



$data = [
    'name'        => $name,
    'idNumber'    => $idNumber,
    'phoneNumber' => $phoneNumber,
];






/**===================  印尼风控  ==================**\

$url = 'http://sat-risk.pinjamango.com:8181/businessRca/getDesicion';
$arr = [

    'commonMsg' => [
        'ynUserId'   => '1112223332244',
        'ynOrderId'  => '900006250001',
        'ynUserType' => '0'
    ],

    'applyInfo' => [
        'ynApplyTime'   => '2019-06-24 14:28:37',
        'ynApplyPeriod' => '7',
        'ynApplyAmount' => '100'
    ],

    'userBaseInfo' => [
        'ynBasicCardId'       => '3100000000000001',
        'ynBasicPhone'        => '080000625001',
        'ynBasicDeviceSystem' => '2',
        'ynBasicName'         => 'test001',
        'ynBasicGender'       => 'FEMALE',
        // 'ynBasicMarried'      => 'aaa',
        // 'ynBasicSalary'       => 'a.1',
        'ynBasicAge'          => '45',
        'ynBasicDeviceId'     => '53ss4dd53sdfsf45',
        'ynBasicOccupation'   => 'abc',
        'ynBasicPayday'       => '12',
        'ynBasicLifeProvince' => 'aa',
        'ynBasicLifeCity'     => 'bb',
        'ynBasicJobProvince'  => 'vvv',
        'ynBasicJobCity'      => 'hhh'
    ],

    'contactInfo' => [
        'ynBasicContactFirstPhone'         => '081000625001',
        'ynBasicContactFirstRelationship'  => 'friend',
        'ynBasicContactSecondPhone'        => '081000625002',
        'ynBasicContactSecondRelationship' => 'father',
        'ynBasicContactThirdPhone'         => '081000625003',
        'ynBasicContactThirdRelationship'  => 'mother',
        'ynBasicContactFourthPhone'        => '081000625004',
        'ynBasicContactFourthRelationship' => 'friend'
    ],

    'reformInfo' => [
        'ynBasicRegisterTime'     => '2019-06-20 15:01:12',
        'ynBasicLoanTime'         => '2019-06-22 15:01:12',
        'ynBasicAuthCnt'          => '1',
        // 'ynCreditVerifyStatus'    => '2',
        'ynCreditVerifyName'      => 'aa',
        'ynIsUploadSalaryList'    => '1',
        'ynCallbookCnt'           => '500',
        'ynCallbookContactCnt'    => '12',
        'ynBasicFstopenAppTime'   => '2019-06-20 16:52:21',
        'ynBasicFstloanClickTime' => '2019-06-20 15:19:56',
        'ynBasicLoanClickCnt'     => '3',
        'ynBasicContactChangeCnt' => '5',
        'ynBasicAuthClickCnt'     => '1'
    ],

    'sourceInfo' => [
        'ynAiCreditScore' => '615',
        'ynIziPhoneInfo'  => '+6280000625001,+6285817574962',
        'ynIziCardInfo'   => '+6281380705198,+6280000625001',
        'ynIziPhoneAge'   => '6'
    ],

    'addressListDatas' => [
        [
            'name'  => 'test1',
            'phone' => '+63800000625001'
        ],
        [
            'name'  => 'test2',
            'phone' => '+63800000625002'
        ],
        [
            'name'  => 'test3',
            'phone' => '+63800000625003'
        ],
        [
            'name'  => 'test4',
            'phone' => '+63800000625004'
        ],
        [
            'name'  => 'test5',
            'phone' => '+63800000625005'
        ]
    ]
];




// $arr = [
//     "commonMsg" => [
//         "ynUserId"   => "062501",
//         "ynOrderId"  => "900006250001",
//         "ynUserType" => "2"

//     ],
//     "applyInfo" => [
//         "ynApplyTime"   => "2019-06-24 14:28:37",
//         "ynApplyPeriod" => "7",
//         "ynApplyAmount" => "100"
//     ],
//     "userBaseInfo" => [
//         "ynBasicCardId"       => "3100000000000001",
//         "ynBasicPhone"        => "080000625001",
//         "ynBasicDeviceSystem" => "Andriod 8.1.0",
//         "ynBasicName"         => "test001",
//         "ynBasicGender"       => "FEMALE",
//         "ynBasicMarried"      => "aaa",
//         "ynBasicSalary"       => "a.1",
//         "ynBasicAge"          => "45",
//         "ynBasicDeviceId"     => "53ss4dd53sdfsf45",
//         "ynBasicOccupation"   => "abc",
//         "ynBasicPayday"       => "12",
//         "ynBasicLifeProvince" => "aa",
//         "ynBasicLifeCity"     => "bb",
//         "ynBasicJobProvince"  => "vvv",
//         "ynBasicJobCity"      => "hhh"

//     ],
//     "contactInfo" => [
//         "ynBasicContactFirstPhone"         => "081000625001",
//         "ynBasicContactFirstRelationship"  => "friend",
//         "ynBasicContactSecondPhone"        => "081000625002",
//         "ynBasicContactSecondRelationship" => "father",
//         "ynBasicContactThirdPhone"         => "081000625003",
//         "ynBasicContactThirdRelationship"  => "mother",
//         "ynBasicContactFourthPhone"        => "081000625004",
//         "ynBasicContactFourthRelationship" => "friend"
//     ],
//     "oldLoanInfo" => [
//         "ynLstLateDays"       => "22",
//         "ynTotLoanCnt"        => "2",
//         "ynTotApplyCnt"       => "33",
//         "ynFirstOrderSuccess" => "1",
//         "ynLstOrderSuccess"   => "2",
//         "ynDiffLstOrder"      => "6"
//     ]
// ];


$data = json_encode($arr);



/**===================  入网时长  ==================**\

$url = 'http://39.96.161.37:8080/final/get_phoneAgeV3';//测试

$uid       = '1';

$data = [
    'uid'        => $uid,
];

/**===================  测试API同盾分  ==================**\

$url = 'http://39.96.161.37/tongdun';//测试

$uid       = '1';
$full_name = 'Muhtadin';
$id_num    = '3171010601795656';
$act_mbl   = '8525555555';

$data = [
    'uid'        => $uid,
    'full_name'  => $full_name,
    'id_num'     => $id_num,
    'act_mbl'    => $act_mbl,
];

// $url = 'http://39.96.161.37/getScore';//测试

// $tongdun_id  = 16;
// $id          = 'WF2019062017524816876624';
// $invoke_type = 'GO_ON';

// $data = [
//     'tongdun_id'  => $tongdun_id,
//     'id'          => $id,
//     'invoke_type' => $invoke_type,
// ];


/**===================  测试压缩  ==================**\

$str = file_get_contents('./压缩前.txt');


// echo '<pre>';
// print_r($str);
// print_r(json_decode($str,true));die;

// // 进行压缩
$bb = gzencode($str);
$aa = base64_encode($bb);
// print_r($aa);die;

// 进行解压
echo '<pre>';
$aa = base64_decode($aa);
$bb = gzdecode($aa);
print_r(json_decode($bb,true));die;

/**===================  测试回调数据  ==================**\*/

//回调地址
$mobile   = '13979766007';
$zw_order = '250473110788fk20190604132756600738100082568_1562150032362_fksc';
$url = 'http://api.xiaoxiangjinka.com/new/bjfk/info?phone='.$mobile.'&orderId='.$zw_order;
echo $url;die;

//配置项
$opts = [
    'http'=> [
        'method'=>"GET",
        'timeout'=>10,
    ]
];

//获取回调数据
$context = stream_context_create($opts);
$str     = file_get_contents($url, false, $context);


// 如果压缩，需要解压
// $str = gzdecode(base64_decode($str));

//打印数据
echo '<pre>';
echo 'json数据是：<br>';
print_r($str);

$arr = json_decode($str, true);
if ($arr == false) {
    $utf = mb_convert_encoding($str, 'utf-8', 'gbk');
    $arr = json_decode($utf, true);
}
echo '<hr>';
echo '<pre>';
echo 'array数据是：<br>';
print_r($arr);
die;


/**===================  风控历史分数  ==================**\

// $url = 'http://59.110.46.55:8094/Partner/FengKong/getHistoryScores';//测试
$url = 'http://api.risk.zhangfujr.com/Partner/FengKong/getHistoryScores';//生产

$partner_id = '1701171101008';
$secret_key = '8997a46ed7cf8bba44cdc3af3ba0c12c';
$zw_order   = 'ZL19040900000010252';

$sign = md5('partner_id='.$partner_id.'&zw_order='.$zw_order.$secret_key);

$data = [
    'partner_id' => $partner_id,
    'zw_order'   => $zw_order,
    'sign'       => $sign,
];

$data = json_encode($data);


/**===================  调用黑名单  ==================**\
$url = 'http://api.risk.zhangfujr.com/Partner/BlackList/Verifyblack';

$partner_id = '1701171101000';
$secret_key = 'd0a92ef3bf626308189e7c47af5bd757';
$id_num     = '110108198211136812';
$name       = '耿彤';
$mobile     = '13230875233';

$sign = md5('id_num='.$id_num.'&mobile='.$mobile.'&name='.$name.'&partner_id='.$partner_id.$secret_key);

$data = [
    'id_num'     => $id_num,
    'name'       => $name,
    'mobile'     => $mobile,
    'partner_id' => $partner_id,
    'sign'       => $sign,
];


/**===================  风控（二）  ==================**\
$url = 'http://59.110.46.55:8094/Partner/FengKong/getRiskScore';//测试
// $url = 'http://api.risk.zhangfujr.com/Partner/FengKong/getRiskScore';//生产
// $url = 'http://api.saasrisk.zhangfujr.com/Partner/FengKong/getRiskScore';//saas

// $partner_id  = '100861';
// $secret_key  = '100861';
// $id_num      = '410711198901031534';
// $mobile      = '13729911290';
// $zw_order    = 'SYS15350072414379';
// $timestamp   = time();
// $sign = md5('id_num='.$id_num.'&mobile='.$mobile.'&partner_id='.$partner_id.'&timestamp='.$timestamp.'&zw_order='.$zw_order.$secret_key);


$mobile      = '13732936702';
$id_num      = '360124198601115415';
$zw_order    = '2019061111570003';
$partner_id  = '1701171101012';
$secret_key  = '100861122';
// $is_compress = 1;
$timestamp  = time();

$sign = md5('id_num='.$id_num.'&mobile='.$mobile.'&partner_id='.$partner_id.'&timestamp='.$timestamp.'&zw_order='.$zw_order.$secret_key);
// $sign = md5('id_num='.$id_num.'&is_compress='.$is_compress.'&mobile='.$mobile.'&partner_id='.$partner_id.'&timestamp='.$timestamp.'&zw_order='.$zw_order.$secret_key);

$data = [
    'id_num'      => $id_num,
    'mobile'      => $mobile,
    'zw_order'    => $zw_order,
    'partner_id'  => $partner_id,
    'timestamp'   => $timestamp,
    // 'is_compress' => $is_compress,
    'sign'        => $sign,
];
// \**===================  结尾--结尾  ==================*/




$header = [
    'content-type:application/json;charset=UTF-8',
    // 'slp-content:gzip',
    // 'X-RISK-TOKEN:EXTER9F72CC11B2'
    'X-CSRF-TOKEN:5hM2NNDqMPMsZKnK6hfPZTlgslBsjosJe085zVCa'

];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

curl_setopt($ch, CURLOPT_TIMEOUT, 300);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

$res = curl_exec ($ch);
if (curl_errno ($ch)) {
    $res = curl_error($ch);
}
curl_close ($ch);

//显示获得的数据
if (json_decode($res, true)) {
    $res = json_decode($res, true);
}
echo '<pre>';
print_r($res);die;



/**
 * [genSign 签名]
 * @param  array  $param [description]
 * @return [type]        [description]
 */
function genSign (array $param)
{
    //0. 删除原数据中自带的sign值,防止干扰计算结果
    unset($param['sign']);
    //1. 按key由a到z排序
    ksort($param);
    foreach ($param as $key => $value) {
        if (is_array($value)) {
            $param[$key] = genSign($value);
        }
    }
    //2. 生成以&符链接的key=value形式的字符串
    $paramString = urldecode(http_build_query($param));
    //3. 拼接我们的服务秘钥，并md5加密
    $sign = md5($paramString . 'secret@9maibei.com');

    return $sign;
}




function gzdecode2 ($data) {
    $flags = ord(substr($data, 3, 1));
    $headerlen = 10;
    $extralen = 0;
    $filenamelen = 0;
    if ($flags & 4) {
        $extralen = unpack('v' ,substr($data, 10, 2));
        $extralen = $extralen[1];
        $headerlen += 2 + $extralen;
    }
    if ($flags & 8) // Filename
        $headerlen = strpos($data, chr(0), $headerlen) + 1;
    if ($flags & 16) // Comment
        $headerlen = strpos($data, chr(0), $headerlen) + 1;
    if ($flags & 2) // CRC at end of file
        $headerlen += 2;
    $unpacked = @gzinflate(substr($data, $headerlen));
    if ($unpacked === FALSE)
          $unpacked = $data;
    return $unpacked;
 }













































