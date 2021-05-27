<!DOCTYPE html>
<html>
<meta name="csrf-token" content="{{ csrf_token() }}">
<head>
    <!-- 加载公共文件：css -->
    @include('public/base_css')
    <style type="text/css">
        .layui-form-item .layui-form-checkbox {
            margin-bottom: 0;
        }
    </style>
</head>
<body>

    <!-- 此扩展能递归渲染一个权限树，点击深层次节点，父级节点中没有被选中的节点会被自动选中，单独点击父节点，子节点会全部 选中/去选中 -->
    <form class="layui-form" style="height: 500px; margin-left: 10px;">

        <input type="hidden" name="role_id" id="role_id" value="{{ $role_id }}">
        <div class="layui-form-item">
            <div id="tree-div"></div>
        </div>
        <div class="layui-form-item" style="position: absolute; right: 10px; bottom: 0; ">
            <div class="layui-input-block">
                <button class="layui-btn" type="submit" lay-submit lay-filter="tree-submit">提交</button>
                <button class="layui-btn layui-btn-primary" type="reset">重置</button>
            </div>
        </div>
    </form>

</body>

<!-- 加载公共文件：js -->
@include('public/base_script')
<script type="text/javascript">
    // 需要查看相关DEMO的时候，搜索相关函数即可，比如：全选则搜索 checkAll，列表转树搜索 listConvert
    layui.config({
        base: "{{ URL::asset('layui/module') }}"
    }).extend({
        authtree: '/layui-tree/authtree',
    }).use(['jquery', 'authtree', 'form', 'layer'], function(){

        var $        = layui.jquery;
        var authtree = layui.authtree;
        var form     = layui.form;
        var layer    = layui.layer;
        var role_id  = $('#role_id').val();

        // 初始化
        $.ajax({
            url: '/AdminRole/adminRoleAllot',
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {role_id:role_id},
            dataType: 'json',
            success: function(res){
                if (res.code == 200) {
                    // 渲染时传入渲染目标ID，树形结构数据（具体结构看样例，checked表示默认选中），以及input表单的名字
                    authtree.render('#tree-div', res.data.trees, {});
                } else {
                    layer.msg("@lang('adminRole.message.request_error')");
                }
            }
            ,error: function() {
                layer.msg("@lang('adminRole.message.network_error')");
            }
        });

        // 表单提交样例
        form.on('submit(tree-submit)', function(obj){

            var power_ids = authtree.getChecked('#tree-div');

            $.ajax({
                url: '/AdminRole/adminRoleAllotPro',
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {role_id:role_id, power_ids:power_ids},
                dataType: 'json',
                success: function(res){

                    layer.msg(res.msg);
                }
                ,error: function() {
                    layer.msg("@lang('adminRole.message.network_error')");
                }
            });
            return false;
        });
    });

</script>
</html>
