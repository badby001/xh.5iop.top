<div class="layui-header">
    <div class="layui-logo">
        <img src="/images/favicon.ico"/>
        <cite>&nbsp;{!! site()['title'] !!}</cite>
    </div>
    <ul class="layui-nav layui-layout-left">
        <li class="layui-nav-item" lay-unselect>
            <a ew-event="flexible" title="侧边伸缩"><i class="layui-icon layui-icon-shrink-right"></i></a>
        </li>
        <li class="layui-nav-item" lay-unselect>
            <a ew-event="refresh" title="刷新"><i class="layui-icon layui-icon-refresh-3"></i></a>
        </li>
    </ul>
    <ul class="layui-nav layui-layout-right">
        <li class="layui-nav-item" lay-unselect>
            <a ew-event="note" title="便签"><i class="layui-icon layui-icon-note"></i></a>
        </li>
        <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <a ew-event="fullScreen" title="全屏"><i class="layui-icon layui-icon-screen-full"></i></a>
        </li>
        <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <a ew-event="lockScreen" title="锁屏"><i class="layui-icon layui-icon-password"></i></a>
        </li>
        <li class="layui-nav-item" lay-unselect>
            <a>
                <img src="/images/face.jpg" class="layui-nav-img">
                <cite>{{_admName()}}</cite>
            </a>
            <dl class="layui-nav-child">
                <dd lay-unselect><a ew-href="/sys/pages/admPwd">安全设置</a></dd>
                <hr>
                <dd lay-unselect><a ew-event="logout" data-url="/sys/logout">退出</a></dd>
            </dl>
        </li>
        <li class="layui-nav-item" lay-unselect>
            <a ew-event="theme" title="主题"><i class="layui-icon layui-icon-more-vertical"></i></a>
        </li>
    </ul>
</div>
