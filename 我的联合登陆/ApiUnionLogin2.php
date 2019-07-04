<?php

header("Content-type:text/html;charset=utf-8");

$data = $_POST['data'];

//把base64串转化十六进制
$str_2hex = base64_url_decode($data);
//十六进制转化二进制
$arr_json = hex2bin($str_2hex);
//json格式转成数组
$arr = json_decode($arr_json, true);

$sign1 = $arr['sign'];
unset($arr['sign']);

$sign2 = Signature::sign($arr);

//验证签名
if ($sign1 == $sign2) {

	//手机号解密
	$mobile1 = HhrCrypt::decrypt($arr['mobile']);
	$ret = [
		'code' => 200,
		'msg' => 'success',
		'mobile' => $mobile1,
		'url' => 'http://www.baidu.com',
	];
} else {
	$ret = [
		'code' => 1001,
		'msg' => '签名错误',
		'url' => ''
	];
}
echo json_encode($ret, JSON_UNESCAPED_UNICODE);die;








/**----------------------------------------------------------------------------------------**\
 -------------------------------------------------------------------------------------------
 ---------------------------                                     ---------------------------
 ---------------------------           下面是定义的函数和类          ---------------------------
 ---------------------------                                     ---------------------------
 -------------------------------------------------------------------------------------------
\**----------------------------------------------------------------------------------------**/


/**
 * [base64_url_decode 函数：base解密]
 * @param  [type] $data [description]
 * @return [type]       [description]
 */
function base64_url_decode($data) {
	return base64_decode(strtr($data, '‐_,', '+/='));
}


/**
 * 类：参数签名
 */
class Signature {

	// 测试使用
	const SALT = 'testtest';

	/**
	 * [sign 方法：生成签名]
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
 * 类：解密算法
 */
class HhrCrypt {

	// 测试使用
	const KEY = 'testtesttesttest';
	// 测试使用
	const IV  = 'testtesttesttest';

	/**
	 * [decrypt 解密]
	 * @param  [type] $encryData [description]
	 * @return [type]            [description]
	 */
	public static function decrypt($encryData)
	{
		$key  = self::KEY;
		$iv   = self::IV;
		$data = openssl_decrypt($encryData, 'aes-128-cbc', $key, OPENSSL_ZERO_PADDING, $iv);
		$data = substr($data,0,11);
		return $data;
	}
}


