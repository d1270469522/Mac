<!--
* @Author: 天尽头流浪
* @Date:   2019-09-07 12:21:31
* @Last Modified by:   天尽头流浪
* @E-mail: 1270469522@qq.com
-->

<!DOCTYPE html>
<html>
<meta name="csrf-token" content="{{ csrf_token() }}">
<head>
    <!-- 加载公共文件：css -->
    @include('public/base_css')
</head>
<body class="layui-layout-body">
    <div class="layui-layout layui-layout-admin">

        <div class="layui-container">
            常规布局（以中型屏幕桌面为例）：
            <div class="layui-row">
                <div class="layui-col-lg9">
                    你的内容 9/12
                </div>
                <div class="layui-col-lg3">
                    你的内容 3/12
                </div>
            </div>
        </div>

    </div>
</body>
<!-- 加载公共文件：js -->
@include('public/base_script')
