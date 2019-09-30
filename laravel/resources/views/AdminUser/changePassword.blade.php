<!--
* @Author: 天尽头流浪
* @Date:   2019-09-05 19:31:36
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

    <form class="layui-form layui-form-pane" style="margin: 20px;">

        <input type="hidden" class="layui-input" autocomplete="off" name="id" autocomplete="off" value="@if(isset($data)){{ $data['id'] }}@endif" disabled>
        <input type="hidden" class="layui-input" autocomplete="off" name="password" autocomplete="off" value="@if(isset($data)){{ $data['password'] }}@endif" disabled>

        <div class="layui-form-item">
            <label class="layui-form-label">@lang('adminUser.password.old')</label>
            <div class="layui-input-block">
                <input type="password" class="layui-input" autocomplete="off" name="old_password" lay-verify="required|password|old_password" autocomplete="off" value="">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">@lang('adminUser.password.new')</label>
            <div class="layui-input-block">
                <input type="password" class="layui-input" autocomplete="off" name="new_password" lay-verify="required|password|new_password" autocomplete="off" value="">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">@lang('adminUser.password.new2')</label>
            <div class="layui-input-block">
                <input type="password" class="layui-input" autocomplete="off" name="new2_password" lay-verify="required|password|new2_password" autocomplete="off" value="">
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="formSubmit">@lang('adminUser.save.submit')</button>
                <button type="reset" class="layui-btn layui-btn-primary">@lang('adminUser.save.reset')</button>
            </div>
        </div>
    </form>

</body>
<!-- 加载公共文件：js -->
@include('public/base_script')
<script type="text/javascript" src="{{ URL::asset('js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/md5.js') }}"></script>
<script>

    layui.use(['jquery', 'layer', 'form'], function(){

        var $     = layui.$         //重点处
        var layer = layui.layer     //弹窗
        var form  = layui.form      //表单

        //自定义验证规则
        form.verify({
            password: [
                /^[\S]{6,12}$/
                ,"@lang('adminUser.password.verify.password')"
            ]
            ,old_password: function(value) {
                if (hex_md5(value) != $('input[name="password"]').val()) {
                    return "@lang('adminUser.password.verify.old_password')";
                }
            }
            ,new_password: function(value) {
                if (value == $('input[name="old_password"]').val()) {
                    return "@lang('adminUser.password.verify.new_password')";
                }
            }
            ,new2_password: function(value) {
                if (value != $('input[name="new_password"]').val()) {
                    return "@lang('adminUser.password.verify.new2_password')";
                }
            }
        });

        //这里 return false;如果注释 Ajax不起效，执行表单提交
        form.on('submit(formSubmit)', function(data){

            var paramJson = JSON.stringify({id:$('input[name="id"]').val(), password:$('input[name="new_password"]').val()})

            var url = "/AdminUser/changePassword";

            $.ajax({
                url : url,
                data : {paramJson},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type : 'POST',
                dataType : 'json',
                success: function (res) {

                    if (res.code == 200) {

                        //配置一个透明的询问框
                        layer.msg("@lang('adminUser.password.success_msg')", {

                            btn : ["@lang('adminUser.password.btn_msg')"],
                        }, function(){

                            //修改密码成功，父页面退出登陆
                            //不能再当前页面退出，这里是iframe页面
                            parent.location.href = "/Login/logout";
                        });

                    } else {
                        layer.msg(res.msg);
                    }
                },error: function () {

                    layer.msg("@lang('adminUser.message.network_error')");
                }
            });
            return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
        });
    });
</script>
</html>
