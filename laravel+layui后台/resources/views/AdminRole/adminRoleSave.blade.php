<!--
* @Author: 天尽头流浪
* @Date:   2019-08-01 16:17:51
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

        <div class="layui-form-item">
            <label class="layui-form-label">@lang('adminRole.field.name')</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" autocomplete="off" name="role_name" lay-verify="required" autocomplete="off" value="@if(isset($data)){{ $data['role_name'] }}@endif">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">@lang('adminRole.field.status')</label>
            <div class="layui-input-block">
                <input type="radio" name="status" value="1" title="@lang('adminRole.tpl.valid')" @if(isset($data)) @if($data['status'] === 1)checked @endif @else checked @endif>
                <input type="radio" name="status" value="0" title="@lang('adminRole.tpl.invalid')"  @if(isset($data) && $data['status'] === 0)checked @endif>
            </div>
        </div>

        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">@lang('adminRole.field.desc')</label>
            <div class="layui-input-block">
                <textarea name="role_desc" lay-verify="required" class="layui-textarea">@if(isset($data)){{ $data['role_desc'] }}@endif</textarea>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="formSubmit">@lang('adminRole.save.submit')</button>
                <button type="reset" class="layui-btn layui-btn-primary">@lang('adminRole.save.reset')</button>
            </div>
        </div>
    </form>

</body>
<!-- 加载公共文件：js -->
@include('public/base_script')

<script>

    layui.use(['jquery', 'layer', 'form'], function(){

        var $     = layui.$         //重点处
        var layer = layui.layer     //弹窗
        var form  = layui.form      //表单

        //这里 return false;如果注释 Ajax不起效，执行表单提交
        form.on('submit(formSubmit)', function(data){

            var paramJson = JSON.stringify(data.field)

            var url = "/AdminRole/adminRoleSavePro";

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

                        parent.location.reload(); // 父页面刷新
                        var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                        parent.layer.close(index); //再执行关闭
                    } else {
                        layer.msg(res.msg);
                    }
                },error: function () {

                    layer.msg("@lang('adminRole.message.network_error')");
                }
            });
            return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
        });
    });
</script>
</html>
