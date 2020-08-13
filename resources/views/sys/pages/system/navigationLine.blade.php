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
                        <label class="layui-form-label">日期类型</label>
                        <div class="layui-input-inline">
                            <select name="dateType" lay-verify="">
                                <option value="addTime">创建时间</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">时间区间</label>
                        <div class="layui-input-inline" style="width: 200px;">
                            <input name="dateRange" class="layui-input icon-date" placeholder="选择日期范围"
                                   autocomplete="off"/>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">搜索</label>
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input" placeholder="请输入搜索关键字" autocomplete="off"
                                   name="key">
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
    layui.use(["admin", "element", "jquery", "table", "form", "laydate", "okLayer", "okUtils", "okLayx"], function () {
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
        /* 渲染时间选择 */
        laydate.render({
            elem: 'input[name="dateRange"]',
            type: 'date',
            range: true,
            trigger: 'click'
        });
        let roleTable = table.render({
            elem: "#tableId",
            url: '{{url('/sys/pages/system/navigationLineProductRead')}}',
            where: {'navigationId': '{{$id}}', 'classify': '{{$classify}}'},
            limit: '{!! frame()['limit'] !!}',
            limits: [{!! frame()['limits'] !!}],
            title: '产品列表_{{getTime(3)}}',
            page: true,
            toolbar: '<div class="layui-btn-container">\n' +
                @if(hasPower(220)) '<button class="layui-btn layui-btn-sm" lay-event="bind" id="bind" name="bind">绑定</button>\n' + @endif
                    '    </div>',
            size: "sm",
            cols: [[
                {type: "checkbox", fixed: "left"},
                {field: "id", title: "编号", width: 80},
                {field: "line_share_name", title: "公共", width: 55},
                {field: "line_type_name", title: "产品类别", width: 120},
                {field: "type", title: "产品类型", width: 80},
                {field: "code", title: "产品代码", width: 120},
                {field: "title", title: "产品名称", width: 360},
                {field: "planCount", title: "团数", width: 70, sort: true},
                // {field: "days", title: "天数", width: 70, sort: true},
                // {field: "night", title: "晚数", width: 70, sort: true},
                // {field: "adult_price", title: "成人价(同行)", width: 120, sort: true},
                // {field: "child_price", title: "小人价(同行)", width: 120, sort: true},
                // {field: "sadult_price", title: "成人价(市场)", width: 120, sort: true},
                // {field: "schild_price", title: "小人价(市场)", width: 120, sort: true},
                {field: "cpyName", title: "供应商", width: 150},
                {field: "operate_type", title: "经营类型", width: 80},
                {field: "add_time", title: "创建时间", width: 145, sort: true},
            ]],
            done: function (res, curr, count) {
                // console.log(res, curr, count);
                admin.removeLoading();
                $("#bind").attr("class", "layui-btn layui-btn-sm");
                $("#bind").attr("disabled", false);
            }
        });
        form.on("submit(search)", function (data) {
            if (data.field.dateRange) {
                var searchDate = data.field.dateRange.split(' - ');
                data.field.start_time = searchDate[0] + ' 00:00:00.000';
                data.field.end_time = searchDate[1] + ' 23:59:59.999';
            } else {
                data.field.start_time = '';
                data.field.end_time = '';
            }
            data.field.dateRange = undefined;
            roleTable.reload({
                where: data.field,
                page: {curr: 1}
            });
            return false;
        });

        table.on("toolbar(tableFilter)", function (obj) {
            switch (obj.event) {
                case "bind":
                    bind();
                    break;
            }
        });

        function bind() {
            okLayer.confirm("确定进行关联吗？", function (indexx) {
                layer.close(indexx);
                var index = layer.msg('提交中，请稍候', {icon: 16, time: false, shade: 0.8});
                let idsStr = okUtils.tableBatchCheck(table);
                if (idsStr) {
                    okUtils.ajax("{{url('/sys/pages/system/navigationLine')}}", "post", {
                        navigation_id: '{{$id}}',
                        line_id: idsStr,
                        _token: '{{csrf_token()}}'
                    }, true).done(function (response) {
                        setTimeout(function () {
                            $("#bind").attr("class", "layui-btn layui-btn-disabled layui-btn-sm");
                            $("#bind").attr("disabled", "disabled");
                            okUtils.tableSuccessMsg(response.msg);
                            layer.close(index);
                        }, 1000);
                    }).fail(function (error) {
                        console.log(error)
                    });
                }
            });
        }

    })
</script>
</body>
</html>
