<!--
* @Author: 天尽头流浪
* @Date:   2019-07-24 15:13:00
* @Last Modified by:   天尽头流浪
* @E-mail: 1270469522@qq.com
-->

<!-- 公共部分：左侧菜单 -->
<div class="layui-side layui-bg-black">
    <div class="layui-side-scroll">
        <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
        <ul class="layui-nav layui-nav-tree" lay-shrink="all">

            <?php $menu_lists = \App\Service\UserService::getMenuLists();?>

            @foreach($menu_lists as $key => $value)
                @if($value['parent_id'] == 0)
                    <li class="layui-nav-item <?php echo Config::get('currentMenu') == $value['power_name'] ? 'layui-nav-itemed' : ''; ?>">
                        <a href="javascript:;" onclick="spread_right()">
                            <i class="layui-icon {{ $value['icon'] }}" style="font-size: 20px; color: #1E9FFF;"></i>&nbsp;&nbsp;
                            <span>{{ __('menu.'.$value['power_name']) }}</span>
                        </a>
                        <dl class="layui-nav-child">
                            @foreach($menu_lists as $k => $v)
                                @if($v['parent_id'] == $value['id'])
                                    <dd style="padding-left: 32px;">
                                        <a href="javascript:;"  class="site-demo-active" data-id="{{ $v['id'] }}" data-title="{{ __('menu.'.$v['power_name']) }}" data-src="{{ $v['power_url'] }}" data-type="tabAdd">{{ __('menu.'.$v['power_name']) }}</a>
                                    </dd>
                                @endif
                            @endforeach
                        </dl>
                    </li>
                @endif
            @endforeach

        </ul>
    </div>
</div>

<!-- 公共部分：点击左侧菜单打开页面，头部tab展示 -->
<div class="layui-tab" lay-filter="demo" lay-allowclose="true" style="position: fixed; top: 50px; left: 200px; width: 100%; z-index: 999;">
    <ul class="layui-tab-title"></ul>
    <div class="layui-tab-content"></div>
</div>

<!-- 公共部分：右上角的刷新，只刷新当前页面(iframe) -->
<button type="button" class="layui-btn layui-btn-sm layui-btn-normal refresh" style="position: fixed; top: 65px; right: 40px; z-index: 999;">
    <i class="layui-icon layui-icon-refresh-3"></i>@lang('common.left.refresh')
</button>
