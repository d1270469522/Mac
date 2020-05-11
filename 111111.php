<?php

$method="AES-128-CBC";

$originKey='c1738990a469934118cc1880af47e9dd';
$originIv='0af9d0aa4e0e74c0b47874a534a8c2a5';
$key=hex2bin($originKey);
$iv=hex2bin($originIv);

$options=OPENSSL_RAW_DATA;

$params=json_decode("{\"approval_amount\":1000000.0,\"order_no\":\"202003061322425027\",\"term_unit\":1,\"pay_amount\":1000000.0,\"actual_amount\":650000.0,\"remark\":\"reject\",\"admin_amount\":140000.0,\"interest_amount\":210000.0,\"conclusion\":40,\"supplements\":[],\"amount_type\":0,\"approval_term\":7,\"approval_time\":1583476076,\"interest_rate\":0.03,\"term_type\":0,\"loan_time\":1583475765}",true);

$enData=base64_encode(openssl_encrypt(
json_encode($params,JSON_UNESCAPED_UNICODE+JSON_UNESCAPED_SLASHES+JSON_PRESERVE_ZERO_FRACTION),
$method,
$key,
$options,
$iv
));
echo"Encrypted:$enData\n";

$encoded=base64_decode($enData);
$decrypted=openssl_decrypt(
$encoded,
$method,
$key,
$options,
$iv
);

echo"Decrypted:$decrypted\n";

$param=json_decode($decrypted,true);
$timestamp="1583905021109";//公共参数中获取
echogetSign($originKey,$param,$timestamp);

//签名
functiongetSign($key,$params,$timestamp)
{
ksort($params);
//echo$key.'*|*'.json_encode($params,JSON_UNESCAPED_UNICODE+JSON_UNESCAPED_SLASHES+JSON_PRESERVE_ZERO_FRACTION).'@!@'.$timestamp;
returnmd5($key.'*|*'.json_encode($params,JSON_UNESCAPED_UNICODE+JSON_UNESCAPED_SLASHES+JSON_PRESERVE_ZERO_FRACTION).'@!@'.$timestamp);
}
