<?php



// header("Content-type: text/html; charset=utf-8");
// header("Content-type: text/html; charset=gbk");


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







// echo base64_encode('6FAppdsCj2Or5BBQh6U0i8e6snBbPXg9');die;

// echo '<pre>';
// print_r(curPageURL());die;


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

/**===================    Access Token   ================**/

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

/**===================    第三步   ================**/

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



function curlPost($url, $data, $header)
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




























