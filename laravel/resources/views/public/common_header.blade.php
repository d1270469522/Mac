<!--
* @Author: 天尽头流浪
* @Date:   2019-07-24 15:12:42
* @Last Modified by:   天尽头流浪
* @E-mail: 1270469522@qq.com
-->

<div class="layui-header">

    <div class="layui-logo">
        <i class="layui-icon" id="animation-left-nav" data-title="@lang('common.header.animation')" style="position: absolute; left: 20px; font-size: 20px; color: #FFB800;">&#xe668;</i>
        @lang('common.header.logo')
    </div>

    <!-- 头部区域（可配合layui已有的水平导航） -->
    <ul class="layui-nav layui-layout-left">

        <li class="layui-nav-item"><a href="/">@lang('common.header.home')</a></li>
        <li class="layui-nav-item">
            <a href="javascript:;">@lang('common.header.language')</a>
            <dl class="layui-nav-child">
                <dd><a lang="cn" href="{{ url('/switchLocale/zh') }}">@lang('common.header.zh')</a></dd>
                <dd><a lang="en" href="{{ url('/switchLocale/en') }}">@lang('common.header.en')</a></dd>
            </dl>
        </li>
    </ul>

    <ul class="layui-nav layui-layout-right">
        <li class="layui-nav-item">
            <a href="javascript:;">
                <img src="@if(!empty(Illuminate\Support\Facades\Auth::user()->img_url)) {{ asset(Illuminate\Support\Facades\Auth::user()->img_url) }} @else {{ asset('uploads/adminUserImg/no.jpg') }} @endif" class="layui-nav-img">{{ Illuminate\Support\Facades\Auth::user()->username }}
            </a>
            <dl class="layui-nav-child">
                <dd><a href="/AdminUser/adminUserInfo">@lang('common.header.basicInfo')</a></dd>
                <dd><a href="javascript:;" onclick="changePassword()">@lang('common.header.changePassword')</a></dd>
                <dd><a href="/logout">@lang('common.header.logout')</a></dd>
            </dl>
        </li>
    </ul>
</div>

<script type="text/javascript">

    /**
     * [changePassword 修改密码]
     * @return {[type]} [description]
     */
    function changePassword ()
    {
        layui.use('jquery', function(){

            var $ = layui.jquery

            var that = this;
            var content = "/AdminUser/changePassword";
            layer_open(that, content, '修改密码');
        });
    }
</script>
