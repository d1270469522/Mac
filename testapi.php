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




require_once 'vendor/autoload.php';
//     $mpdf = new Mpdf\Mpdf(
//         [
//             'mode' => 'UTF-8',
//             'format' => 'A4',
//             // 'default_font_size' => 40,
//             // 'default_font' => '',
//             // 'margin_left' => 20,
//             // 'margin_right' => 20
//         ]
//     );
//     $mpdf->autoScriptToLang = true;//支持中文设置
//     $mpdf->autoLangToFont = true;//支持中文设置

//     $mpdf->WriteHTML('111');
//     $mpdf->Output();//直接在页面显示pdf页面内容
//     // $path = FILE_UPLOAD.date('YmdHis').'_'.mt_rand(1,5).'.pdf';
//     // $mpdf->Output($path,'f');//保存pdf文件到指定目录
// die;

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
        // $pdf->writeHTML('1111');
        $pdf->writeHTML('<div style="text-align: center"><h1>第一页内容</h1></div>');
        $pdf->writeHTML('<p>我是第一行内容rerewqrwqe</p>');
        $pdf->writeHTML('<p style="color: red">我是第二行内容</p>');
        $pdf->writeHTML('<p>我是第三行内容</p><br>');
        // $pdf->Ln(5);//换行符
        $pdf->writeHTML('<p><a href="http://www.lanrenkaifa.com/" title="">懒人开发网</a></p>');

        // 第二页
        $pdf->AddPage();
        $pdf->writeHTML('<h1>第二页内容</h1>');

        //输出PDF
        $pdf->Output('t.pdf', 'D');//I输出、D下载


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















































