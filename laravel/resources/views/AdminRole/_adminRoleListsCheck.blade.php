<!--
* @Author: 天尽头流浪
* @Date:   2019-08-01 16:17:35
* @Last Modified by:   天尽头流浪
* @E-mail: 1270469522@qq.com
-->

<!-- 表格上方：条件搜索 -->
<div class="searchTable">
    <div class="layui-fluid">

        <form class="layui-form">
            <div class="layui-row">
                <div class="layui-col-md3">
                    <label class="layui-form-label">ID</label>
                    <div class="layui-input-block">
                        <input type="text" class="layui-input" name="id" id="id">
                    </div>
                </div>

                <div class="layui-col-md3">
                    <label class="layui-form-label">@lang('adminRole.conditions.name')</label>
                    <div class="layui-input-block">
                        <input type="text" class="layui-input" name="role_name" id="role_name">
                    </div>
                </div>

                <div class="layui-col-md3">
                    <label class="layui-form-label">@lang('adminRole.conditions.desc')</label>
                    <div class="layui-input-block">
                        <input type="text" class="layui-input" name="role_desc" id="role_desc">
                    </div>
                </div>

                <div class="layui-col-md3">
                    <label class="layui-form-label">@lang('adminRole.conditions.status.status')</label>
                    <div class="layui-input-block">
                        <select name="status" id="status" lay-verify="required">
                            <option value="">@lang('adminRole.conditions.status.all')</option>
                            <option value="1">@lang('adminRole.conditions.status.valid')</option>
                            <option value="0">@lang('adminRole.conditions.status.invalid')</option>
                        </select>
                    </div>
                </div>
            </div>

            <br>

            <div class="layui-row">
                <div class="layui-col-md3">
                    <label class="layui-form-label">@lang('adminRole.conditions.startDate')</label>
                    <div class="layui-input-block">
                        <input type="text" class="layui-input" name="startDate" id="startDate">
                    </div>
                </div>

                <div class="layui-col-md3">
                    <label class="layui-form-label">@lang('adminRole.conditions.endDate')</label>
                    <div class="layui-input-block">
                        <input type="text" class="layui-input" name="endDate" id="endDate">
                    </div>
                </div>
            </div>
        </form>

        <br>

        <div class="layui-row">
            <div class="layui-col-md3" style="margin-left: 110px;">
                <button class="layui-btn " data-type="reload"><i class="layui-icon">&#xe615;</i>@lang('adminRole.conditions.search')</button>
                <button class="layui-btn  layui-btn-primary" data-type="reset"><i class="layui-icon">&#xe669;</i>@lang('adminRole.conditions.reset')</button>
            </div>
        </div>

    </div>
</div>
