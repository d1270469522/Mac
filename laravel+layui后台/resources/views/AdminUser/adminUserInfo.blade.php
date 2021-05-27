<!--
* @Author: 天尽头流浪
* @Date:   2019-08-16 14:10:04
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
    <div class="layui-layout layui-layout-admin">
        <!-- 公共html：加载头部 -->
        @include('public/common_header')
        <!-- 公共html：加载左侧 -->
        @include('public/common_left')
        <div class="layui-body">

            <br>
            <!-- 面包屑 -->
            <span class="layui-breadcrumb" style="padding-left: 20px;">
                <a>@lang('common.header.home')</a>
                <a><cite>@lang('common.header.basicInfo')</cite></a>
            </span>
            <hr>

            <form class="layui-form layui-form-pane" style="margin: 20px; width: 60%">

                <input type="hidden" class="layui-input" lay-verify="id" name="id" autocomplete="off" lay-verify="required" value="@if(isset($data)){{ $data['id'] }}@endif" disabled>

                <div class="layui-form-item">
                    <label class="layui-form-label">@lang('adminUser.info.username')</label>
                    <div class="layui-input-block">
                        <input type="text" class="layui-input" name="username" lay-verify="required" autocomplete="off" value="@if(isset($data)){{ $data['username'] }}@endif" disabled>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">@lang('adminUser.info.phone')</label>
                    <div class="layui-input-block">
                        <input type="tel" name="phone" lay-verify="required|phone" autocomplete="off" class="layui-input" value="@if(isset($data)){{ $data['phone'] }}@endif">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">@lang('adminUser.info.email')</label>
                    <div class="layui-input-block">
                        <input type="text" name="email" lay-verify="email" autocomplete="off" class="layui-input" value="@if(isset($data)){{ $data['email'] }}@endif">
                    </div>
                </div>


                <div class="layui-form-item">
                    <label class="layui-form-label">@lang('adminUser.info.img')</label>
                    <div class="layui-input-block">
                        <input type="hidden" name="img_url" id="img_url" value="@if(isset($data)){{ $data['img_url'] }}@endif">
                        <img class="layui-upload-img" id="img_show"
                            src="@if(isset($data) && !empty($data['img_url'])) {{ asset($data['img_url']) }} @else {{ asset('uploads/adminUserImg/no.jpg') }} @endif"
                            style="width: 120px; height: 120px; margin-left: 10px; cursor: pointer;">
                        <p id="img_err_msg" style="display: inline-block; margin-left: 10px;">
                            <span style="color: #B8B8B8;">@lang('adminUser.info.img_msg')</span>
                        </p>
                    </div>
                </div>

                <div class="layui-form">
                    <div class="layui-form-item" id="area-picker">
                        <div class="layui-form-label">@lang('adminUser.info.address.address')</div>
                        <div class="layui-input-inline" style="width: 200px;">
                            <select name="province" class="province-selector" data-value="@if(isset($data)){{ $data['province'] }}@endif">
                                <option value="">@lang('adminUser.info.address.province')</option>
                            </select>
                        </div>
                        <div class="layui-input-inline" style="width: 200px;">
                            <select name="city" class="city-selector" data-value="@if(isset($data)){{ $data['city'] }}@endif">
                                <option value="">@lang('adminUser.info.address.city')</option>
                            </select>
                        </div>
                        <div class="layui-input-inline" style="width: 200px;">
                            <select name="county" class="county-selector" data-value="@if(isset($data)){{ $data['county'] }}@endif">
                                <option value="">@lang('adminUser.info.address.county')</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">@lang('adminUser.info.address.detail')</label>
                    <div class="layui-input-block">
                        <input type="text" class="layui-input" autocomplete="off" name="address" value="@if(isset($data)){{ $data['address'] }}@endif">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">@lang('adminUser.info.hobby.hobby')</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="write" title="@lang('adminUser.info.hobby.write')" @if(isset($data) && in_array('write', explode(',', $data['hobby'])) ) checked @endif>
                        <input type="checkbox" name="read" title="@lang('adminUser.info.hobby.read')" @if(isset($data) && in_array('read', explode(',', $data['hobby'])) ) checked @endif>
                        <input type="checkbox" name="game" title="@lang('adminUser.info.hobby.game')" @if(isset($data) && in_array('game', explode(',', $data['hobby'])) ) checked @endif>
                    </div>
                </div>

                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">@lang('adminUser.info.desc')</label>
                    <div class="layui-input-block">
                        <textarea class="layui-textarea layui-hide" name="desc" lay-verify="content" id="LAY_demo_editor">@if(isset($data)){{ $data['desc'] }}@endif</textarea>
                    </div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="formSubmit">@lang('adminUser.info.submit')</button>
                        <button type="reset" class="layui-btn layui-btn-primary">@lang('adminUser.info.reset')</button>
                    </div>
                </div>

            </form>


        </div>
        <!-- 公共html：加载底部 -->
        @include('public/common_bottom')
    </div>

</body>
<!-- 加载公共文件：js -->
@include('public/base_script')

<script>

    /**
     * [住址 - 三级联动]
     * @param  {[type]}   [description]
     * @return {[type]}   [description]
     */
    layui.config({
        base: "{{ URL::asset('layui/module') }}"
    }).extend({
        layarea: '/layui-area/layarea',
    }).use('layarea', function () {
        var layer = layui.layer
            , layarea = layui.layarea;

        layarea.render({
            elem: '#area-picker',
            change: function (res) {
                //选择结果
                console.log(res);
            }
        });
    });


    //编辑信息
    layui.use(['jquery', 'layer', 'form', 'layedit'], function(){

        var $       = layui.$         //重点处
        var layer   = layui.layer     //弹窗
        var form    = layui.form      //表单
        var layedit = layui.layedit   //文本域

        //创建一个编辑器
        var editIndex = layedit.build('LAY_demo_editor');

        //自定义验证规则
        form.verify({
            id: function(value){
                if(value == ''){
                    return "@lang('adminUser.info.not_login')";
                }
            }
            ,pass: [
                /^[\S]{6,12}$/
                ,"@lang('adminUser.info.password_mistake')"
            ]
            ,content: function(value){
                layedit.sync(editIndex);
            }
        });

        //这里 return false;如果注释 Ajax不起效，执行表单提交
        form.on('submit(formSubmit)', function(data){

            var paramJson = JSON.stringify(data.field)

            var url = "/AdminUser/adminUserInfo";

            $.ajax({
                url : url,
                data : {paramJson},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type : 'POST',
                dataType : 'json',
                success: function (res) {

                    layer.msg(res.msg);
                },error: function () {

                    layer.msg("@lang('adminUser.message.network_error')");
                }
            });
            return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
        });
    });
</script>
</html>
