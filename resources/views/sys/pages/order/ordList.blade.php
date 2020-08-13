<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    @include('.sys.public.css')
    @include('.sys.public.js')
</head>
<body onscroll="layui.admin.hideFixedEl();" class="page-no-scroll">
<!-- 小球样式 -->
<div class="page-loading">
    <div class="ball-loader">
        <span></span><span></span><span></span><span></span>
    </div>
</div>
<!-- 正文开始 -->
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <!-- 表格工具栏 -->
            <form class="layui-form toolbar table-tool-mini">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">订单状态</label>
                        <div class="layui-input-inline">
                            <select name="status" lay-verify="">
                                <option value="0" selected>请选择订单状态</option>
                                <option value="1">待支付</option>
                                <option value="2">已确认</option>
                                <option value="4">已取消</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-card-header1" data-step="1" data-intro="您好! 这是时间类型的筛选, 默认有 出团时间/下单时间 的选项"
                             data-position="bottom">
                            <label class="layui-form-label">日期类型</label>
                            <div class="layui-input-inline">
                                <select name="dateType" lay-verify="">
                                    <option value="planTime">出团时间</option>
                                    <option value="addTime">下单时间</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-card-header1" data-step="2" data-intro="您好! 这是时间区间, 您可以选择/输入您要筛选数据的时间区间"
                             data-position="bottom">
                            <label class="layui-form-label">时间区间</label>
                            <div class="layui-input-inline" style="width: 200px;">
                                <input name="dateRange" class="layui-input icon-date" placeholder="选择日期范围"
                                       autocomplete="off"/>
                            </div>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-card-header1" data-step="3" data-intro="您好! 请在文本框内输入您要搜索内容的关键字"
                             data-position="bottom">
                            <label class="layui-form-label">搜索</label>
                            <div class="layui-input-inline">
                                <input type="text" class="layui-input" placeholder="请输入关键字 | 线路名称" autocomplete="off"
                                       name="key">
                            </div>
                        </div>
                    </div>
                    <div class="layui-inline" style="padding-right: 110px;">
                        <button class="layui-btn icon-btn" lay-filter="search" lay-submit>
                            <i class="layui-icon">&#xe615;</i>搜索
                        </button>
                    </div>
                </div>
            </form>
            <!-- 数据表格 -->
            <table id="tableId" lay-filter="tableFilter"></table>
        </div>
    </div>
</div>
<!--js逻辑-->
<script>
    var json;
    layui.use(["admin", "jquery", "table", "form", "laydate", "okLayer", "okUtils", "introJs"], function () {
        let admin = layui.admin;
        let table = layui.table;
        let form = layui.form;
        let laydate = layui.laydate;
        let okLayer = layui.okLayer;
        let okUtils = layui.okUtils;
        let introJs = layui.introJs;
        let $ = layui.jquery;
        /* 渲染时间选择 */
        laydate.render({
            elem: 'input[name="dateRange"]',
            type: 'date',
            range: true,
            trigger: 'click'
        });
        let orderTable = table.render({
            elem: "#tableId",
            url: '{{url('/sys/pages/order/orderRead')}}',
            limit: '{!! frame()['limit'] !!}',
            limits: [{!! frame()['limits'] !!}],
            title: '订单列表_{{getTime(3)}}',
            page: true,
            even: true,
            totalRow: true,
            toolbar: '<div class="layui-btn-container">\n' +
                '<ul style="float: right;">' +
                '<button class="layui-btn layui-btn-sm layui-btn-primary" lay-event="useGuidance">使用指引</button>' +
                '</ul>' +
                '</div>',
            size: "lg",
            cols: [[
                // {type: "checkbox", fixed: "left"},
                {field: "id", title: "订单号", width: 80, align: "center", fixed: 'left', totalRowText: '合计'},
                {
                    field: "statusShow",
                    title: "状态",
                    width: 80,
                    align: "center",
                    fixed: 'left',
                    event: 'log',
                    style: 'cursor: pointer;'
                },
                {field: "planDate", title: "出团日期", width: 105, sort: true},
                {field: "backDate", title: "回团日期", width: 105, sort: true},
                {field: "lineTitle", title: "线路名称", width: 220},
                    @if(_admIsVip()==1)
                {
                    field: "amount", title: "应收总额", width: 105, sort: true, totalRow: true, align: 'right'
                },
                {field: "pingAmount_show", title: "已收总额", width: 135, sort: true, align: 'right'},
                {field: "noPingAmount_show", title: "未收总额", width: 135, sort: true, align: 'right'},
                {
                    field: "payablePingAmount_show",
                    title: "应付总额",
                    width: 105,
                    sort: true,
                    totalRow: true,
                    align: 'right'
                },
                {field: "profit", title: "利润", width: 105, sort: true, totalRow: true, align: 'right'},
                    @else
                {
                    field: "amount", title: "应付总额", width: 105, sort: true, totalRow: true, align: 'right'
                },
                {field: "pingAmount_show", title: "已付总额", width: 135, sort: true, align: 'right'},
                {field: "noPingAmount_show", title: "未付总额", width: 135, sort: true, align: 'right'},
                    @endif
                {
                    field: "fromCityName", title: "出发城市", width: 100
                },
                {field: "pubFromcityName", title: "联运城市", width: 100},
                {field: "priceTitle", title: "套餐名称", width: 100},
                {field: "perNum", title: "总人数", width: 100, sort: true, totalRow: true},
                {field: "aduNum", title: "成人数", width: 100, sort: true, totalRow: true},
                {field: "aduNum1", title: "小青年", width: 100, sort: true, totalRow: true},
                {field: "aduNum2", title: "老人数", width: 100, sort: true, totalRow: true},
                {field: "chdNum", title: "儿童数", width: 100, sort: true, totalRow: true},
                {field: "ordBak", title: "订单备注", width: 250},
                    @if(_admIsVip()==1)
                {
                    field: "supCpyName", title: "供应商", width: 150, sort: true
                },
                {field: "supCpyMobile", title: "供应商电话", width: 130, sort: true},
                    @endif
                {
                    field: "ctName", title: "下单人", width: 100, sort: true
                },
                {field: "addTime", title: "下单时间", width: 180, sort: true},
                {field: "endTime", title: "支付终止时间", width: 180, sort: true},
                {
                    title: "操作", width: 100, align: "center", fixed: "right", templet: function (d) {
                        var orderInfo = "@if(hasPower(249))<a href=\"javascript:\" title=\"订单详情\" lay-event=\"orderInfo\"><i class=\"ok-icon\">&#xe74a;</i></a>@endif";
                        var pay = "<a href=\"javascript:\" title=\"支付\" lay-event=\"pay\"><i class=\"ok-icon\">&#xe77d;</i></a>";
                        var cancel = "@if(hasPower(250))<a href=\"javascript:\" title=\"取消\" lay-event=\"cancel\"><i class=\"ok-icon\">&#xe659;</i></a>@endif";
                        var list = '';
                        if (d.noPingAmount > 0 && d.isOk >= 0) {
                            list = list + '&nbsp;&nbsp;' + pay
                        }
                        if (d.isOk == 0) {
                            if (d.admIsVip == 1) {
                                list = list + '&nbsp;&nbsp;' + cancel
                            } else {
                                list = list + '&nbsp;&nbsp;';
                            }
                        }
                        return orderInfo + list;
                    }
                }
            ]],
            done: function (res, curr, count) {
                admin.removeLoading();
                //console.log(res, curr, count)
            }
        });

        /* 表格搜索 */
        form.on("submit(search)", function (data) {
            if (data.field.dateRange) {
                var searchDate = data.field.dateRange.split(' - ');
                data.field.start_time = searchDate[0];
                data.field.end_time = searchDate[1];
            } else {
                data.field.start_time = '';
                data.field.end_time = '';
            }
            data.field.dateRange = undefined;
            orderTable.reload({
                where: data.field,
                page: {curr: 1}
            });
            return false;
        });

        /* 表格点击事件 */
        table.on("toolbar(tableFilter)", function (obj) {
            switch (obj.event) {
                case "useGuidance":
                    introJs().setOption('showProgress', true).start();
                    break;
                case "batchCancel":
                    batchCancel();
                    break;
            }
        });

        /* 表格工具条点击事件 */
        table.on("tool(tableFilter)", function (obj) {
            let data = obj.data;
            switch (obj.event) {
                case "orderInfo":
                    orderInfo(data.id);
                    break;
                case "pay":
                    pay(data.id);
                    break;
                case "cancel":
                    cancel(data.id);
                    break;
                case "log":
                    log(data.id);
                    break;
            }
        });

        function batchCancel() {
            var checkStatus = table.checkStatus('tableId');
            for (var i = 0; i < checkStatus['data'].length; i++) {
                if (checkStatus['data'][i]['isOk'] !== 0) {
                    layer.msg("只有在待支付状态下才可以取消哦", {icon: 5});
                    return;
                }
            }
            okLayer.confirm("确定要批量取消吗？", function (index) {
                layer.close(index);
                let idsStr = okUtils.tableBatchCheck(table);
                if (idsStr) {
                    okUtils.ajax("orderCancel", "post", {
                        id: idsStr,
                        _token: '{{csrf_token()}}'
                    }, true).done(function (response) {
                        okUtils.tableSuccessMsg(response.msg);
                    }).fail(function (error) {
                        console.log(error)
                    });
                }
            });
        }

        function orderInfo(id) {
            okLayer.open("订单详情", "ordList/" + id, "100%", "100%", null, function () {
                orderTable.reload();
            })
        }

        function pay(id) {
            okLayer.open("支付", "orderPay/" + id + "/-1", "380px", "520px", null, function () {
                orderTable.reload();
            })
        }

        function cancel(id) {
            okLayer.confirm("确定要取消吗？", function () {
                okUtils.ajax("orderCancel", "post", {
                    id: id,
                    _token: '{{csrf_token()}}'
                }, true).done(function (response) {
                    okUtils.tableSuccessMsg(response.msg);
                }).fail(function (error) {
                    console.log(error)
                });
            })
        }

        function log(id) {
            var tableName = 'line_plan_ord';
            var tableId = id;
            var tableStr = 'aduNum,aduNum1,aduNum2,adultPrice,adultPrice1,adultPrice2,chdNum,chdNum1,childPrice,childPrice1,ctName,ctInfo,days,dingPrice,linePlanPriceTitle,ordBak,priceType,saleName,sources';
            okLayer.open("日志", "/sys/pages/logInfo/" + tableName + '/' + tableId + '/' + tableStr, "90%", "90%", null, function () {
            })
        }
    })
</script>
</body>
</html>
