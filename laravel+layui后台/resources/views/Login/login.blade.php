<!--
* @Author: 登陆
* @Date:   2019-07-11 18:16:24
* @Last Modified by:   天尽头流浪
* @E-mail: 1270469522@qq.com
-->
<!DOCTYPE html>
<html>
<meta name="csrf-token" content="{{ csrf_token() }}" />
<head>
    {{-- 加载公共文件：css --}}
    @include('public/base_css')
    {{-- 登陆页星空样式：css --}}
    <link rel="stylesheet" href="{{ asset('css/starrySky.css') }}">
</head>
<body>

    <canvas id="canvas"></canvas>

    <form class="layui-form layui-form-pane">

        <div class="layui-form-item">
            <label class="layui-form-label">@lang('login.username')</label>
            <div class="layui-input-block">
                <input type="text" name="username" class="layui-input" lay-verify="required" autocomplete="off" placeholder="@lang('login.username_placeholder')" value="天尽头流浪">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">@lang('login.password')</label>
            <div class="layui-input-block">
                <input type="password" name="password" class="layui-input" lay-verify="required" autocomplete="off" placeholder="@lang('login.password_placeholder')" value="123456">
            </div>
        </div>

        <div class="layui-form-item">
            <button class="layui-btn" lay-submit="" lay-filter="formSubmit">@lang('login.login')</button>
        </div>
    </form>

</body>


{{-- 加载公共文件：js --}}
@include('public/base_script')
{{-- 登陆页星空样式：js --}}
<script type="text/javascript" src="{{ asset('js/starrySky.js') }}"></script>
<script type="text/javascript">

    layui.use(['jquery', 'layer', 'form'], function(){

        var $     = layui.$         //重点处
        var layer = layui.layer     //弹窗
        var form  = layui.form      //表单


        //这里  Ajax没有起效，因为return false;注释掉了
        form.on('submit(formSubmit)', function(data){

            var paramJson = JSON.stringify(data.field)

            $.ajax({
                url : "/login",
                data : {paramJson},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type : 'POST',
                dataType : 'json',
                success: function (res) {

                    if (res.code != 200) {

                        layer.msg(res.msg);
                    } else {

                        //登陆成功，跳转首页
                        window.location = "/";
                    }
                },error: function () {

                }
            });
            return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
        });
    });

</script>
</html>
