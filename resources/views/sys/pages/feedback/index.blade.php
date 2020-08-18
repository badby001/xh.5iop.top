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
                        <div class="layui-card-header1" data-step="1" data-intro="您好! 这是时间类型的筛选, 默认有 添加时间 的选项"
                             data-position="bottom">
                            <label class="layui-form-label">日期类型</label>
                            <div class="layui-input-inline">
                                <select name="dateType" lay-verify="">
                                    <option value="addTime">添加时间</option>
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
                                <input type="text" class="layui-input" placeholder="请输入关键字" autocomplete="off"
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
        let dbTable = table.render({
            elem: "#tableId",
            url: '{{url('/sys/pages/handlingManagement/feedbackRead')}}',
            limit: '{!! frame()['limit'] !!}',
            limits: [{!! frame()['limits'] !!}],
            title: '代办列表_{{getTime(3)}}',
            page: true,
            even: true,
            toolbar: '<div class="layui-btn-container">\n' +
                '<button class="layui-btn layui-btn-sm" lay-event="add">添加原始办件</button>\n' +
                // '<button class="layui-btn layui-btn-sm layui-btn-normal" lay-event="batchEnabled">批量启用</button>\n' +
                // '<button class="layui-btn layui-btn-sm layui-btn-warm" lay-event="batchDisabled">批量停用</button>\n' +
                // '<button class="layui-btn layui-btn-sm layui-btn-danger" lay-event="batchDel">批量删除</button>\n' +
                '<ul style="float: right;">' +
                '<button class="layui-btn layui-btn-sm layui-btn-primary" lay-event="useGuidance">使用指引</button>' +
                '</ul>' +
                '</div>',
            size: "lg",
            cols: [[
                // {type: "checkbox", fixed: "left"},
                {field: "event_number", title: "事件编号", width: 120},
                {field: "recording_time", title: "记录时间", width: 120},
                {field: "plaintiff", title: "诉求人", width: 80},
                {field: "contact_number", title: "联系电话", width: 120},
                {field: "event_type", title: "事件类型", width: 80},
                {field: "event_max_category", title: "事件大类", width: 80},
                {field: "event_title", title: "事件标题", width: 180},
                {field: "details_of_the_incident", title: "事件详情", width: 320},
                {field: "event_address", title: "事件地址", width: 220},
                {field: "emergency_degree", title: "紧急程度", width: 80},
                {
                    title: "操作", width: 100, align: "center", fixed: "right", templet: function (d) {
                        var info = "<a href=\"javascript:\" title=\"督办详情\" lay-event=\"info\"><i class=\"ok-icon\">&#xe74a;</i></a>";
                        var edit = "<a href=\"javascript:\" title=\"编辑\" lay-event=\"edit\"><i class=\"layui-icon\">&#xe642;</i></a>";
                        var chuLi = "<a href=\"javascript:\" title=\"处理办件\" lay-event=\"chuLi\"><i class=\"layui-icon\">&#xe63a;</i></a>";
                        var print = "<a href=\"javascript:\" title=\"打印\" lay-event=\"print\"><i class=\"layui-icon\">&#xe66d;</i></a>";
                        return edit + '&nbsp;&nbsp;' + chuLi + '&nbsp;&nbsp;' + print;
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
            dbTable.reload({
                where: data.field,
                page: {curr: 1}
            });
            return false;
        });

        /* 表格点击事件 */
        table.on("toolbar(tableFilter)", function (obj) {
            switch (obj.event) {
                case "add":
                    add();
                    break;
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
                case "info":
                    info(data.id);
                    break;
                case "edit":
                    edit(data);
                    break;
                case "chuLi":
                    chuLi(data.id);
                    break;
                case "print":
                    print(data.id);
                    break;
                case "cancel":
                    cancel(data.id);
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


        function add() {
            json = JSON.stringify('');
            okLayer.open("添加原始办件", "feedback/create", "100%", "100%", null, function () {
                dbTable.reload();
            })
        }

        function edit(data) {
            json = JSON.stringify(data);
            okLayer.open("编辑原始办件", "feedback/" + data.id + "/edit", "100%", "100%", null, function () {
                dbTable.reload();
            })
        }

        function chuLi(id) {
            okLayer.open("处理办件", "feedbackChuLi/" + id + '/edit', "100%", "100%", null, function () {
                dbTable.reload();
            })
        }

        function print(id) {
            okLayer.open("打印反馈表", "feedbackPrint/" + id, "100%", "100%", null, function () {
                dbTable.reload();
            })
        }

        function info(id) {
            okLayer.open("详情", "feedback/" + id, "100%", "100%", null, function () {
                dbTable.reload();
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


        //监听单元格编辑
        table.on('edit(tableFilter)', function (obj) {
            okUtils.ajax("feedbackTableEdit", "post", {
                id: obj.data.id,
                field: obj.field,
                value: obj.value,
                _token: '{{csrf_token()}}'
            }, true).done(function (response) {
                okUtils.tableSuccessMsg(response.msg);
            }).fail(function (error) {
                console.log(error)
            });
        });
    })
</script>
</body>
</html>
