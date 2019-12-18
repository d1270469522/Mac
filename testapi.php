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

// header("Content-type: text/html; charset=utf-8");
// header("Content-type: text/html; charset=gbk");



$data = [
    'id'     => 1,
    'status' => 'ACTIVE',
];

$data['appId'] = 'appId1';
$data['nonce'] = 'd6tvz4UFIcuxdVhqKVUDj6enIHQ9WgBU';

ksort($data);

$str = json_encode($data).'secret=abc';

echo ($str);
echo '<hr>';
echo sha1($str);die;


// $a = file_get_contents('http://149.129.251.98:8099/Library/baijiayoumi/rsa_private_key.pem');
print_r($a);die;


/**===================    Kreditpedia  参数生成   ================**/

$test_arr_001 = [
    'mobile'      => '13878888888',
    'user_idcard' => '4201051989XXXXXXXX',
];

$test_arr_002 = [
    'mobile'             => '13878888888',
    'user_idcard'        => '4201051989XXXXXXXX',
    'application_amount' => 800000,
    'application_term'   => 7,
    'term_unit'          => 1,
];

$test_arr_003 = [
    'mobile'      => '13878888888',
    'user_idcard' => '4201051989XXXXXXXX',
    'user_name'   => 'nihao',
];

$test_arr_004 = [
    'order_info' => [
        'order_no'           => '123456789',
        'application_amount' => '1000000',
        'application_term'   => 7,
        'term_unit'          => 1,
        'order_time'         => 1520072101,
    ],
    'base_info' => [
        'user_name'                 => '张三',
        'user_mobile'               => '13878888888',
        'user_idcard'               => '421015192202022999',
        'face_img_url'              => 'https://dss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=4022157416,1072029411&fm=26&gp=0.jpg',
        'idcard_image_front'        => 'https://dss1.bdstatic.com/70cFuXSh_Q1YnxGkpoWK1HF6hhy/it/u=3499311072,2789073099&fm=26&gp=0.jpg',
        'idcard_image_reverse_side' => 'https://dss1.bdstatic.com/70cFuXSh_Q1YnxGkpoWK1HF6hhy/it/u=1467242771,949293240&fm=26&gp=0.jpg',
        'education'                 => 4,
        'month_income'              => 1000000,
        'religion'                  => 4,
        'marital_status'            => 2,
        'sex'                       => 1,
        'birth_day'                 => '1992-03-12',
    ],
    'contact_info' => [
        [
            'emergencyRelation' => 3,
            'emergencyName'     => 'anny',
            'emergencyPhone'    => '18989365677',
        ],
        [
            'emergencyRelation' => 1,
            'emergencyName'     => 'tony',
            'emergencyPhone'    => '18989365678',
        ],
    ],
];

$test_arr_005 = [
    "order_no" => "123456789",

    "device_info" => [
        "device_id"   => "9774d56d682e549c",
        "ip"          => "192.0.0.1",
        "longitude"   => "38.6518 ",
        "latitude"    => "104.07642",
        "mac"         => "B8:B2:F8:A4:F6:5B",
        "imei"        => "354782081457389",
        "is_root"     => 1,
        "is_debug"    => 1,
        "is_gps_fake" => 1
    ],

    "address_info" => [
        "address"          => "ACEH/KOTA BANDA ACEH/BANDA RAYA",
        "address_property" => 1,
        "live_time"        => 2
    ],

    "company_info" => [
        "has_work"           => 1,
        "work_type"          => 3,
        "work_certificate"   => "https://dss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=4022157416,1072029411&fm=26&gp=0.jpg",
        "company_name"       => "xxxxxx",
        "company_address"    => "ACEH/KOTA BANDA ACEH/BANDA RAYA",
        "company_telephone"  => "12342443423",
        "company_member_num" => 200,
        "working_time"       => 2,
        "email"               => " supercash0620@gmail.com"
    ],

    "face_info" => [
        "liveness_score" => 100,
        "similarity"     => '69.0'
    ],

    "app_list" => json_encode([
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
    ]),

    "contact_list" => json_encode([
        [
            "mobile" => "18989356622",
            "name"   => "lucy"
        ],
        [
            "mobile" => "18989356622",
            "name"   => "lucy"
        ]
    ])
];



$str = json_encode($test_arr_005);

// 加密
$en_data = base64_encode(openssl_encrypt($str, "AES-128-CBC", 'testtesttesttest', OPENSSL_RAW_DATA, 'testtesttesttest'));
print_r($en_data);
echo '<hr>';

// 解密
$de_data = openssl_decrypt(base64_decode($en_data), "AES-128-CBC", 'testtesttesttest', OPENSSL_RAW_DATA, 'testtesttesttest');
print_r($de_data);
echo '<hr>';

// 签名
ksort($test_arr_005);
$timestamp = 1575254404;
echo md5('testtesttesttest' . '*|*' . json_encode($test_arr_005, JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES) . '@!@' . $timestamp);die;



/**===================    Kreditpedia 004.api   ================**/

$url = 'http://39.96.161.37/Kreditpedia/004.api';

$data = '{"version":"1","partner_name":"Kreditpedia","appid":"99","timestamp":"1575254404","sign":"f4cfa1fc873d79ae218974d06a9a59e1","en_data":"MeEnb5BIwX1REoDw+jhWGQ7/IzJt2Ds/6ItCwhzAUjRgMAI9bvvKMiuA2ogbwKoVZLoSoanNWLsGJbsYO7MFnoWITDqixuZS+l/Xlgs5e9bZejVFLRHWxfSL6H0upTbciqR5+qshAaZrpVU7qIGZTC++e4J4dnw4t5tIdcPMrVaXdDu1KmB66ZMJcXwG7erRmG0GTCwkl2xLwf2/lit8zULay/bzcuB6UZf18OCgaRAoI9fH54Sx8to2OYY5DRrtp61JWcdrvnrUgfMh5FJHW2GfglIhoUpWd+Ljbj6gzGrUDzcaUy5lj9pdzvbcvcFj5rCLeOZ73qXT+CtyxKvWasNaZoDHfyZU5Rpwm0rMSwQiJeru0VkW/EyK0m4l0HdWG8Xracb67hebtIP2d+VlVCv2xEQX2QiFfjiJil0X8ViL2b4t8li18HaEmO9quICw5ho45UirNKZ6XnxQyYrQLNlZ3EwT/cu9VuyGB+ePjzk0S5Ggsnt6AJ89zdH4CCQtkQijOlC4iv3G1yLBd6Y9W1vcVsYxu/TtvWxT6tKybmPKWPfgCLzSh1rIDQ71LKnoP7UpmC5FvXnigtMw8H/hjpUBSo8TjQ0wKxnM5tw3sABq7DBsuekEMG5vU53cUSsLGtKR6VB05BcJ4eSmiN5m2x1Xy7vSEqQP1t6jeymv+7biGacLAErAANE/tvwsacQwYl5ynpSx4WUKKVg83VXZ3nztWK1bFjYHpnuXfvMcijzVAQvSQMvXGGf5pOeEiH5+hAJA1HAIjPVoY2H7KBJHT6zouv47hynlJa7LaqOVyW4Yicgj6g6ghH2ljghoU6t7lYSPhoXBI8HAHc00lRWSUtAQTHqMrUr3+oknvJUdWXLb8W6BkgLI1XsCMoxd3as5ugTQ3TfdVwt/dqr02frSKgrFvFHhbYEmYPXYlgHDO3IYE1bTFZ+87o3+h4qv9kEANfAhW69GQuiA+Ul4SSvWV/slxAgOF+6ligB1/QPKiH2Dohnmj8yDGzGIBBdpkx8ng2OBPtivj87VDtNb7Y8WnccMqv5Dy+exVYWf67WZTHM2P+CswFOjFt7F6fab2oCLdWVId87tfJdEGYMk5Z6Pn3fVbfmYsw1g9eKNSWnHfumrPqJwOanCVi19tNqySVxHPIL1ZPKwqxgx+06qWMz7bwS1XjXqIMxf5rz8BxzjihU="}';

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




























