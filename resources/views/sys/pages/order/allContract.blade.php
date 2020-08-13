<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    @include('.sys.public.css')
    @include('.sys.public.js')
</head>
<body class="page-no-scroll">
<!-- 小球样式 -->
<div class="page-loading">
    <div class="ball-loader">
        <span></span><span></span><span></span><span></span>
    </div>
</div>
<div class="ok-body">
    <!--模糊搜索区域-->
    <div class="layui-row">
        <form class="layui-form layui-col-md12 ok-search">
            {{--            <input class="layui-input" placeholder="开始日期" autocomplete="off" id="start_time" name="start_time">--}}
            {{--            <input class="layui-input" placeholder="截止日期" autocomplete="off" id="end_time" name="end_time">--}}
            {{--            <input class="layui-input" placeholder="请输入广告标题" autocomplete="off" name="title">--}}
            {{--            <button class="layui-btn" lay-submit="" lay-filter="search">--}}
            {{--                <i class="layui-icon layui-icon-search"></i>--}}
            {{--            </button>--}}
        </form>
    </div>
    <!--数据表格-->
    <table class="layui-hide" id="tableId" lay-filter="tableFilter"></table>
</div>
<!--js逻辑-->
<script>
    var json;
    layui.use(["admin","element", "jquery", "table", "form", "laydate", "okLayer", "okUtils", "okLayx"], function () {
	    let admin = layui.admin;
        let table = layui.table;
        let form = layui.form;
        let laydate = layui.laydate;
        let okLayer = layui.okLayer;
        let okUtils = layui.okUtils;
        let okLayx = layui.okLayx;
        let $ = layui.jquery;
        okLayx.notice({
            title: "温馨提示",
            type: "warning",
            message: "{!! frame()['message'] !!}"
        });
        laydate.render({elem: "#start_time", type: "datetime"});
        laydate.render({elem: "#end_time", type: "datetime"});
        let eContractTable = table.render({
            elem: "#tableId",
            url: '{{url('/sys/pages/order/eContractAllRead')}}',
            title: '电子合同汇总列表_{{getTime(3)}}',
            page: true,
            even: true,
            totalRow: true,
            toolbar: '<div class="layui-btn-container">\n' +
                '<button class="layui-btn layui-btn-normal layui-btn-sm" lay-event="log">日志</button>\n' +
                '</div>',
            size: "sm",
            cols: [[
                {
                    field: "statusShow",
                    title: "状态",
                    width: 70,
                    align: "center",
                    sort: true,
                    event: 'log',
                    style: 'cursor: pointer;',
                    totalRowText: '合计'
                },
                {field: "planDate", title: "出团日期", width: 95},
                {field: "lineTitle", title: "线路名称", width: 260},
                {
                    field: "amount", title: "应收总额", width: 105, sort: true, totalRow: true, align: 'right'
                },
                {field: "pingAmount", title: "已收总额", width: 135, sort: true, totalRow: true, align: 'right'},
                {field: "noPingAmount", title: "未收总额", width: 135, sort: true, totalRow: true, align: 'right'},
                {field: "cusName", title: "游客代表", width: 80},
                {field: "cusMoblie", title: "代表手机号", width: 110},
                {field: "userNames", title: "合同成员", width: 160},
                {field: "others", title: "其他约定", width: 120},
                {field: "totalPrice", title: "旅游费用", width: 80},
                {field: "id", title: "编号", width: 80, align: "center"},
                {field: "ordId", title: "订单号", width: 80, align: "center"},
                {field: "contractNo", title: "合同编号", width: 200},
                {field: "addTime", title: "添加时间", width: 145,  sort: true},
                {
                    title: "操作", width: 200, templet: function (d) {
                        var sign = "@if(hasPower(262))<a href=\"javascript:\" title=\"发起签署\" lay-event=\"sign\"><i class=\"layui-icon\">&#xe609;</i></a>@endif";
                        var signSms = "@if(hasPower(263))<a href=\"javascript:\" title=\"发送签署短信\" lay-event=\"signSms\"><i class=\"layui-icon\">&#xe618;</i></a>@endif";
                        var signCancel = "@if(hasPower(265))<a href=\"javascript:\" title=\"作废合同\" lay-event=\"signCancel\"><i class=\"layui-icon\">&#x1006;</i></a>@endif";
                        var signDelete = "@if(hasPower(265))<a href=\"javascript:\" title=\"删除合同\" lay-event=\"signDelete\"><i class=\"layui-icon\">&#xe640;</i></a>@endif";
                        var download = "@if(hasPower(266))<a href=\"javascript:\" title=\"下载\" lay-event=\"download\"><i class=\"layui-icon\">&#xe601;</i></a>@endif";
                        if (d.status == 0) {
                            return sign;
                        } else if (d.status == 1) {
                            return signSms + signCancel;
                        } else if (d.status == 2) {
                            return download;
                        } else {
                            return signDelete;
                        }
                    }
                },
            ]],
            done: function (res, curr, count) {
                admin.removeLoading();
                //console.log(res, curr, count)
            }
        });

        // form.on("submit(search)", function (data) {
        //     eContractTable.reload({
        //         where: data.field,
        //         page: {curr: 1}
        //     });
        //     return false;
        // });

        table.on("toolbar(tableFilter)", function (obj) {
            switch (obj.event) {
                case "log":
                    log();
                    break;
                case "":
                    break;
            }
        });


        function log(id) {
            var tableName = 'electronic_contract';
            var tableId = id;
            var tableStr = 'orderId,minPlanNum,aduAmount,childAcmount,amountAll,payTime,transactorName,transactorPhone,other,traveler,travelmobile';
            okLayer.open("日志", "/sys/pages/logInfo/" + tableName + '/' + tableId + '/' + tableStr, "90%", "90%", null, function () {
            })
        }


        table.on("tool(tableFilter)", function (obj) {
            let data = obj.data;
            switch (obj.event) {
                case "sign":
                    sign(data);
                    break;
                case "signSms":
                    signSms(data.id);
                    break;
                case "signCancel":
                    signCancel(data);
                    break;
                case "signDelete":
                    signDelete(data.id);
                    break;
                case "download":
                    download(data.fileUrl);
                case "log":
                    log(data.id);
                    break;
            }
        });

        function sign(data) {
            okLayer.confirm("确定要发起签署吗？", function () {
                okUtils.ajax("/sys/pages/order/eContractSign", "post", {
                    id: data.id,
                    transactorName: data.transactorName,
                    transactorPhone: data.transactorPhone,
                    _token: '{{csrf_token()}}'
                }, true).done(function (response) {
                    okUtils.tableSuccessMsg(response.msg);
                    eContractTable.reload();
                }).fail(function (error) {
                    console.log(error)
                });
            })
        }

        function signSms(id) {
            okLayer.confirm("确定要重新发送签署短信吗？", function () {
                okUtils.ajax("/sys/pages/order/eContractSignSms", "post", {
                    id: id,
                    _token: '{{csrf_token()}}'
                }, true).done(function (response) {
                    okUtils.tableSuccessMsg(response.msg);
                    eContractTable.reload();
                }).fail(function (error) {
                    console.log(error)
                });
            })
        }


        function signCancel(data) {
            console.log(data);
            okLayer.confirm("确定要作废合同吗？", function () {
                okUtils.ajax("/sys/pages/order/eContractSignCancel", "post", {
                    id: data.id,
                    ordId: data.ordId,
                    //planId: data.planId,
                    contractNo: data.contractNo,
                    _token: '{{csrf_token()}}'
                }, true).done(function (response) {
                    okUtils.tableSuccessMsg(response.msg);
                    eContractTable.reload();
                }).fail(function (error) {
                    console.log(error)
                });
            })
        }


        function signDelete(id) {
            okLayer.confirm("确定要删除合同吗？", function () {
                okUtils.ajax("/sys/pages/order/eContractSignDelete", "post", {
                    id: id,
                    _token: '{{csrf_token()}}'
                }, true).done(function (response) {
                    okUtils.tableSuccessMsg(response.msg);
                    eContractTable.reload();
                }).fail(function (error) {
                    console.log(error)
                });
            })
        }

        function download(fileUrl) {
            window.open(fileUrl);
        }

    })
</script>
</body>
</html>
