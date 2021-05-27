<!--
* @Author: 天尽头流浪
* @Date:   2019-08-01 16:53:21
* @Last Modified by:   天尽头流浪
* @E-mail: 1270469522@qq.com
-->

<!-- 模版：表格头部下载、导出 -->
<script type="text/html" id="toolbarDemo">
    <div class="layui-btn-container">
        <button class="layui-btn layui-btn-sm" lay-event="addData"><i class="layui-icon">&#xe654;</i>@lang('adminRole.tpl.add')</button>
    </div>
</script>

<!-- 模版：表格右侧的查看编辑 -->
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs layui-btn-normal" lay-event="allot">@lang('adminRole.tpl.allot')</a>
    <a class="layui-btn layui-btn-xs" lay-event="edit"><i class="layui-icon">&#xe642;</i>@lang('adminRole.tpl.edit')</a>
    <a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del"><i class="layui-icon">&#xe640;</i>@lang('adminRole.tpl.delete')</a>
</script>

<!-- 模版：状态切换 -->
<script type="text/html" id="switchTpl">
    <input type="checkbox" name="status" value="@{{d.id}}" lay-skin="switch" lay-text="@lang('adminRole.tpl.valid')|@lang('adminRole.tpl.invalid')" lay-filter="status" @{{ d.status == 1 ? 'checked' : '' }} @{{ d.id == 1 ? 'disabled' : '' }}>
</script>
