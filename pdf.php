<?php


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
