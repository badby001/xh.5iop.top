<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{!! site()['siteWebName'] !!}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="keywords" content="{!! site()['keywords'] !!}">
    <meta name="description" content="{!! site()['description'] !!}">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    @include('.sys.public.css')
    <script type="text/javascript" src="{{asset('../../../../assets/libs/echarts/echarts.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('../../../../assets/libs/echarts/echarts.theme.js')}}"></script>
    <script type="text/javascript" src="{{asset('../../../../assets/libs/echarts/world/js/china.js')}}"></script>
    @include('.sys.public.js')
</head>
<body class="console console1 ok-body-scroll page-no-scroll">
<!-- 小球样式 -->
<div class="page-loading">
    <div class="ball-loader">
        <span></span><span></span><span></span><span></span>
    </div>
</div>
<div class="ok-body home">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-xs6 layui-col-md3">
            <div class="ok-card layui-card">
                <div class="ok-card-body p0 clearfix cart-data">
                    <div class="data-body">
                        <div class="media-cont">
                            <p class="tit">当日订单总额</p>
                            <h5 class="num">{!! $db['this_amount'] !!}</h5>
                        </div>
                        <div class="w-img" ok-pc-in-show>
                            <img src="/images/home-01.png" alt="当日利润"/>
                        </div>
                    </div>
                    <div id="echIncome" class="line-home-a"></div>
                </div>
            </div>
        </div>

        <div class="layui-col-xs6 layui-col-md3">
            <div class="ok-card layui-card">
                <div class="ok-card-body p0 clearfix cart-data">
                    <div class="data-body">
                        <div class="media-cont">
                            <p class="tit">当日下单数</p>
                            <h5 class="num">{!! $db['this_orders'] !!}</h5>
                        </div>
                        <div class="w-img" ok-pc-in-show>
                            <img src="/images/home-02.png" alt="当日订单数"/>
                        </div>
                    </div>
                    <div id="echGoods" class="line-home-a"></div>
                </div>
            </div>
        </div>

        <div class="layui-col-xs6 layui-col-md3">
            <div class="ok-card layui-card">
                <div class="ok-card-body p0 clearfix cart-data">
                    <div class="data-body">
                        <div class="media-cont">
                            <p class="tit">当日出行订单数</p>
                            <h5 class="num">{!! $db['this_toGoOrder'] !!}</h5>
                        </div>
                        <div class="w-img" ok-pc-in-show>
                            <img src="/images/home-03.png" alt="待出行订单"/>
                        </div>
                    </div>
                    <div id="echBlogs" class="line-home-a"></div>
                </div>
            </div>
        </div>

        <div class="layui-col-xs6 layui-col-md3">
            <div class="ok-card layui-card">
                <div class="ok-card-body p0 clearfix cart-data">
                    <div class="data-body">
                        <div class="media-cont">
                            <p class="tit">会员总数</p>
                            <h5 class="num">{!! $db['this_members'] !!}</h5>
                        </div>
                        <div class="w-img" ok-pc-in-show>
                            <img src="/images/home-04.png" alt="会员总数"/>
                        </div>
                    </div>
                    <div id="echUser" class="line-home-a"></div>
                </div>
            </div>
        </div>
    </div>

    {{--    <div class="layui-row layui-col-space15">--}}
    {{--        <div class="layui-col-md8">--}}
    {{--            <div class="layui-card">--}}
    {{--                <div class="layui-card-header">--}}
    {{--                    <div class="ok-card-title">今日用户活跃量</div>--}}
    {{--                </div>--}}
    {{--                <div class="ok-card-body map-body">--}}
    {{--                    <div style="height: 100%;" id="userActiveTodayChart"></div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}

    {{--        <div class="layui-col-md4">--}}
    {{--            <div class="layui-card">--}}
    {{--                <div class="layui-card-header">--}}
    {{--                    <div class="ok-card-title">今日用户访问来源</div>--}}
    {{--                </div>--}}
    {{--                <div class="ok-card-body map-body">--}}
    {{--                    <div style="height: 100%;" id="userSourceTodayChart"></div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}

    <div class="layui-row layui-col-space15">
        {{--        <div class="layui-col-md12">--}}
        {{--            <div class="layui-card">--}}
        {{--                <div class="layui-card-header">--}}
        {{--                    <div class="ok-card-title">本周用户访问来源</div>--}}
        {{--                </div>--}}
        {{--                <div class="ok-card-body clearfix">--}}
        {{--                    <div class="map-china" id="userSourceWeekChart"></div>--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--        </div>--}}

        <div class="layui-col-md7">
            <div class="layui-card">
                <div class="layui-card-header">
                    <div class="ok-card-title">登录日志</div>
                </div>
                <div class="ok-card-body map-body">
                    <div style="width: 100%;" id="userLoginLog"></div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<script type="text/javascript">
    "use strict";
    layui.use(["admin","element", "table", "okUtils", "okCountUp"], function () {
	    let admin = layui.admin;
        let table = layui.table;
        let countUp = layui.okCountUp;
        let okUtils = layui.okUtils;
        let $ = layui.jquery;
        table.render({
            elem: '#userLoginLog',
            url: '{{url('/sys/admUserLogin')}}',
            limit: '{!! frame()['limit'] !!}',
            limits: [{!! frame()['limits'] !!}],
            height: 300,
            //width: 750,
            page: true,
            size: "sm",
            cols:
                [[
                    {field: "open_type", title: "登录方式", width: 80},
                    {field: "name", title: "姓名", width: 90},
                    {field: "type", title: "类型", width: 65},
                    {field: "ip", title: "登录IP", width: 110},
                    {field: "browser", title: "登录设备", width: 160},
                    {field: "add_time", title: "操作时间", width: 145, sort: true},
                ]],
            done: function (res, curr, count) {
                admin.removeLoading();
                //console.info(res, curr, count);
            }
        });

        /**
         * 收入、商品、博客、用户
         */
        function initMediaCont() {
            var elem_nums = $(".media-cont .num");
            elem_nums.each(function (i, j) {
                var ran = parseInt(elem_nums);
                !new countUp({
                    target: j,
                    endVal: ran,
                }).start();
            });
        }

        function dataTrendOption(color) {
            color = color || "#00c292";
            return {
                color: color, toolbox: {show: false, feature: {saveAsImage: {}}},
                grid: {left: '-1%', right: '0', bottom: '0', top: '5px', containLabel: false},
                xAxis: [{
                    type: 'category',
                    boundaryGap: false,
                    splitLine: {show: false},
                    data: ['周一', '周二', '周三', '周四', '周五', '周六', '周日']
                }],
                yAxis: [{type: 'value', splitLine: {show: false}}],
                series: [{
                    name: '用户',
                    type: 'line',
                    stack: '总量',
                    smooth: true,
                    symbol: "none",
                    clickable: false,
                    areaStyle: {},
                    data: [randomData(), randomData(), randomData(), randomData(), randomData(), randomData(), randomData(), randomData(), randomData(), randomData(), randomData(), randomData(), randomData(), randomData(), randomData(), randomData(), randomData(), randomData(), randomData(), randomData(), randomData(), randomData(), randomData(), randomData()]
                }]
            }
        }

        function randomData() {
            return Math.round(Math.random() * 500);
        }

        /**
         * 近一周数量涨幅图表
         */
        function initDataTrendChart() {
            // 收入
            var echIncome = echarts.init($("#echIncome")[0]);
            // 商品
            var echGoods = echarts.init($('#echGoods')[0]);
            // 博客
            var echBlogs = echarts.init($("#echBlogs")[0]);
            // 用户
            var echUser = echarts.init($('#echUser')[0]);
            echIncome.setOption(dataTrendOption("#00c292"));
            echGoods.setOption(dataTrendOption("#ab8ce4"));
            echBlogs.setOption(dataTrendOption("#03a9f3"));
            echUser.setOption(dataTrendOption("#fb9678"));
            okUtils.echartsResize([echIncome, echGoods, echBlogs, echUser]);
        }

        var userActiveTodayChartOption = {
            color: "#03a9f3",
            xAxis: {type: 'category', data: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']},
            yAxis: {type: 'value'},
            series: [{data: [120, 200, 150, 80, 70, 110, 130], type: 'bar'}]
        };

        /**
         * 今日用户活跃量图表
         */
        function initUserActiveTodayChart() {
            var userActiveTodayChart = echarts.init($("#userActiveTodayChart")[0], "themez");
            userActiveTodayChart.setOption(userActiveTodayChartOption);
            okUtils.echartsResize([userActiveTodayChart]);
        }

        var userSourceTodayChartOption = {
            title: {show: false, text: '用户访问来源', subtext: '纯属虚构', x: 'center'},
            tooltip: {trigger: 'item', formatter: "{a} <br/>{b} : {c} ({d}%)"},
            legend: {orient: 'vertical', left: 'left', data: ['直接访问', '邮件营销', '联盟广告', '视频广告', '搜索引擎']},
            series: [
                {
                    name: '访问来源', type: 'pie', radius: '55%', center: ['50%', '60%'],
                    data: [{value: 335, name: '直接访问'}, {value: 310, name: '邮件营销'}, {
                        value: 234,
                        name: '联盟广告'
                    }, {value: 135, name: '视频广告'}, {value: 1548, name: '搜索引擎'}],
                    itemStyle: {emphasis: {shadowBlur: 10, shadowOffsetX: 0, shadowColor: 'rgba(0, 0, 0, 0.5)'}}
                }
            ]
        };

        /**
         * 今日用户访问来源图表
         */
        function initUserSourceTodayChart() {
            var userSourceTodayChart = echarts.init($("#userSourceTodayChart")[0], "themez");
            userSourceTodayChart.setOption(userSourceTodayChartOption);
            okUtils.echartsResize([userSourceTodayChart]);
        }

        var userSourceWeekChartOption = {
            title: {show: true, text: ''},
            tooltip: {trigger: 'axis', axisPointer: {type: 'cross', label: {backgroundColor: '#6a7985'}}},
            legend: {data: ['邮件营销', '联盟广告', '视频广告', '直接访问', '搜索引擎']},
            toolbox: {show: false, feature: {saveAsImage: {}}},
            grid: {left: '3%', right: '4%', bottom: '3%', containLabel: true},
            xAxis: [{type: 'category', boundaryGap: false, data: ['周一', '周二', '周三', '周四', '周五', '周六', '周日']}],
            yAxis: [{type: 'value', splitLine: {show: false},}],
            series: [
                {
                    name: '邮件营销',
                    type: 'line',
                    stack: '总量',
                    smooth: true,
                    areaStyle: {},
                    data: [120, 132, 101, 134, 90, 230, 210]
                },
                {
                    name: '联盟广告',
                    type: 'line',
                    stack: '总量',
                    smooth: true,
                    areaStyle: {},
                    data: [220, 182, 191, 234, 290, 330, 310]
                },
                {
                    name: '视频广告',
                    type: 'line',
                    stack: '总量',
                    smooth: true,
                    areaStyle: {},
                    data: [150, 232, 201, 154, 190, 330, 410]
                },
                {
                    name: '直接访问',
                    type: 'line',
                    stack: '总量',
                    smooth: true,
                    areaStyle: {normal: {}},
                    data: [320, 332, 301, 334, 390, 330, 320]
                },
                {
                    name: '搜索引擎',
                    type: 'line',
                    stack: '总量',
                    smooth: true,
                    label: {normal: {show: true, position: 'top'}},
                    areaStyle: {normal: {}},
                    data: [370, 932, 901, 934, 1290, 1330, 1320]
                }
            ]
        };

        /**
         * 本周用户访问来源图表
         */
        function initUserSourceWeekChart() {
            var userSourceWeekChart = echarts.init($("#userSourceWeekChart")[0], "themez");
            userSourceWeekChart.setOption(userSourceWeekChartOption);
            okUtils.echartsResize([userSourceWeekChart]);
        }

        initMediaCont();
        initDataTrendChart();
        initUserActiveTodayChart();
        initUserSourceTodayChart();
        initUserSourceWeekChart();
    });
</script>



















