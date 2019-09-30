<!--
* @Author: 天尽头流浪
* @Date:   2019-08-12 15:54:08
* @Last Modified by:   天尽头流浪
* @E-mail: 1270469522@qq.com
-->

<!DOCTYPE html>
<html>
<meta name="csrf-token" content="{{ csrf_token() }}">
<head>
    <!-- 加载公共文件：css -->
    @include('public/base_css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('layui/module/layui-formSelects/formSelects-v4.css') }}"/>
</head>
<body>
    <form class="layui-form" style="height: 420px; margin-top: 10px;">

        <!-- ID -->
        <input type="hidden" name="user_id" id="user_id" value="{{ $user_id }}">

        <!-- 所有角色，下拉菜单，给用户分配 -->
        <select name="role" xm-select="select_role" xm-select-show-count="3" id="role">
            @foreach($data as $key => $value)
                <option
                    value="{{ $value['id'] }}"
                    @if($value['selected'] == 'true') selected @endif
                    @if($value['disabled'] == 'true') disabled @endif
                >{{ $value['role_name'] }}</option>
            @endforeach
        </select>

        <!-- 提交、重置 -->
        <div class="layui-form-item" style="position: absolute; right: 15px; bottom: 0;">
            <div class="layui-input-block">
                <button class="layui-btn" type="submit" lay-submit lay-filter="select-submit">提交</button>
                <button class="layui-btn layui-btn-primary" type="reset">重置</button>
            </div>
        </div>
    </form>
</body>

<!-- 加载公共文件：js -->
@include('public/base_script')

<script type="text/javascript">
    //全局定义一次, 加载formSelects
    layui.config({
        base: "{{ URL::asset('layui/module') }}" //此处路径请自行处理, 可以使用绝对路径
    }).extend({
        formSelects: '/layui-formSelects/formSelects-v4'
    }).use(['jquery', 'form', 'layer', 'formSelects'], function(){

        var $           = layui.jquery;
        var layer       = layui.layer;
        var form        = layui.form;
        var formSelects = layui.formSelects;

        var user_id     = $('#user_id').val();
        var role_ids    = formSelects.value('select_role', 'valStr');

        // 表单提交样例
        form.on('submit(select-submit)', function(obj){

            var role_ids = formSelects.value('select_role', 'valStr');
            console.log(role_ids);

            $.ajax({
                url: '/AdminUser/adminUserAllotPro',
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {user_id:user_id, role_ids:role_ids},
                dataType: 'json',
                success: function(res){
                    if (res.code == 200) {

                        layer.msg('分配成功！');
                    } else {

                        layer.msg('分配失败！');
                    }
                }
                ,error: function() {
                    layer.msg('网络异常！');
                }
            });
            return false;
        });


    });
</script>
