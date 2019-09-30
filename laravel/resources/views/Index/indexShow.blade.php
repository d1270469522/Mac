<!--
* @Author: 天尽头流浪
* @Date:   2019-09-11 11:12:38
* @Last Modified by:   天尽头流浪
* @E-mail: 1270469522@qq.com
-->


<!DOCTYPE html>
<html>
<head>
    {{-- 加载公共文件：css --}}
    @include('public/base_css')
</head>
<body class="layui-layout-body">
    <div class="layui-layout layui-layout-admin">
        <div class="layui-fluid">
            <div class="layui-row layui-col-space20">
                <div class="layui-col-md1"></div>
                <div class="layui-col-md4">
                    <table class="layui-table" lay-skin="line"  lay-size="sm">
                        <thead>
                            <tr>
                                <th>账号</th>
                                <th>IP</th>
                                <th>结果</th>
                                <th>时间</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($login_res)
                                @foreach($login_res as $key => $value)
                                    <tr>
                                        <td>{{$value['username']}}</td>
                                        <td>{{$value['login_ip']}}</td>
                                        <td>
                                            @if($value['login_res'] == 1)
                                                成功
                                            @else
                                                失败
                                            @endif
                                        </td>
                                        <td>{{$value['create_time']}}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="layui-col-md2"></div>
                <div class="layui-col-md4">
                    <br><br>
                    1、从此以后提及你，无风无雨也无晴!<br>
                    2、你是那些年月里最烈的酒，我是真的认真醉过!<br>
                    3、其实你不用这么冷淡，我从未想过要纠缠!<br>
                    4、倘若深情被辜负，从此，你我永是陌路!<br>
                    5、说不出的话叫心事，留不住的人叫故事!<br>
                    6、我敬往事一杯酒，故事与你我不强留!<br>
                    7、他说别喝酒、胃会痛，我说放下酒杯、心会痛!<br>
                    8、仅以你消逝的一面，足够我享用一生!<br>
                    9、只因密度不同，波罗的海和北海相遇，却永不融合，就像我遇见了你!<br>
                    10、情话是我抄的，想说给你听是真的!<br>
                    11、一夕枯荣如朝露，一念劫缘终归尘!
                </div>
                <div class="layui-col-md1"></div>
            </div>
        </div>
    </div>
</body>

{{-- 加载公共文件：js --}}
@include('public/base_script')
</html>
