<?php


/***
 *
 *  █████░▒██   ██  ▄████▄   ██ ▄█▀       ██████╗ ██╗   ██╗ ██████╗
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



// header("Content-type: application/json; charset=utf-8");
header("Content-type: text/html; charset=utf-8");
// header("Content-type: text/html; charset=gbk");
echo '<pre>';


function quickSort($arr)
{
    $len = count($arr);
    if ($len <= 1) {
        return $arr;
    }
    $base = $arr[0];
    $left = $right = [];
    for ($i = 1; $i < $len; $i++) {
        if ($base > $arr[$i]) {
            $left[] = $arr[$i];
        } else {
            $right[] = $arr[$i];
        }
    }

    $left = quickSort($left);
    $right = quickSort($right);
    return array_merge($left, [$base], $right);
}


function quickSort2($arr)
{
    $len = count($arr);
    if ($len <= 1) {
        return $arr;
    }
    $base = $arr[0];
    $left = $right = [];
    for ($i = 1; $i < $len; $i++) {
        if ($arr[$i] < $base) {
            $left[] = $arr[$i];
        } else {
            $right[] = $arr[$i];
        }
    }

    $left = quickSort2($left);
    $right = quickSort2($right);

    return array_merge($left, [$base], $right);
}
// $arr = [2,4,1,3,7,9,8,6,5];
$arr = [4,5,3,1,2];
print_r(quickSort2($arr));die;



echo str_pad($str,30,".",STR_PAD_LEFT);die;

/**===================    获取公钥   ================**

$data = [
    'mobile'         =>'1111111111',
    'credentialNo'   =>'1111111111',
    'credentialType' =>'KTP',
    'productIds'     =>[1],
];

$data['appId'] = '112787023608';
$data['nonce'] = '112787023608';

$data1 = array_filter($data);

ksort($data1);

echo '<pre>';
print_r($data1);

$str = json_encode($data1).'secret=RGFr5nPmCYjiSTzz';

echo ($str);
echo '<hr>';
$sign = sha1($str);
echo $sign;
echo '<hr>';


$data['sign'] = $sign;

echo json_encode($data);
die;



/**===================    获取公钥   ================**
// $a = file_get_contents('http://149.129.251.98:8099/Library/baijiayoumi/rsa_private_key.pem');
print_r($a);die;


/**===================    Kreditpedia  测试   ================**/


$originKey = 'c1738990a469934118cc1880af47e9dd';
$originIv  = '0af9d0aa4e0e74c0b47874a534a8c2a5';
$key = hex2bin($originKey);
$iv  = hex2bin($originIv);

// 001 拉取借款金额和借款周期接口（合作方）
$test_arr = [
    'mobile'      => '13878888888',
    'user_idcard' => '4201051989XXXXXXXX',
];

// 002 获取借款金额、周期、利息，管理费等信息接口（合作方）
$test_arr = [
    'mobile'             => '13878888888',
    'user_idcard'        => '4201051989XXXXXXXX',
    'application_amount' => 1000000,
    'application_term'   => 7,
    'term_unit'          => 1
];

// 003 可申请用户&附贷简化流程判断接口（合作方）
$test_arr = [
    'mobile'      => '81316542924',
    'user_idcard' => '3502013001860003',
    'user_name'   => 'DANIEL ARISANDY EKA PUTRA',
];

// 004 订单基础信息推送接口（合作方）
$test_arr = [
    "order_info" => [
        "order_no"           => "123456789",
        "application_amount" => 1000000,
        "application_term"   => 7,
        "term_unit"          => 1,
        "order_time"         => 1520072101,
    ],
    "base_info" => [
        "user_name"                 => "张三",
        "user_mobile"               => "13878888888",
        "user_idcard"               => "4201051989XXXXXXXX",
        "face_img_url"              => "https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1583753775433&di=170997ed3f297d8e7894aa9e802cb97a&imgtype=0&src=http%3A%2F%2Fa0.att.hudong.com%2F78%2F52%2F01200000123847134434529793168.jpg",
        "idcard_image_front"        => "https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1583753775433&di=170997ed3f297d8e7894aa9e802cb97a&imgtype=0&src=http%3A%2F%2Fa0.att.hudong.com%2F78%2F52%2F01200000123847134434529793168.jpg",
        "idcard_image_reverse_side" => "https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1583753775433&di=170997ed3f297d8e7894aa9e802cb97a&imgtype=0&src=http%3A%2F%2Fa0.att.hudong.com%2F78%2F52%2F01200000123847134434529793168.jpg",
        "education"                 => 4,
        "month_income"              => 100000,
        "religion"                  => 4,
        "marital_status"            => 2,
        "sex"                       => 1,
        "birth_day"                 => "1992-03-12",
    ],
    "contact_info" => [
        [
            "emergencyRelation" => 3,
            "emergencyName"     => "anny",
            "emergencyPhone"    => "18989365677"
        ],
        [
            "emergencyRelation" => 1,
            "emergencyName"     => "tony",
            "emergencyPhone"    => "18989365678"
        ]
    ]
];

// 005 补充信息推送接口（合作方）
$test_arr = [
    "order_no" => "123456789",
    "device_info" => [
        "device_id"   => "9774d56d682e549c",
        "ip "         => "192.0.0.1",
        "longitude"   => "38.6518 ",
        "latitude"    => "104.07642",
        "mac"         => "B8 => B2 => F8 => A4 => F6 => 5B",
        "imei"        => "354782081457389",
        "is_root"     => 1,
        "is_debug"    => 1,
        "is_gps_fake" => 1
    ],
    "address_info" => [
        "address"          => " ACEH/KOTA BANDA ACEH/BANDA RAYA",
        "address_property" => 1,
        "live_time"        => 2
    ],
    "company_info" => [
        "has_work"           => 1,
        "work_type"          => 3,
        "work_certificate"   => "http => //xxxxxx",
        "company_name"       => "xxxxxx",
        "company_address"    => " ACEH/KOTA BANDA ACEH/BANDA RAYA",
        "company_telephone"  => "12342443423",
        "company_member_num" => 200,
        "working_time"       => 2,
        "email"              => " supercash0620@gmail.com"
    ],
    "face_info" => [
        "liveness_score " => "100",
        "similarity"      => "69.0"
    ],
    "app_list" =>  [
        [
            "app_name"     =>  "Solusindo",
            "package_name" =>  "com.ecreditpal.solusindo",
            "version_name" =>  "2.1.0"
        ],
        [
            "app_name"     =>  "搜狗浏览器",
            "package_name" =>  "sogou.mobile.explorer",
            "version_name" =>  "5.24.6"
        ]
    ],
    "contact_list" => [
        [
            "mobile" => "18989356622",
            "name"   => "lucy"
        ],
        [
            "mobile" => "18989356622",
            "name"   => "lucy"
        ]
    ]
];

// 006 获取支持开户行接口（合作方）
$test_arr = [

];

// 007 绑卡接口（合作方）
$test_arr = [
    "order_no"        => "123456789",
    "bank_account"    => "1234567890123123",
    "open_bank_id"    => 1001,
    "bank_user_name"  => "张三",
    "bank_user_phone" => "13878888888"
];

// 008 获取合同接口（合作方）
$test_arr = [
    "application_amount" => 1000000,
    "application_term"   => 7,
    "term_unit"          => 1,
    "contract_page"      => 1
];

// 011 拉取审批结论接口（合作方）
$test_arr = [
    "order_no" => "123456789"
];

// 012 拉取订单状态接口（合作方）
$test_arr = [
    "order_no" => ["123456789","223456789","323456789"]
];


// 013 还款计划信息接口（合作方）
$test_arr = [
    "order_no"      =>  "123456789",
    "repay_type "   =>  1,
    "repay_bank "   =>  1005,
    "request_type " =>  2,
    "delay_term"    =>  7,
    "delay_unit"    =>  1
];

// 014 还款详情信息接口（合作方）
$test_arr = [
    "order_no"     => "123456789",
    "repay_type"   => 1,
    "repay_bank"   => 2001,
    "repay_store"  => 2001,
    "request_type" => 2,
    "delay_term"   => 7,
    "delay_unit"   => 1
];

$timestamp = 1583905021109;

$str = json_encode($test_arr);

// 加密
$en_data = base64_encode(openssl_encrypt($str, "AES-128-CBC", $key, OPENSSL_RAW_DATA, $iv));
// print_r($en_data);
// echo '<hr>';

// 解密
$de_data = openssl_decrypt(base64_decode($en_data), "AES-128-CBC", $key, OPENSSL_RAW_DATA, $iv);
// print_r($de_data);
// echo '<hr>';


// 签名
ksort($test_arr);

print_r($test_arr);
echo '<hr>';

$sign = md5($originKey . '*|*' . json_encode($test_arr, JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES + JSON_PRESERVE_ZERO_FRACTION) . '@!@' . $timestamp);
echo $sign;
echo '<hr>';

// $url = 'http://39.96.161.37/Kreditpedia/001.api';
// $url = 'http://39.96.161.37/Kreditpedia/002.api';
// $url = 'http://39.96.161.37/Kreditpedia/003.api';
// $url = 'http://39.96.161.37/Kreditpedia/004.api';
// $url = 'http://39.96.161.37/Kreditpedia/005.api';
// $url = 'http://39.96.161.37/Kreditpedia/006.api';
// $url = 'http://39.96.161.37/Kreditpedia/007.api';
// $url = 'http://39.96.161.37/Kreditpedia/008.api';
// $url = 'http://39.96.161.37/Kreditpedia/011.api';
// $url = 'http://39.96.161.37/Kreditpedia/012.api';
// $url = 'http://39.96.161.37/Kreditpedia/013.api';
$url = 'http://39.96.161.37/Kreditpedia/014.api';

$arr = [
    'version'      => 1,
    'partner_name' => 'Kreditpedia',
    'appid'        => 202003119106,
    'timestamp'    => $timestamp,
    'sign'         => $sign,
    'en_data'      => $en_data,
];
print_r($arr);
echo json_encode($arr);
echo '<hr>';


$data = json_encode($arr);

$res = curlPost($url, $data);

if (json_decode($res, true)) {
    $res = json_decode($res, true);
}

echo '<pre>';
print_r($res);die;


/**===================    Access Token   ================**\

$url = 'http://39.96.161.37:8080/odeoRepaymentCallback';//测试

$data = [
    "grant_type"    => "client_credentials",
    "client_id"     => "6FAppdsCj2Or5BBQh6U0i8e6snBbPXg9",
    "client_secret" => "GJHLqs8rpSxeMFTKvHxmwX7ZRXvtsemiE19Qt1mTyA1LTZZGklkWlZvzSIhmoVyb",
    "scope"         => "",
    'notify_type'   => 'va_inquiry',
    'va_code'       => '81880119092610411578621',
];

$res = curlPost($url, json_encode($data));

echo '<pre>';
print_r($res);die;

/**===================    Access Token   ================**\

$url = 'http://api.v2.staging.odeo.co.id/oauth2/token';//测试

$data = [
    "grant_type"    => "client_credentials",
    "client_id"     => "6FAppdsCj2Or5BBQh6U0i8e6snBbPXg9",
    "client_secret" => "GJHLqs8rpSxeMFTKvHxmwX7ZRXvtsemiE19Qt1mTyA1LTZZGklkWlZvzSIhmoVyb",
    "scope"         => ""
];

$token = curlPost($url, json_encode($data), $header);

$token = json_decode($token, true);
// echo '<pre>';
// print_r($token);die;

/**===================    第三步   ================**\

$url = 'http://api.v2.staging.odeo.co.id/dg/v1/disbursements/2107';//test

$signingKey   = "we76V7Gb2lJQs8BvNBr9IKpVRmIC7gL6BpRj93K2xMZIQh1pPJNtDIBNjVPZsBrS";
$accessToken  = $token['access_token'];
$timestamp    = time();
$bodyHash     = base64_encode(hash('sha256', '', true));
$path         = '/dg/v1/disbursements/2107';
$method       = 'GET';
$stringToSign = "$method:$path::$accessToken:$timestamp:$bodyHash";
$signature    = base64_encode(hash_hmac('sha256', $stringToSign, $signingKey, true));

$header = [
    'Authorization:Bearer '.$accessToken,
    'X-Odeo-Timestamp:' . $timestamp,
    'X-Odeo-Signature:' . $signature,
    'Content-Type:application/json',
    'Accept-Language:en',
];

$res = curlGet($url, $header);

if (json_decode($res, true)) {
    $res = json_decode($res, true);
}

echo '<pre>';
echo 'The accessToken is ====>';
print_r($token);
echo 'The header is ====>';
print_r($header);
echo 'The result is ====>';
print_r($res);
echo 'The stringToSign is ====>';
print_r($stringToSign);die;



/**===================    放款第二步   ================**

$url = 'http://api.v2.staging.odeo.co.id/dg/v1/disbursements';//test

$data = [
    "account_number" => "123456789",
    "amount"         => "100000",
    "bank_id"        => 1,
    "customer_name"  => "test",
    "description"    => "testtest",
    "reference_id"   => "111222333"
];

$signingKey   = "we76V7Gb2lJQs8BvNBr9IKpVRmIC7gL6BpRj93K2xMZIQh1pPJNtDIBNjVPZsBrS";
$accessToken  = $token['access_token'];
$timestamp    = time();
$bodyHash     = base64_encode(hash('sha256', json_encode($data), true));
$path         = '/dg/v1/disbursements';
$method       = 'POST';
$stringToSign = "$method:$path::$accessToken:$timestamp:$bodyHash";
$signature    = base64_encode(hash_hmac('sha256', $stringToSign, $signingKey, true));

$header = [
    'Authorization:Bearer '.$accessToken,
    'X-Odeo-Timestamp:' . $timestamp,
    'X-Odeo-Signature:' . $signature,
    'Content-Type:application/json',
    'Accept-Language:en',
];

$res = curlPost($url, json_encode($data), $header);

if (json_decode($res, true)) {
    $res = json_decode($res, true);
}

echo '<pre>';

echo 'The accessToken is ====><br>';
print_r($token);
echo '<hr><br>';

echo 'The header is ====><br>';
print_r($header);
echo '<hr><br>';

echo 'The data is ====><br>';
print_r($data);
echo '<hr><br>';

echo 'The result is ====><br>';
print_r($res);
echo '<hr><br>';

echo 'The stringToSign is ====><br>';
print_r($stringToSign);die;



/**===================    放款第一步  ================**

$url = 'http://api.v2.staging.odeo.co.id/dg/v1/bank-account-inquiry';//test

$data = [
    "account_number"  => "123456789", //用户银行卡号
    "bank_id"         => 1,           //放款银行ID
    "customer_name"   => "test",      //用户姓名
    "with_validation" => true
];

$signingKey   = "we76V7Gb2lJQs8BvNBr9IKpVRmIC7gL6BpRj93K2xMZIQh1pPJNtDIBNjVPZsBrS";
$accessToken  = $token['access_token'];
$timestamp    = time();
$bodyHash     = base64_encode(hash('sha256', json_encode($data), true));
$path         = '/dg/v1/bank-account-inquiry';
$method       = 'POST';
$stringToSign = "$method:$path::$accessToken:$timestamp:$bodyHash";
$signature    = base64_encode(hash_hmac('sha256', $stringToSign, $signingKey, true));

$header = [
    'Authorization:Bearer '.$accessToken,
    'X-Odeo-Timestamp:' . $timestamp,
    'X-Odeo-Signature:' . $signature,
    'Content-Type:application/json',
    'Accept-Language:en',
];

$res = curlPost($url, json_encode($data), $header);

if (json_decode($res, true)) {
    $res = json_decode($res, true);
}

echo '<pre>';

echo 'The accessToken is ====><br>';
print_r($token);
echo '<hr><br>';

echo 'The header is ====><br>';
print_r($header);
echo '<hr><br>';

echo 'The data is ====><br>';
print_r($data);
echo '<hr><br>';

echo 'The result is ====><br>';
print_r($res);
echo '<hr><br>';

echo 'The stringToSign is ====><br>';
print_r($stringToSign);die;


/**===================    /pg/v1/payment/reference-id/{reference_id}   ================**\

$url = 'http://api.v2.staging.odeo.co.id/dg/v1/banks';//test

$signingKey   = "we76V7Gb2lJQs8BvNBr9IKpVRmIC7gL6BpRj93K2xMZIQh1pPJNtDIBNjVPZsBrS";
$accessToken  = $token['access_token'];
$timestamp    = time();
$bodyHash     = base64_encode(hash('sha256', '', true));
$path         = '/dg/v1/banks';
$method       = 'GET';
$stringToSign = "$method:$path::$accessToken:$timestamp:$bodyHash";
$signature    = base64_encode(hash_hmac('sha256', $stringToSign, $signingKey, true));

$header = [
    'Authorization:Bearer '.$accessToken,
    'X-Odeo-Timestamp:' . $timestamp,
    'X-Odeo-Signature:' . $signature,
    'Content-Type:application/json',
    'Accept-Language:en',
];

$res = curlGet($url, $header);

if (json_decode($res, true)) {
    $res = json_decode($res, true);
}

echo '<pre>';
echo 'The accessToken is ====>';
print_r($token);
echo 'The header is ====>';
print_r($header);
echo 'The result is ====>';
print_r($res);
echo 'The stringToSign is ====>';
print_r($stringToSign);die;

/**===================    /pg/v1/payment/reference-id/{reference_id}   ================**\

$url = 'http://api.v2.staging.odeo.co.id/pg/v1/payment/reference-id/1';//test

$signingKey   = "we76V7Gb2lJQs8BvNBr9IKpVRmIC7gL6BpRj93K2xMZIQh1pPJNtDIBNjVPZsBrS";
$accessToken  = $token['access_token'];
$timestamp    = time();
$bodyHash     = base64_encode(hash('sha256', '', true));
$path         = '/pg/v1/payment/reference-id/1';
$method       = 'GET';
$stringToSign = "$method:$path::$accessToken:$timestamp:$bodyHash";
$signature    = base64_encode(hash_hmac('sha256', $stringToSign, $signingKey, true));

$header = [
    'Authorization:Bearer '.$accessToken,
    'X-Odeo-Timestamp:' . $timestamp,
    'X-Odeo-Signature:' . $signature,
    'Content-Type:application/json',
    'Accept-Language:en',
];

$res = curlGet($url, $header);

if (json_decode($res, true)) {
    $res = json_decode($res, true);
}

echo '<pre>';
echo 'The accessToken is ====>';
print_r($token);
echo 'The header is ====>';
print_r($header);
echo 'The result is ====>';
print_r($res);
echo 'The stringToSign is ====>';
print_r($stringToSign);die;


/**===================  风控（二）  ==================**\
$url = 'http://59.110.46.55:8094/Partner/FengKong/getRiskScoreTest';//测试
// $url = 'http://api.risk.zhangfujr.com/Partner/FengKong/getRiskScore';//生产
// $url = 'http://api.saasrisk.zhangfujr.com/Partner/FengKong/getRiskScore';//saas

$partner_id  = '100861';
$secret_key  = '100861';
$id_num      = '410711198901031534';
$mobile      = '13729911290';
$zw_order    = 'SYS15350072414379';
$timestamp   = time();
$sign = md5('id_num='.$id_num.'&mobile='.$mobile.'&partner_id='.$partner_id.'&timestamp='.$timestamp.'&zw_order='.$zw_order.$secret_key);


$data = [
    'id_num'      => $id_num,
    'mobile'      => $mobile,
    'zw_order'    => $zw_order,
    'partner_id'  => $partner_id,
    'timestamp'   => $timestamp,
    'sign'        => $sign,
];
// echo '<pre>';
// print_r($data);die;
/**===================  测试完成  ==================**/



function curlPost($url = '', $data = '', $header = '')
{
    if (!$header) {
        $header = [
            'content-type:application/json',
        ];
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

    $res = curl_exec ($ch);

    if (curl_errno ($ch)) {
        $res = curl_error($ch);
    }
    curl_close ($ch);

    return $res;
}


function curlGet ($url, $header)
{
    //初始化
    $ch = curl_init();
    //设置抓取的url
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //执行命令
    $data = curl_exec($ch);

    if (curl_errno ($ch)) {
        $data = curl_error($ch);
    }
    //关闭URL请求
    curl_close($ch);
    //显示获得的数据
    return $data;
}





/**
 * 过滤字符串
 */
function filter_array(&$arr, $values = ['', null, false, []])
{
    if (!is_array($arr)) {
        return [];
    }

    foreach ($arr as $k => $v) {
        if (is_array($v) && count($v) > 0) {
            $arr[$k] = filter_array($v, $values);
        }

        if (in_array($v, $values, true)) {
            unset($arr[$k]);
        }
    }
    return $arr;
}























