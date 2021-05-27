<!--
* @Author: 天尽头流浪
* @Date:   2019-08-02 14:51:03
* @Last Modified by:   天尽头流浪
* @E-mail: 1270469522@qq.com
-->

<!-- 模版：表格头部添加、下载、导出 -->
<script type="text/html" id="toolbarDemo">
    <div class="layui-btn-container">
        <button class="layui-btn layui-btn-sm" lay-event="addData"><i class="layui-icon">&#xe654;</i>@lang('adminPower.tpl.add')</button>
        <button class="layui-btn layui-btn-sm" lay-event="expand">@lang('adminPower.tpl.expand')</button>
        <button class="layui-btn layui-btn-sm" lay-event="fold">@lang('adminPower.tpl.fold')</button>
    </div>
</script>

<!-- 模版：表格右侧的编辑、删除 -->
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="edit"><i class="layui-icon">&#xe642;</i>@lang('adminPower.tpl.edit')</a>
    <a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del"><i class="layui-icon">&#xe640;</i>@lang('adminPower.tpl.delete')</a>
</script>

<!-- 模版：状态切换 -->
<script type="text/html" id="switchTpl">
    <input type="checkbox" name="status" value="@{{d.id}}" lay-skin="switch" lay-text="@lang('adminPower.tpl.valid')|@lang('adminPower.tpl.invalid')" lay-filter="status" @{{ d.status == 1 ? 'checked' : '' }}>
</script>
