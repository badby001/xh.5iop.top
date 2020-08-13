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
                        <label class="layui-form-label">选择状态</label>
                        <div class="layui-input-inline">
                            <select name="is_lock" lay-verify="">
                                <option value="" selected>请选择状态</option>
                                <option value="o">已启用</option>
                                <option value="n">已停用</option>
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
                        <label class="layui-form-label">时间区间:</label>
                        <div class="layui-input-inline" style="width: 200px;">
                            <input name="dateRange" class="layui-input icon-date" placeholder="选择日期范围"
                                   autocomplete="off"/>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">认证状态</label>
                        <div class="layui-input-inline">
                            <select name="state" lay-verify="">
                                <option value="" selected>请选择状态</option>
                                <option value="0">未认证</option>
                                <option value="1">认证中</option>
                                <option value="2">已认证</option>
                                <option value="-1">认证失败</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">搜索</label>
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input" placeholder="请输入关键字 | 姓名/账号" autocomplete="off"
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
        let userTable = table.render({
            elem: '#tableId',
            url: '{{url('/sys/pages/system/userRead')}}',
            limit: '{!! frame()['limit'] !!}',
            limits: [{!! frame()['limits'] !!}],
            title: '用户列表_{{getTime(3)}}',
            page: true,
            even: true,
            toolbar: '<div class="layui-btn-container">\n' +
                @if(hasPower(159))'<button class="layui-btn layui-btn-sm layui-btn-normal" lay-event="batchEnabled">批量启用</button>\n' +
                '<button class="layui-btn layui-btn-sm layui-btn-warm" lay-event="batchDisabled">批量停用</button>\n' + @endif
                    '<button class="layui-btn layui-btn-sm layui-btn-danger" lay-event="batchPower">权限赋予</button>' +
                '    </div>',
            size: "sm",
            cols:
                [[
                    {type: "checkbox", fixed: "left"},
                    {field: "code", title: "编号", width: 100},
                    {field: "name", title: "姓名", width: 100},
                    {field: "sex", title: "性别", width: 60},
                    {field: "attestation_tourist_agency", title: "旅行社名称", width: 220},
                    {field: "group_number_name", title: "分组编号", width: 100},
                    {field: "birth_date", title: "出生日期", width: 100, sort: true},
                    {field: "open_id", title: "账号", width: 120},
                    {field: "email", title: "常用邮箱", width: 160},
                    {field: "wei_xin", title: "微信快捷", width: 80, align: 'center'},
                    {field: "role_name", title: "角色", width: 100},
                    {field: "last_login_time", title: "最后登录时间", width: 145, sort: true},
                    {field: "is_lock_name", title: "状态", width: 85, sort: true},
                    {field: "add_name", title: "创建者", width: 90},
                    {field: "add_time", title: "创建时间", width: 145, sort: true},
                    {field: "up_name", title: "最后修改人", width: 100},
                    {field: "up_time", title: "修改时间", width: 145, sort: true},
                    {
                        title: "操作", width: 100, align: "center", fixed: "right", templet: function (d) {
                            var edit = "@if(hasPower(158))<a href=\"javascript:\" title=\"修改\" lay-event=\"edit\"><i class=\"layui-icon\">&#xe642;</i></a>@endif";
                            return edit;
                        }
                    }
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
                data.field.start_time = searchDate[0] + ' 00:00:00.000';
                data.field.end_time = searchDate[1] + ' 23:59:59.999';
            } else {
                data.field.start_time = '';
                data.field.end_time = '';
            }
            data.field.dateRange = undefined;
            userTable.reload({
                where: data.field,
                page: {curr: 1}
            });
            return false;
        });

        /* 表格点击事件 */
        table.on("toolbar(tableFilter)", function (obj) {
            switch (obj.event) {
                case "batchEnabled":
                    batchEnabled();
                    break;
                case "batchDisabled":
                    batchDisabled();
                    break;
                case "batchPower":
                    batchPower();
                    break;
                case "add":
                    add();
                    break;
            }
        });
        /* 表格工具条点击事件 */
        table.on("tool(tableFilter)", function (obj) {
            let data = obj.data;
            switch (obj.event) {
                case "edit":
                    edit(data);
                    break;
                case "del":
                    del(data.id);
                    break;
            }
        });

        function batchEnabled() {
            okLayer.confirm("确定要批量启用吗？", function (index) {
                layer.close(index);
                let idsStr = okUtils.tableBatchCheck(table);
                if (idsStr) {
                    okUtils.ajax("userStart", "post", {
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

        function batchDisabled() {
            okLayer.confirm("确定要批量停用吗？", function (index) {
                layer.close(index);
                let idsStr = okUtils.tableBatchCheck(table);
                if (idsStr) {
                    okUtils.ajax("userStop", "post", {
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

        function batchPower() {
            okLayer.open("权限赋予", "userPower", "600px", "90%", null, function () {
                userTable.reload();
            })
        }

        function add() {
            json = JSON.stringify('');
            okLayer.open("添加用户", "user/create", "90%", "90%", null, function () {
                userTable.reload();
            })
        }

        function edit(data) {
            json = JSON.stringify(data);
            okLayer.open("编辑用户", "user/" + data.id + "/edit", "90%", "90%", null, function () {
                userTable.reload();
            })
        }

        // 导出excel
        $('#tbBasicExportBtn').click(function () {
            let idsStr = okUtils.tableBatchCheck(table);
            if (idsStr) {
                table.exportFile(userTable.config.id, idsStr.data, 'xls');
            }
        });
    })
</script>
</body>
</html>
