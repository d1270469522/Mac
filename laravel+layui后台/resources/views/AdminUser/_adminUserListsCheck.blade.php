<!--
* @Author: 天尽头流浪
* @Date:   2019-08-12 15:25:38
* @Last Modified by:   天尽头流浪
* @E-mail: 1270469522@qq.com
-->

<!-- 表格上方：条件搜索 -->
<div class="searchTable">
    <div class="layui-fluid">

        <form class="layui-form">
            <div class="layui-row">

                <!-- ID -->
                <div class="layui-col-md3">
                    <label class="layui-form-label">ID</label>
                    <div class="layui-input-block">
                        <input type="text" class="layui-input" name="id" id="id">
                    </div>
                </div>

                <!-- 账号 -->
                <div class="layui-col-md3">
                    <label class="layui-form-label">@lang('adminUser.conditions.username')</label>
                    <div class="layui-input-block">
                        <input type="text" class="layui-input" name="username" id="username">
                    </div>
                </div>

                <!-- 状态 -->
                <div class="layui-col-md3">
                    <label class="layui-form-label">@lang('adminUser.conditions.status.status')</label>
                    <div class="layui-input-block">
                        <select name="status" id="status" lay-verify="required">
                            <option value="">@lang('adminUser.conditions.status.all')</option>
                            <option value="1">@lang('adminUser.conditions.status.valid')</option>
                            <option value="0">@lang('adminUser.conditions.status.invalid')</option>
                        </select>
                    </div>
                </div>

                <!-- 开始日期 -->
                <div class="layui-col-md3">
                    <label class="layui-form-label">@lang('adminUser.conditions.startDate')</label>
                    <div class="layui-input-block">
                        <input type="text" class="layui-input" name="startDate" id="startDate">
                    </div>
                </div>
            </div>

            <br>

            <div class="layui-row">
                <!-- 结束日期 -->
                <div class="layui-col-md3">
                    <label class="layui-form-label">@lang('adminUser.conditions.endDate')</label>
                    <div class="layui-input-block">
                        <input type="text" class="layui-input" name="endDate" id="endDate">
                    </div>
                </div>
            </div>
        </form>

        <br>

        <!-- 搜索、重置 -->
        <div class="layui-row">
            <div class="layui-col-md3" style="margin-left: 110px;">
                <button class="layui-btn" data-type="reload"><i class="layui-icon">&#xe615;</i>@lang('adminUser.conditions.search')</button>
                <button class="layui-btn layui-btn-primary" data-type="reset"><i class="layui-icon">&#xe669;</i>@lang('adminUser.conditions.reset')</button>
            </div>
        </div>

    </div>
</div>
