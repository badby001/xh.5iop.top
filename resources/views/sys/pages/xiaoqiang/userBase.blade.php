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
                        <label class="layui-form-label">请选择状态</label>
                        <div class="layui-input-inline">
                            <select name="is_lock" lay-verify="">
                                <option value="" selected>请选择状态</option>
                                <option value="o">已生成</option>
                                <option value="n">未生成</option>
                            </select>
                        </div>
                    </div>
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
                            <input type="text" class="layui-input" placeholder="请输入关键字 | 姓名/手机号" autocomplete="off"
                                   name="key">
                        </div>
                    </div>
                    <div class="layui-inline" style="padding-right: 110px;">
                        <button class="layui-btn icon-btn" lay-filter="search" lay-submit>
                            <i class="layui-icon">&#xe615;</i>搜索
                        </button>
                        <button id="tbBasicExportBtn" class="layui-btn icon-btn" type="button">
                            <i class="layui-icon">&#xe67d;</i>导出
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
        let userBaseTable = table.render({
            elem: '#tableId',
            url: '{{url('/sys/pages/system/xiaoqiang/userBaseRead')}}',
            limit: '{!! frame()['limit'] !!}',
            limits: [{!! frame()['limits'] !!}],
            title: '客户联系人列表_{{getTime(3)}}',
            page: true,
            even: true,
            toolbar: '<div class="layui-btn-container">\n' +
                @if(hasPower(281))'<button class="layui-btn layui-btn-sm layui-btn-normal" lay-event="batchCreate">生成小助手账号</button>\n' + @endif
                    '<a style="font-size:12px;" class="gray-1">默认生成的密码为手机号码后六位</a>' +
                '</div>',
            size: "sm",
            cols:
                [[
                    {type: "checkbox", fixed: "left"},
                    {field: "id", title: "编号", width: 80},
                    {field: "Code", title: "账号状态", templet: '#CodeTpl', width: 80},
                    {field: "cpyName", title: "公司名称", width: 220, sort: true},
                    {field: "trueName", title: "姓名", width: 100},
                    {field: "DeptName", title: "部门", width: 100},
                    {field: "Job", title: "职位", width: 100},
                    {field: "sex", title: "性别", width: 60},
                    {field: "ordNum", title: "订单量", width: 70, align: 'center'},
                    {field: "Mobile", title: "手机号", width: 120},
                    {field: "tel", title: "电话", width: 120},
                    {field: "fax", title: "传真", width: 120},
                    {field: "weixin", title: "微信", width: 120},
                    {field: "email", title: "邮箱", width: 150},
                    {field: "QQ", title: "QQ", width: 110},
                    {field: "addr", title: "地址", width: 120},
                    {field: "remark", title: "备注", width: 200},
                    {field: "sales_name", title: "销售", width: 90},
                    {field: "is_leader", title: "身份", width: 80, templet: '#is_leaderTpl', sort: true},
                    {field: "addName", title: "创建者", width: 90},
                    {field: "addTime", title: "创建时间", width: 145, sort: true},
                ]],
            done: function (res, curr, count) {
                admin.removeLoading();
                //console.info(res, curr, count);
            }
        });
        /* 表格搜索 */
        form.on('submit(search)', function (data) {
            if (data.field.dateRange) {
                var searchDate = data.field.dateRange.split(' - ');
                data.field.start_time = searchDate[0]+' 00:00:00.000';
                data.field.end_time = searchDate[1]+' 23:59:59.999';
            } else {
                data.field.start_time = '';
                data.field.end_time = '';
            }
            data.field.dateRange = undefined;
            userBaseTable.reload({
                where: data.field,
                page: {curr: 1}
            });
            return false;
        });

        /* 表格点击事件 */
        table.on("toolbar(tableFilter)", function (obj) {
            switch (obj.event) {
                case "batchCreate":
                    batchCreate();
                    break;
                case "":
                    break;
            }
        });


        function batchCreate() {
            okLayer.confirm("确定要批量生成小助手账号吗？", function (index) {
                layer.close(index);
                let idsStr = okUtils.tableBatchCheck(table);
                if (idsStr) {
                    okUtils.ajax("userBase", "post", {
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

        // 导出excel
        $('#tbBasicExportBtn').click(function () {
            let idsStr = okUtils.tableBatchCheck(table);
            if (idsStr) {
                table.exportFile(userBaseTable.config.id, idsStr.data, 'xls');
            }
        });
    })
</script>
<script type="text/html" id="CodeTpl">
    <%#  if(d.Code == 1){ %>
    <span class="layui-btn layui-btn-normal layui-btn-xs">已生成</span>
    <%#  } else { %>
    <span class="layui-btn layui-btn-warm layui-btn-xs">未生成</span>
    <%#  } %>
</script>
<script type="text/html" id="is_leaderTpl">
    <%#  if(d.is_leader == 1){ %>
    <span class="layui-btn layui-btn-normal layui-btn-xs">负责人</span>
    <%#  } else { %>
    <span class="layui-btn layui-btn-warm layui-btn-xs">员工</span>
    <%#  } %>
</script>
</body>
</html>
