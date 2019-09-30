<!--
* @Author: 天尽头流浪
* @Date:   2019-07-26 15:56:01
* @Last Modified by:   天尽头流浪
* @E-mail: 1270469522@qq.com
-->

<!DOCTYPE html>
<html>
<meta name="csrf-token" content="{{ csrf_token() }}">
<head>
    <!-- 加载公共文件：css -->
    @include('public/base_css')
    <!-- 树形组件专用样式 -->
    <link rel="stylesheet" href="{{ asset('layui/module/layui-treeTable/treetable.css') }}">
    <style>
        #search {
            height       : 30px;
            line-height  : 30px;
            padding      : 0 7px;
            border       : 1px solid #ccc;
            border-radius: 2px;
            outline      : none;
            margin-left  : 10px;
        }

        #search:focus {
            border-color: #009E94;
        }
    </style>
</head>
<body>

    <!-- 条件搜索 -->
    <input id="search" type="text">
    <button class="layui-btn layui-btn-sm" id="btn-search"><i class="layui-icon">&#xe615;</i>@lang('adminPower.search.search')</button>

    <table id="dataTable" class="layui-table" lay-filter="dataTable"></table>

    <!-- layui模版 -->
    @include('AdminPower/_adminPowerListsTpl')

</body>
<!-- 加载公共文件：js -->
@include('public/base_script')

<script>
    layui.config({
        base: "{{ asset('layui/module') }}"
    }).extend({
        treetable: '/layui-treeTable/treetable'
    }).use(['layer', 'form', 'table', 'treetable'], function () {
        var $         = layui.jquery;
        var table     = layui.table;
        var layer     = layui.layer;
        var form      = layui.form;
        var treetable = layui.treetable;

        // 渲染表格
        var renderTable = function () {
            layer.load(2);
            treetable.render({
                treeColIndex: 2, //树形展开放在第几列
                treeSpid: 0, //根目录pid
                treeIdName: 'id', //自增ID
                treePidName: 'parent_id', //父ID
                treeDefaultClose: true,
                treeLinkage: false,
                elem: '#dataTable',
                url: '/AdminPower/adminPowerListsData',
                toolbar: '#toolbarDemo',
                cols: [[
                    {type:'numbers',    fixed:'left'},
                    {field:'order_id', title: "@lang('adminPower.field.order')", width:80, unresize: true, edit: 'text'},
                    {field:'power_name', title: "@lang('adminPower.field.name')"},
                    {field:'power_url',   title:"@lang('adminPower.field.routes')"},
                    {field:'power_desc',  title:"@lang('adminPower.field.desc')"},
                    {field:'power_type', title: "@lang('adminPower.field.type')",
                        templet: function(d){
                            if(d.power_type == 11){
                                return "<span class='layui-btn layui-btn-xs layui-bg-cyan'>@lang('adminPower.field.side_menu')</span>";
                            } else if (d.power_type == 12) {
                                return "<span class='layui-btn layui-btn-xs layui-bg-blue'>@lang('adminPower.field.page_elements')</span>";
                            } else if (d.power_type == 13) {
                                return "<span class='layui-btn layui-btn-xs layui-bg-orange'>@lang('adminPower.field.specific_function')</span>";
                            }
                        }
                    },
                    {field:'status',      title:"@lang('adminPower.field.status')",      width:90,  unresize: true, templet: '#switchTpl'},
                    {field:'create_time', title:"@lang('adminPower.field.create_time')", width:180, unresize: true},
                    {field:'update_time', title:"@lang('adminPower.field.update_time')", width:180, unresize: true},
                    {fixed:'right',       title:"@lang('adminPower.field.operation')",   width:180, toolbar: '#barDemo'},
                ]],
                done: function () {
                    layer.closeAll('loading');
                }
            });
        };

        renderTable();

        //搜索
        $('#btn-search').click(function () {
            var keyword = $('#search').val();
            var searchCount = 0;
            $('#dataTable').next('.treeTable').find('.layui-table-body tbody tr td').each(function () {
                $(this).css('background-color', 'transparent');
                var text = $(this).text();
                if (keyword != '' && text.indexOf(keyword) >= 0) {
                    $(this).css('background-color', 'rgba(250,230,160,0.5)');
                    if (searchCount == 0) {
                        treetable.expandAll('#dataTable');
                        $('html,body').stop(true);
                        $('html,body').animate({scrollTop: $(this).offset().top - 150}, 500);
                    }
                    searchCount++;
                }
            });
            if (keyword == '') {
                layer.msg("@lang('adminPower.search.please')", {icon: 5});
            } else if (searchCount == 0) {
                layer.msg("@lang('adminPower.search.empty')", {icon: 5});
            }
        });

        //表格头部 -- 添加
        table.on('toolbar(dataTable)', function(obj){

            var checkStatus = table.checkStatus(obj.config.id);

            switch(obj.event){
                case 'addData':
                    var that = this;
                    var content = '/AdminPower/adminPowerSave';
                    layer_open(that, content);
                    break;
                case 'expand':
                    treetable.expandAll('#dataTable');
                    break;
                case 'fold':
                    treetable.foldAll('#dataTable');
                    break;
            };
        });

        //监听单元格编辑(实时编辑排序)
        table.on('edit(dataTable)', function(obj){

            var url = "/AdminPower/adminPowerSavePro";
            var paramJson = JSON.stringify({id:obj.data.id,order_id:obj.value});
            //使用ajax向后端发送数据
            ajax_fun(url, paramJson, 'order');
        });

        //监听状态操作
        form.on('switch(status)', function(obj){

            layer.tips(obj.elem.checked, obj.othis);

            var id = this.value;
            var status = obj.elem.checked === true ? 1 : 0;

            var url = "/AdminPower/adminPowerSavePro";
            var paramJson = JSON.stringify({id:id,status:status});
            //使用ajax向后端发送数据
            ajax_fun(url, paramJson, 'switch');
        });

        //监听工具条（查看、编辑、删除）
        table.on('tool(dataTable)', function(obj){

            var data = obj.data;

            //编辑
            if(obj.event === 'edit'){
                var that = this;
                var content = "/AdminPower/adminPowerSave?id=" + data.id;;
                layer_open(that, content, "@lang('adminPower.edit')");
            //删除
            } else if(obj.event === 'del'){

                layer.confirm("@lang('adminPower.message.is_delete')", function(index){
                    layer.load(2);
                    var url = "/AdminPower/adminPowerSavePro";
                    var paramJson = JSON.stringify({id:data.id, is_del:1});
                    //使用ajax向后端发送数据
                    ajax_fun(url, paramJson, 'del');

                    //节点删除
                    obj.del();
                    layer.closeAll('loading');
                    layer.close(index);
                });
            }
        });

        /**
         * [ajax_fun 向后端发起Ajax请求]
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
                            layer.msg("@lang('adminPower.message.delete_success')");
                        } else if (type == 'switch'){
                            layer.msg("@lang('adminPower.message.switch_success')");
                        } else if (type == 'order') {
                            layer.msg("@lang('adminPower.message.order_success')");
                            renderTable();
                        } else {
                            layer.msg(res.msg);
                        }
                    }
                },error: function () {
                    layer.msg("@lang('adminPower.message.network_error')");
                }
            });
        }
    });
</script>
</html>
