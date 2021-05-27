/*
* @Author: 天尽头流浪
* @Date:   2019-07-27 11:22:19
* @Last Modified by:   天尽头流浪
* @Last Modified time: 2019-09-11 14:50:16
*/

//全局变量，左上角的缩进按钮状态：0未缩进；1缩进
var sideStatus = 0;

//导航收缩，以及打开页面
layui.use(['element','layer'], function(){

    var $       = layui.jquery
    var element = layui.element //Tab的切换功能，切换事件监听等，需要依赖element模块
    var layer   = layui.layer

    //左上角的缩进按钮图标，提示语
    $('#animation-left-nav').hover(function(){
        layer.tips($(this).data('title'), '#animation-left-nav', {tips:[3,'#FF8000']});
    },function(){
        layer.closeAll('tips');
    });

    //通过左上角的缩进按钮图标id来触发左侧导航栏收缩功能动画效果
    $('#animation-left-nav').click(function(){
        //这里定义一个全局变量来方便判断动画收缩的效果,也就是放在最外面
        if (sideStatus == 0) {
            //向左收缩
            spread_left();
        } else {
            //向右展开
            spread_right();
        }
    });

    //当左侧菜单收缩起来之后，滑上显示提示语
    $(".layui-side").find('.layui-icon').hover(function(){
        //显示提示语
        layer.tips($(this).next().text(), $(this), {tips:[2,'#009688']});
    },function(){
        //关闭提示语
        layer.closeAll('tips');
    });


    //Tab触发事件
    var active = {
        tabAdd: function(title, content, id){
            //新增一个Tab项
            element.tabAdd('demo', {
                id     : id,
                title  : title,
                content: content
            })
        }
        ,tabDelete: function(id){
            //删除指定Tab项
            element.tabDelete('demo', id);
        }
        ,tabChange: function(id){
            //切换到指定Tab项
            element.tabChange('demo', id);
        }
    };

    //自动加载：Tab页面，iframe展示首页内容
    active.tabAdd("首页", setContent(), 999);
    active.tabChange(999);
    FrameWH(); //自动调节宽高

    //点击左侧菜单的时候，添加Tab标签，并打开iframe页面
    $('.site-demo-active').on('click', function(){

        var id    = $(this).data('id')
        var src   = $(this).data('src')
        var title = $(this).data('title')

        //否则判断该tab项是否以及存在
        //初始化一个标志，为false说明未打开该tab项 为true则说明已有
        var isData = false;

        $.each($(".layui-tab-title li[lay-id]"), function () {
            //如果点击左侧菜单栏所传入的id 在右侧tab项中的lay-id属性可以找到，则说明该tab项已经打开
            if ($(this).attr("lay-id") == id) {
                isData = true;
            }
        })
        if (isData == false) {
            //标志为false 新增一个tab项
            active.tabAdd(title, setContent(id, src), id);
        }

        //最后不管是否新增tab，最后都转到要打开的选项页面上
        active.tabChange(id);
        FrameWH(); //自动调节宽高
    });

    //Tab对应的内容，iframe加载
    function setContent (id = 999, src = '/indexShow') {

        return content = '<iframe data-frameid="'+id+'" scrolling="auto" frameborder="0" src="'+src+'" style="width:100%;height:100%;"></iframe>';
    }

    //刷新当前iframe页面
    $(".refresh").on("click",function(){

        $(".layui-tab-content .layui-show").find("iframe")[0].contentWindow.location.reload(true);
    })
});

/**
 * [FrameWH 动态变动页面内容的宽和高]
 * @param {Number} w_offset [description]
 */
function FrameWH(w_offset = 200, h_offset = 156) {

    layui.use([],function(){

        var $ = layui.jquery
        var w = $(window).width() - w_offset;
        var h = $(window).height() - h_offset;

        $("iframe").css("width", w + "px");
        $("iframe").css("height", h + "px");
    });
}

/**
 * [spread_left 向左收缩]
 *
 * 左侧菜单全部关闭
 * 自动调节宽高
 * @return {[type]} [description]
 */
function spread_left ()
{
    layui.use([],function(){

        var $ = layui.jquery

        $(".layui-side").animate({width:'50px'});
        $(".layui-tab").animate({left:'50px'});
        $(".layui-footer").animate({left:'50px'});
        //改变左上角的图标
        $('#animation-left-nav').html('&#xe66b;');
        //左侧菜单全部关闭
        $(".layui-side").find('.layui-nav-item').removeClass('layui-nav-itemed');
        //自动调节宽高
        FrameWH(50);
    });
    sideStatus = 1;
}

/**
 * [spread_right 向右展开]
 *
 * 自动调节宽高
 * @return {[type]} [description]
 */
function spread_right ()
{
    layui.use([],function(){

        var $ = layui.jquery

        $(".layui-side").animate({width:'200px'});
        $(".layui-tab").animate({left:'200px'});
        $(".layui-footer").animate({left:'200px'});
        //改变左上角的图标
        $('#animation-left-nav').html('&#xe668;');
        //自动调节宽高
        FrameWH();
    });
    sideStatus = 0;
}


/**
 * [日期插件 - 开始 | 结束]
 * @param  {[type]}     [description]
 * @return {[type]}     [description]
 */
layui.use('laydate', function(){

    var laydate = layui.laydate //日期

    //日期选择框 -- 开始时间
    laydate.render({
        elem: '#startDate'
        ,type: 'datetime'
    });

    //日期选择框 -- 结束时间
    laydate.render({
        elem: '#endDate'
        ,type: 'datetime'
    });
});



/**
 * [上传图片]
 * @param  {[type]}  [description]
 * @param  {[type]}  [description]
 * @param  {[type]}  [description]
 * @return {[type]}  [description]
 */
layui.use('upload', function () {

    var $ = layui.jquery
    ,upload = layui.upload;

    //普通图片上传
    var uploadInst = upload.render({
        elem: '#img_show'
        ,url: '/uploadImg'
        ,size: 1024 //限制文件大小，单位 KB
        ,headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        ,before: function(obj){
            //预读本地文件示例，不支持ie8
            obj.preview(function(index, file, result){
                $('#img_show').attr('src', result); //图片链接（base64）
            });
        }
        ,done: function(res){

            layer.msg(res.msg);

            if (res.code == 200) {

                $('#img_url').val(res.data.path);
                $('#img_err_msg').html('<span style="color: #339900;">' + res.msg + '</span>');
            } else {

                $('#img_err_msg').html('<span style="color: #FF5722;">' + res.msg + '</span>');
            }
        }
        ,error: function(){

            //演示失败状态，并实现重传
            $('#img_err_msg').html('<span style="color: #FF5722;">failure</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
            $('#img_err_msg').find('.demo-reload').on('click', function(){
                uploadInst.upload();
            });
        }
    });
})


/**
 * [layer_open 弹窗]
 * @param  {[type]} that    [description]
 * @param  {String} content [description]
 * @return {[type]}         [description]
 */
function layer_open (that, content = '', title = '添加', area = '400px')
{

    layui.use(['layer'], function(){

        var layer = layui.layer;

        //多窗口模式，层叠置顶
        layer.open({

            type       : 2 //此处以iframe举例
            ,title     : title
            ,area      : area //宽高
            ,offset    : '200px'//位置
            ,skin      : 'layui-layer-lan' // 弹窗的样式class
            ,content   : content
            ,btnAlign  : 'r'//按钮右对齐。默认值，不用设置
            ,shade     : 0.5 //遮罩层
            ,shadeClose: true //点击遮罩层外面的灰色，关闭弹窗
            ,time      : 0  //5000，即代表5秒后自动关闭
            ,anim      : 0 //弹出动画
            ,isOutAnim : true //关闭动画
            ,maxmin    : true //最大化，最小化
            ,fixed     : true //即鼠标滚动时，层是否固定在可视区域。如果不想，设置fixed: false即可
            ,resize    : true //是否允许拉伸
            ,zIndex    : layer.zIndex //重点1

            ,success: function(layero, index){
                layer.setTop(layero); //重点2  窗口置顶
                layer.iframeAuto(index); //重点2  高自适应
            }
        });
    })
}
