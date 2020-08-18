<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="keywords" content="{!! site()['keywords'] !!}">
    <meta name="description" content="{!! site()['description'] !!}">
    <link rel="shortcut icon" href="{!! site()['ico'] !!}" type="image/x-icon"/>
    <title>{!! site()['siteWebName'] !!}</title>
    @include('.sys.public.css')
    @include('.sys.public.js')
</head>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
    <!-- 头部 -->
    @include('.sys.public.head')

<!-- 侧边栏 -->
    @include('.sys.public.menu')

<!-- 主体部分 -->
    <div class="layui-body"></div>
    <!-- 底部 -->
</div>

<!-- 加载动画 -->
<div class="page-loading">
    <div class="rubik-loader"></div>
</div>

<!-- js部分 -->
<script>
    layui.use(['index'], function () {
        let $ = layui.jquery;
        let index = layui.index;

        // 默认加载主页
        index.loadHome({
            menuPath: '/sys/pages/handlingManagement/feedback',
            menuName: '<i class="layui-icon layui-icon-form"></i>'
        });

        // index.openTab({
        //     title: '订单列表',
        //     url: '/sys/pages/order/ordList',
        //     end: function() {
        //         // insTb.reload();
        //     }
        // });

    });
</script>
</body>
</html>
