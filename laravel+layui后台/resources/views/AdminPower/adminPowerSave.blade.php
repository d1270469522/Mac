<!--
* @Author: 天尽头流浪
* @Date:   2019-07-31 10:35:50
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
            <label class="layui-form-label">@lang('adminPower.field.name')</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" autocomplete="off" name="power_name" lay-verify="required" autocomplete="off" value="@if(isset($data)){{ $data['power_name'] }}@endif">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">@lang('adminPower.field.routes')</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" autocomplete="off" name="power_url" autocomplete="off" value="@if(isset($data)){{ $data['power_url'] }}@endif">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">@lang('adminPower.save.routes_father')</label>
            <div class="layui-input-block">
                <select name="parent_id" lay-verify="required">
                    <option value="0" @if(isset($data) && $data['parent_id'] === 0) selected @endif>├ @lang('adminPower.save.root_directory')</option>
                    @foreach($tree as $key => $value)
                        <option value="{{ $value['id'] }}"
                            @if(isset($data) && $data['parent_id'] === $value['id']) selected @endif
                            @if(isset($data) && $data['id'] === $value['id']) disabled @endif>
                            <?php echo $value['level']; ?><?php echo __('menu.'.$value['power_name']); ?>
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">@lang('adminPower.field.type')</label>
            <div class="layui-input-block">
                <select name="power_type" lay-verify="required">
                    <option value=""></option>
                    <option value="11" @if(isset($data) && $data['power_type'] === 11) selected @endif>@lang('adminPower.field.side_menu')</option>
                    <option value="12" @if(isset($data) && $data['power_type'] === 12) selected @endif>@lang('adminPower.field.page_elements')</option>
                    <option value="13" @if(isset($data) && $data['power_type'] === 13) selected @endif>@lang('adminPower.field.specific_function')</option>
                </select>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">@lang('adminPower.status.status')</label>
            <div class="layui-input-block">
                <input type="radio" name="status" value="1" title="@lang('adminPower.status.valid')" @if(isset($data)) @if($data['status'] === 1)checked @endif @else checked @endif>
                <input type="radio" name="status" value="0" title="@lang('adminPower.status.invalid')"  @if(isset($data) && $data['status'] === 0)checked @endif>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">@lang('adminPower.field.icon')</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" autocomplete="off" name="icon" autocomplete="off" value="@if(isset($data)){{ $data['icon'] }}@endif">
            </div>
        </div>

        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">@lang('adminPower.field.desc')</label>
            <div class="layui-input-block">
                <textarea name="power_desc" lay-verify="required" class="layui-textarea">@if(isset($data)){{ $data['power_desc'] }}@endif</textarea>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="formSubmit">@lang('adminPower.submit')</button>
                <button type="reset" class="layui-btn layui-btn-primary">@lang('adminPower.reset')</button>
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

            var url = "/AdminPower/adminPowerSavePro";

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

                    layer.msg('网络异常！');
                }
            });
            return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
        });
    });
</script>
</html>
