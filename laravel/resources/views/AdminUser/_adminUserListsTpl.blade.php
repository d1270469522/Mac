<!--
* @Author: 天尽头流浪
* @Date:   2019-08-12 15:25:27
* @Last Modified by:   天尽头流浪
* @E-mail: 1270469522@qq.com
-->

<!-- 模版：表格头部：添加、下载、导出 -->
<script type="text/html" id="toolbarDemo">
    <div class="layui-btn-container">
        <button class="layui-btn layui-btn-sm" lay-event="addData"><i class="layui-icon">&#xe654;</i>@lang('adminUser.tpl.add')</button>
    </div>
</script>

<!-- 模版：表格右侧：分配、编辑、删除 -->
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs layui-btn-normal" lay-event="allot">@lang('adminUser.tpl.allot')</a>
    <a class="layui-btn layui-btn-xs" lay-event="edit"><i class="layui-icon">&#xe642;</i>@lang('adminUser.tpl.edit')</a>
    <a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del"><i class="layui-icon">&#xe640;</i>@lang('adminUser.tpl.delete')</a>
</script>

<!-- 模版：状态切换 -->
<script type="text/html" id="switchTpl">
    <input type="checkbox" name="status" value="@{{d.id}}" lay-skin="switch" lay-text="@lang('adminUser.tpl.valid')|@lang('adminUser.tpl.invalid')" lay-filter="status" @{{ d.status == 1 ? 'checked' : '' }} @{{ d.id == 1 ? 'disabled' : '' }}>
</script>
