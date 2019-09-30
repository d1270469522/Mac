<!--
* @Author: 天尽头流浪
* @Date:   2019-07-26 10:58:26
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
        <!-- 公共html：加载头部 -->
        @include('public/common_header')
        <!-- 公共html：加载左侧 -->
        @include('public/common_left')
        <div class="layui-body">

            <!-- 代码 begin -->
            <div style="text-align: center; width: 500px; margin: 200px auto; font-size: 20px;">
                <span style="color: red">{{ $message }}</span>
                <br><br>

                <span id="loginTime" style="font-weight: bold;">{{ $jumpTime }}</span>
                    @lang('message.jumpTo')
                <a href="{{ $url }}" style="color: blue">{{ $urlname }}</a>
            </div>

        </div>
        <!-- 公共html：加载底部 -->
        @include('public/common_bottom')
    </div>
</body>
<!-- 加载公共文件：js -->
@include('public/base_script')

<script>

    layui.use(['jquery'], function(){

        var $ = layui.$  //重点处

        $(function(){

            var url = "{{ $url }}"
            var loginTime = parseInt($('#loginTime').text());

            var time = setInterval(function(){

                loginTime = loginTime - 1;
                $('#loginTime').text(loginTime);

                if(loginTime == 0){

                    clearInterval(time);
                    window.location.href = url;
                }
            },1000);
        })
    })
</script>
</html>
