<?php


/*

  █████░▒██   ██  ▄████▄   ██ ▄█▀       ██████╗ ██╗   ██╗ ██████╗
 ▓██   ▒ ██  ▓██▒▒██▀ ▀█   ██▄█▒        ██╔══██╗██║   ██║██╔════╝
 ▒████ ░▓██  ▒██░▒▓█    ▄ ▓███▄░        ██████╔╝██║   ██║██║  ███╗
 ░▓█▒  ░▓▓█  ░██░▒▓▓▄ ▄██▒▓██ █▄        ██╔══██╗██║   ██║██║   ██║
 ░▒█░   ▒▒█████▓ ▒ ▓███▀ ░▒██▒ █▄       ██████╔╝╚██████╔╝╚██████╔╝
  ▒ ░   ░▒▓▒ ▒ ▒ ░ ░▒ ▒  ░▒ ▒▒ ▓▒       ╚═════╝  ╚═════╝  ╚═════╝
  ░     ░░▒░ ░ ░   ░  ▒   ░ ░▒ ▒░
  ░ ░    ░░░ ░ ░ ░        ░ ░░ ░
           ░     ░ ░      ░  ░

                    .::::.
                  .::::::::.
                 .:::::::::'
               .:::::::::'
           ':::::::::::''
       .      .::::::::
        ':::::::::::::::
           `''::::::::::::.
           ``:::::::::::::::
            :::::``::::::::'        .:::.
           ::::'    :::::'       .::::::::.
          ::::'     :::::     .::::::::::::.
         :::'       :::::  .:::::::::''':::::.
        :::        :::::.::::::::::'     '::::.
      .::'        .::::::::::::::'         '::::
    .:::'         :::::::::::::'             ':::.
 ``````':.         ::::::::::'                 ':::.
                    '.:::::'                   ':'':::..

┌───┐   ┌───┬───┬───┬───┐ ┌───┬───┬───┬───┐ ┌───┬───┬───┬───┐ ┌───┬───┬───┐ ┌───┬───┬───┬───┐
│Esc│   │ F1│ F2│ F3│ F4│ │ F5│ F6│ F7│ F8│ │ F9│F10│F11│F12│ │P/S│S L│P/B│ │ F │ U │ C │ K │
└───┘   └───┴───┴───┴───┘ └───┴───┴───┴───┘ └───┴───┴───┴───┘ └───┴───┴───┘ └───┴───┴───┴───┘
┌───┬───┬───┬───┬───┬───┬───┬───┬───┬───┬───┬───┬───┬───────┐ ┌───┬───┬───┐ ┌───┬───┬───┬───┐
│~ `│! 1│@ 2│# 3│$ 4│% 5│^ 6│& 7│* 8│( 9│) 0│_ -│+ =│ BacSp │ │Ins│Hom│PUp│ │N L│ / │ * │ - │
├───┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─────┤ ├───┼───┼───┤ ├───┼───┼───┼───┤
│ Tab │ Q │ W │ E │ R │ T │ Y │ U │ I │ O │ P │{ [│} ]│ | \ │ │Del│End│PDn│ │ 7 │ 8 │ 9 │   │
├─────┴┬──┴┬──┴┬──┴┬──┴┬──┴┬──┴┬──┴┬──┴┬──┴┬──┴┬──┴┬──┴─────┤ └───┴───┴───┘ ├───┼───┼───┤ + │
│ Caps │ A │ S │ D │ F │ G │ H │ J │ K │ L │: ;│" '│ Enter  │               │ 4 │ 5 │ 6 │   │
├──────┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴────────┤     ┌───┐     ├───┼───┼───┼───┤
│ Shift  │ Z │ X │ C │ V │ B │ N │ M │< ,│> .│? /│  Shift   │     │ ↑ │     │ 1 │ 2 │ 3 │   │
├─────┬──┴─┬─┴──┬┴───┴───┴───┴───┴───┴──┬┴───┼───┴┬────┬────┤ ┌───┼───┼───┐ ├───┴───┼───┤ E││
│ Ctrl│    │Alt │         Space         │ Alt│    │    │Ctrl│ │ ← │ ↓ │ → │ │   0   │ . │←─┘│
└─────┴────┴────┴───────────────────────┴────┴────┴────┴────┘ └───┴───┴───┘ └───────┴───┴───┘
 */

#打开目录浏览。
autoindex on;

#默认为off，显示的文件时间为GMT时间。
#改为on后，显示的文件时间为文件的服务器时间。
autoindex_localtime on;

#默认为on，显示出文件的确切大小，单位是bytes。
#改为off后，显示出文件的大概大小，单位是kB或者MB或者GB。
autoindex_exact_size off;

#解决中文乱码问题。
charset utf-8,gbk;


        server_name  www.tianjintou.top;

        location / {
            root   /data/www/html;
            index  index.html index.htm index.php;
        }

        location ~ \.php$ {
            fastcgi_pass 127.0.0.1:9000;
            fastcgi_index index.php;
            fastcgi_param SCRIPT_FILENAME /data/www/html/$fastcgi_script_name;
            include fastcgi_params;
        }

include conf.d/*.conf;


server {
    listen       80;
    server_name  wp.tianjintou.top;

    location / {
        root   /data/www/html/wordpress/;
        index  index.html index.htm index.php;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME /data/www/html/wordpress/$fastcgi_script_name;
        include fastcgi_params;
    }
}













// /** WordPress数据库的名称 */
// define('DB_NAME', 'wordpress');

// /** MySQL数据库用户名 */
// define('DB_USER', 'root');

// /** MySQL数据库密码 */
// define('DB_PASSWORD', 'root');

// /**
//  * WordPress数据表前缀。
//  *
//  * 如果您有在同一数据库内安装多个WordPress的需求，请为每个WordPress设置
//  * 不同的数据表前缀。前缀名只能为数字、字母加下划线。
//  */
// $table_prefix  = 'wp_';


// https://files.phpmyadmin.net/phpMyAdmin/5.1.1/phpMyAdmin-5.1.1-all-languages.zip
// https://files.phpmyadmin.net/phpMyAdmin/5.1.1/phpMyAdmin-5.1.1-all-languages.tar.gz
// https://files.phpmyadmin.net/phpMyAdmin/5.1.1/phpMyAdmin-5.1.1-all-languages.tar.xz

$str = file_get_contents('./result.json');

$arr = json_decode($str, true);

$report = $arr['data']['result'];

// echo '<pre>';
// print_r($report);die;


require_once 'vendor/autoload.php';


$pdf = new TCPDF();
// 设置文档信息
// $pdf->SetCreator('懒人开发网');
// $pdf->SetAuthor('懒人开发网');
// $pdf->SetTitle('TCPDF示例');
// $pdf->SetSubject('TCPDF示例');
// $pdf->SetKeywords('TCPDF, PDF, PHP');

// 设置页眉和页脚信息
// $pdf->SetHeaderData('tcpdf_logo.jpg', 30, 'LanRenKaiFA.com', '学会偷懒，并懒出效率！', [0, 64, 255], [0, 64, 128]);
// $pdf->setFooterData([0, 64, 0], [0, 64, 128]);

// 设置页眉和页脚字体
// $pdf->setHeaderFont(['stsongstdlight', '', '10']);
// $pdf->setFooterFont(['helvetica', '', '8']);

// 设置默认等宽字体
// $pdf->SetDefaultMonospacedFont('courier');

// 设置间距
// $pdf->SetMargins(15, 15, 15);//页面间隔
// $pdf->SetHeaderMargin(5);//页眉top间隔
// $pdf->SetFooterMargin(10);//页脚bottom间隔

// 设置分页
// $pdf->SetAutoPageBreak(true, 25);

// set default font subsetting mode
// $pdf->setFontSubsetting(true);

//设置字体 stsongstdlight支持中文
$pdf->SetFont('stsongstdlight', '', 14);

//第一页
$pdf->AddPage();
$pdf->Ln(40);//换行符
$pdf->writeHTML('<div style="text-align: center"><h1>国聘高考志愿专业导航测评报告</h1></div>');
$pdf->Ln(40);//换行符
$pdf->writeHTML('<p style="text-align: center">姓名：' . $report['basic_info']['name'] . '</p>');
$pdf->Ln(20);//换行符
$pdf->writeHTML('<p style="text-align: center">性别：' . $report['basic_info']['sex'] . '</p>');
$pdf->Ln(20);//换行符
$pdf->writeHTML('<p style="text-align: center">年龄：' . $report['basic_info']['age'] . '</p>');


// 第二页
$pdf->AddPage();
$pdf->Ln(5);//换行符
$pdf->writeHTML('<p>第一部分 总体结果</p>');
$pdf->Ln(5);//换行符
$pdf->writeHTML('<p>您在测试中的作答表明，您的性格类型为“' . $report['report']['type'] . '”型，是一名“' . $report['report']['name'] . '”</p>');
$pdf->writeHTML('<p>您在不同方面的得分如下：</p>');
$pdf->Ln(5);//换行符
// $pdf->writeHTML('<p><img src="images/guopin.png"></p>');

$score_html = '
    <table>
        <tr>
            <td colspan="4" style="text-align: center;">您倾向于将注意力集中在：</td>
        </tr>
        <tr>
            <th style="text-align: left; width: 15%;">外向（E）</th>
            <th style="text-align: right; width: 35%;"><div style="background-color: #4682B4; margin-right: 5px;">' . $report['every_num']['E']  . '</div></th>
            <th style="width: 3%;"></th>
            <th style="text-align: left; width: 35%;"><div style="background-color: #4682B4; margin-left: 5px">' . $report['every_num']['I']  . '</div></th>
            <th style="text-align: right; width: 15%;">外向（I）</th>
        </tr>
        <tr>
            <th style="text-align: left; width: 15%;">感觉（S）</th>
            <th style="text-align: right; width: 35%;"><div style="background-color: #4682B4; margin-right: 5px;">' . $report['every_num']['S']  . '</div></th>
            <th style="width: 3%;"></th>
            <th style="text-align: left; width: 35%;"><div style="background-color: #4682B4; margin-left: 5px">' . $report['every_num']['N']  . '</div></th>
            <th style="text-align: right; width: 15%;">直觉（N）</th>
        </tr>
        <tr>
            <th style="text-align: left; width: 15%;">思维（T）</th>
            <th style="text-align: right; width: 35%;"><div style="background-color: #4682B4; margin-right: 5px;">' . $report['every_num']['T']  . '</div></th>
            <th style="width: 3%;"></th>
            <th style="text-align: left; width: 35%;"><div style="background-color: #4682B4; margin-left: 5px">' . $report['every_num']['F']  . '</div></th>
            <th style="text-align: right; width: 15%;">情感（F）</th>
        </tr>
        <tr>
            <th style="text-align: left; width: 15%;">判断（J）</th>
            <th style="text-align: right; width: 35%;"><div style="background-color: #4682B4; margin-right: 5px;">' . $report['every_num']['J']  . '</div></th>
            <th style="width: 3%;"></th>
            <th style="text-align: left; width: 35%;"><div style="background-color: #4682B4; margin-left: 5px;">' . $report['every_num']['P']  . '</div></th>
            <th style="text-align: right; width: 15%;">知觉（P）</th>
        </tr>
    </table>';
$pdf->writeHTML('<p>' . $score_html . '</p>');


$pdf->Ln(5);//换行符
$pdf->writeHTML('<p>第二部分 结果解释</p>');
$pdf->writeHTML('<p><dt>典型表现：</dt>');
$pdf->Ln(5);//换行符
$expression_html = '<dd>';
foreach ($report['report']['expression']['content'] as $value) {
    $expression_html .= $value . '<br>';
}
$pdf->writeHTML($expression_html . '</dd>');
$pdf->writeHTML('<dt>性格盲区：</dt>');
$pdf->Ln(5);//换行符
$blind_area_html = '<dd>';
foreach ($report['report']['blind_area']['content'] as $value) {
    $blind_area_html .= $value . '<br>';
}
$pdf->writeHTML($blind_area_html . '</dd></p>');


$pdf->Ln(5);//换行符
$pdf->writeHTML('<p>第三部分 专业匹配</p>');
$pdf->Ln(5);//换行符
$pdf->writeHTML('<p>&nbsp;&nbsp;&nbsp;&nbsp;' . $report['report']['profession']['content'] . '</p>');

$pdf->Ln(5);//换行符
$profession_html = '
    <style>
        th, td{
            border: 1px solid #cccccc;
            margin: 0;
            padding: 10px;
            text-align: left;
        }
    </style>
    <table style="border-collapse: collapse;">
        <tr>
            <th style="text-align: center; width: 80%;">专业类别</th>
            <th style="text-align: center; width: 20%;">推荐等级</th>
        </tr>
        <tr>
            <th style="text-align: left; width: 80%;">' . $report['report']['profession']['result'][0]['content'] . '</th>
            <th style="text-align: center; width: 20%;">' . $report['report']['profession']['result'][0]['priority'] . '</th>
        </tr>
        <tr>
            <th style="text-align: left; width: 80%;">' . $report['report']['profession']['result'][1]['content'] . '</th>
            <th style="text-align: center; width: 20%;">' . $report['report']['profession']['result'][1]['priority'] . '</th>
        </tr>
        <tr>
            <th style="text-align: left; width: 80%;">' . $report['report']['profession']['result'][2]['content'] . '</th>
            <th style="text-align: center; width: 20%;">' . $report['report']['profession']['result'][2]['priority'] . '</th>
        </tr>
    </table>';
$pdf->writeHTML($profession_html);


$pdf->Ln(5);//换行符
$pdf->writeHTML('<p>第四部分 成长建议</p>');
$pdf->writeHTML('<p><dt>&nbsp;&nbsp;&nbsp;&nbsp;' . $report['report']['advice']['content']['title'] . '</dt>');
$pdf->Ln(5);//换行符
$advice_html = '<dd>';
foreach ($report['report']['advice']['content']['content'] as $key => $value) {
    $advice_html .= $key + 1 . '、' . $value . '<br>';
}
$pdf->writeHTML($advice_html . '</dd></p>');

$pdf->Ln(5);//换行符
$pdf->writeHTML('<p>第五部分 使用帮助</p>');
$pdf->Ln(5);//换行符
$pdf->writeHTML('<p>' . $report['report']['help']['info'][0]['title'] . '</p>');
$pdf->Ln(5);//换行符
$pdf->writeHTML('<p>&nbsp;&nbsp;&nbsp;&nbsp;' . $report['report']['help']['info'][0]['content'] . '</p>');
$pdf->Ln(5);//换行符
$pdf->writeHTML('<p>' . $report['report']['help']['info'][1]['title'] . '</p>');
$pdf->writeHTML('<p><dt>&nbsp;&nbsp;&nbsp;&nbsp;' . $report['report']['help']['info'][1]['content']['title'] . '</dt>');
$pdf->Ln(5);//换行符
$help_html = '<dd>';
foreach ($report['report']['help']['info'][1]['content']['content'] as $key => $value) {
    $help_html .= $key + 1 . '、' . $value . '<br>';
}
$pdf->writeHTML($help_html . '</dd></p>');

//输出PDF
$pdf->Output('t.pdf', 'I');//I输出、D下载


die;






phpinfo();die;

/**
 * CURL 请求 POST
 *
 * @param string $url
 * @param array $params
 * @param array $header
 */
function curlPost($url = '', $params = [], $header = [])
{
    $default_header = [
        'content-type:application/json',
    ];

    $header = array_merge($default_header, $header);
    $params = json_encode($params);

    // 1. 初始化
    $ch = curl_init();

    // 2. 设置选项，包括 URL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    // 设置获取的信息以文件流的形式返回，而不是直接输出
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // 允许 cURL 函数执行的最长秒数
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
    // 在尝试连接时等待的秒数。设置为0，则无限等待
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
    // true 时会发送 POST 请求，类型为：application/x-www-form-urlencoded，是 HTML 表单提交时最常见的一种
    curl_setopt($ch, CURLOPT_POST, 1);
    // true 禁用 @ 前缀在 CURLOPT_POSTFIELDS 中发送文件。 意味着 @ 可以在字段中安全得使用了
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

    // 设置为 0 表示不返回 HTTP 头部信息
    curl_setopt($ch, CURLOPT_HEADER, 0);

    // 3. 执行并获取HTML文档内容
    $result = curl_exec ($ch);

    if (curl_errno ($ch)) {
        $result = curl_error($ch);
    }

    // 4. 释放curl句柄
    curl_close ($ch);

    return json_decode($result, true);
}

/**
 * CURL 请求 GET
 *
 * @param string $url
 * @param array $header
 */
function curlGet ($url = '', $header = [])
{
    $default_header = [
        'content-type:application/json',
    ];

    $header = array_merge($default_header, $header);

    // 初始化
    $ch = curl_init();

    // 设置抓取的url
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    // 设置获取的信息以文件流的形式返回，而不是直接输出
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // 执行命令
    $result = curl_exec($ch);

    if (curl_errno ($ch)) {
        $result = curl_error($ch);
    }

    //关闭URL请求
    curl_close($ch);

    //显示获得的数据
    return json_decode($result, true);
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















































