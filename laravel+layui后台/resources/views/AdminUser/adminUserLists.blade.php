<!--
* @Author: 天尽头流浪
* @Date:   2019-07-27 17:38:47
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
<body>

    <!-- 表格上方：条件搜索 -->
    @include('AdminUser/_adminUserListsCheck')

    <!-- 表格主体：数据表格 -->
    <table class="layui-hide" id="dataTable" lay-filter="dataTable"></table>

    <!-- 操作按钮模版 -->
    @include('AdminUser/_adminUserListsTpl')

</body>
<!-- 加载公共文件：js -->
@include('public/base_script')

<script>

    layui.use(['table', 'form'], function(){

        //实例化组件
        var $      = layui.$
        var table  = layui.table
        var form   = layui.form

        //加载表格数据
        table.render({
            elem: '#dataTable'
            ,url:'/AdminUser/adminUserListsData'
            ,cellMinWidth: 80
            ,method:'post'
            ,where: {_token: $('meta[name="csrf-token"]').attr('content')}
            ,toolbar: '#toolbarDemo'
            ,title: "@lang('menu.administrator')"
            ,cols: [[
                {type:'numbers',    fixed:'left'}
                ,{field:'id',          title:'ID', width:80, unresize: true, sort: true}
                ,{field:'username',    title:"@lang('adminUser.field.username')"}
                ,{field:'password',    title:"@lang('adminUser.field.password')",
                    templet: function(d){
                        return "******"
                    }
                }
                ,{field:'status',      title:"@lang('adminUser.field.status')",      width:90,  unresize: true, templet: '#switchTpl'}
                ,{field:'create_time', title:"@lang('adminUser.field.create_time')", width:180, unresize: true}
                ,{field:'update_time', title:"@lang('adminUser.field.update_time')", width:180, unresize: true}
                ,{fixed:'right',       title:"@lang('adminUser.field.operation')",   width:230, toolbar: '#barDemo'}
            ]]
            ,page: true
            ,id:'testReload'
            ,limits:[10,20]
            ,limit: 10
        });

        //实现搜索功能（重载）
        var active = {
            reload: function () {
                var id        = $('#id').val();//ID
                var username  = $('#username').val();//角色名称
                var password  = $('#password').val();//角色描述
                var status    = $('#status').val();//状态
                var startDate = $('#startDate').val();//开始日期
                var endDate   = $('#endDate').val();//结束日期

                //执行重载
                table.reload('testReload', {
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }, page: {
                        curr: 1 //重新从第 1 页开始
                    }, where: {
                        id       : id,
                        username : username,
                        password : password,
                        status   : status,
                        startDate: startDate,
                        endDate  : endDate,
                    }//这里传参  向后台
                    , url: '/AdminUser/adminUserListsData'//后台接口路径
                    , method: 'post'
                });
            }
            ,reset: function () {
                $("input[type='text']").val('');
                $("select").find('option').attr("selected", false);
            }
        };

        //这个是用于创建点击事件的实例（搜索、重置）
        $('.searchTable .layui-btn').on('click', function (){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });

        //监听状态操作
        form.on('switch(status)', function(obj){

            layer.tips(this.value + ' ' + this.name + '：'+ obj.elem.checked, obj.othis);

            var id = this.value;
            var status = obj.elem.checked === true ? 1 : 0;
            var url = "/AdminUser/adminUserSavePro";
            var paramJson = JSON.stringify({id:id,status:status});

            if (id == 1) return false;

            //使用ajax向后端发送数据
            ajax_fun(url, paramJson, 'switch');

        });

        //表格头部 -- 工具栏事件（下载、导出）
        table.on('toolbar(dataTable)', function(obj){

            var checkStatus = table.checkStatus(obj.config.id);

            switch(obj.event){
                case 'addData':
                    var that = this;
                    var content = '/AdminUser/adminUserSave';
                    layer_open(that, content);
                    break;
                case 'getCheckData':
                    var data = checkStatus.data;
                    layer.alert(JSON.stringify(data));
                    break;
            };
        });

        //监听工具条（查看、编辑、删除）
        table.on('tool(dataTable)', function(obj){

            var data = obj.data;

            if (data.id == 1) return false;

            switch(obj.event){
                //分配角色
                case 'allot':
                    var that = this;
                    var content = "/AdminUser/adminUserAllot?id=" + data.id;
                    layer_open(that, content, "@lang('adminUser.tpl.allot')");
                    break;

                //编辑
                case 'edit':
                    var that = this;
                    var content = "/AdminUser/adminUserSave?id=" + data.id;
                    layer_open(that, content, "@lang('adminUser.tpl.edit')");
                    break;

                //删除
                case 'del':
                    layer.confirm("@lang('adminUser.message.is_delete')", function(index){
                        layer.load(2);
                        var url = "/AdminUser/adminUserSavePro";
                        var paramJson = JSON.stringify({id:data.id, is_del:1});

                        //使用ajax向后端发送数据
                        ajax_fun(url, paramJson, 'del');

                        //节点删除
                        obj.del();
                        layer.closeAll('loading');
                        layer.close(index);
                    });
                    break;
            }
        });

        /**
         * [ajax_fun Ajax向后端请求]
         * @param  {String} url       [description]
         * @param  {String} paramJson [description]
         * @param  {String} type      [description]
         * @return {[type]}           [description]
         */
        function ajax_fun (url = '', paramJson = '', type = '') {

            if (url == '' || paramJson == '') {

                layer.msg('error'); return false;
            }

            $.ajax({
                url : url,
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
                        if (type == 'del') {
                            layer.msg("@lang('adminUser.message.delete_success')");
                        } else if (type == 'switch'){
                            layer.msg("@lang('adminUser.message.switch_success')");
                        } else {
                            layer.msg(res.msg);
                        }
                    }
                },error: function () {
                    layer.msg("@lang('adminUser.message.network_error')");
                }
            });
        }
    });
</script>
</html>
