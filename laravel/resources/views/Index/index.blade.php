<!--
* @Author: 首页
* @Date:   2019-07-18 18:34:27
* @Last Modified by:   天尽头流浪
* @E-mail: 1270469522@qq.com
-->

<!DOCTYPE html>
<html>
<head>
    {{-- 加载公共文件：css --}}
    @include('public/base_css')
</head>
<body class="layui-layout-body">
    <div class="layui-layout layui-layout-admin">
        {{-- 加载头部 --}}
        @include('public/common_header')
        {{-- 加载左侧 --}}
        @include('public/common_left')

        <div class="layui-body"></div>

        {{-- 加载底部 --}}
        @include('public/common_bottom')
    </div>
</body>

{{-- 加载公共文件：js --}}
@include('public/base_script')
</html>
