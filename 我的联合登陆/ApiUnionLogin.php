<?php

header("Content-type:text/html;charset=utf-8");

$mobile1   = '13000000000';
$mobile    = HhrCrypt::encrypt($mobile1);
$app_id    = 'test';

$arr = [
	'mobile'    => $mobile,
	'app_id'    => $app_id,
];


//生成签名
$sign = Signature::sign($arr);
//签名入参
$arr['sign'] = $sign;


//参数转成json格式
$arr_json   = json_encode($arr);
//二进制换十六进制
$str_2hex   = bin2hex($arr_json);
//进行base64转化
$str_base64 = base64_url_encode($str_2hex);

$data = ['data' => $str_base64];

$url = 'http://........../api.php';

//调用方法：getCurl
$res = getCurl($url, $data);
$ret = json_decode($res, true);

if ($ret['code'] == 200) {
	// header("location:".$ret['url']);
	//显示获得的数据
	echo '<pre>';
	print_r(json_decode($res, true));die;
} else {
	echo '<pre>';
	print_r(json_decode($res, true));die;
}








/**----------------------------------------------------------------------------------------**\
 -------------------------------------------------------------------------------------------
 ---------------------------                                     ---------------------------
 ---------------------------           下面是定义的函数和类          ---------------------------
 ---------------------------                                     ---------------------------
 -------------------------------------------------------------------------------------------
\**----------------------------------------------------------------------------------------**/



/**
 * [getCurl 函数：curl请求]
 * @param  string $url  [description]
 * @param  array  $data [description]
 * @return [type]       [description]
 */
function getCurl ($url = '', $data = [])
{
	if ($url == '' || empty($data)) {
		return false;
	}
	//初始化
	$curl = curl_init();
	//设置抓取的url
	curl_setopt($curl, CURLOPT_URL, $url);
	//设置获取的信息以文件流的形式返回，而不是直接输出。
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	//设置post方式提交
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	//执行命令
	$res = curl_exec($curl);
	$err_code = curl_errno($curl);
	//关闭URL请求
	curl_close($curl);
	//打印错误信息
	if($err_code) {
		return $err_code;die;
	}
	return $res;die;
}


/**
 * 函数：base64转化
 * @param  [type] $data [description]
 * @return [type]       [description]
 */
function base64_url_encode($data = []) {
	if (empty($data)) {
		return false;
	}
	return iconv('UTF-8', 'GB2312//IGNORE',  strtr(base64_encode($data), '+/=', '‐_,'));
}

/**
 * 类：参数签名
 */
class Signature {

	// 测试使用
	const SALT = 'testtest';

	/**
	 * [sign 生成签名]
	 * @param  [type] $args [description]
	 * @return [type]       [description]
	 */
	public static function sign($args) {
		if (!$args) {
			return '';
		}
		$salt = self::SALT;
		ksort($args);
		$signStr = '';
		foreach ($args as $k => $v) {
			if ($v || is_numeric($v)) {
				$signStr .= $k.$v;
			}
		}
		return strtoupper(md5(md5($signStr) . $salt));
	}
}

/**
 * 类：加密算法
 */
class HhrCrypt {

	// 测试使用
	const KEY = 'testtesttesttest';
	// 测试使用
	const IV  = 'testtesttesttest';

	/**
	 * [encrypt 方法：加密]
	 * @param  [type] $content [description]
	 * @return [type]          [description]
	 */
	public static function encrypt($content)
	{
		$key     = self::KEY;
		$content = $content . "\0\0\0\0\0";
		$iv      = self::IV;
		$data    = openssl_encrypt($content, "aes-128-cbc", $key, OPENSSL_ZERO_PADDING, $iv);
		$error   = openssl_error_string();

		if($error) {
			return false;
		}
		return $data;
	}
}


