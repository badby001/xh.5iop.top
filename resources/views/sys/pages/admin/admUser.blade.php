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
                        <div class="layui-card-header1" data-step="1" data-intro="您好! 这是时间类型的筛选, 默认有 创建时间 的选项"
                             data-position="bottom">
                            <label class="layui-form-label">日期类型</label>
                            <div class="layui-input-inline">
                                <select name="dateType" lay-verify="">
                                    <option value="addTime">创建时间</option>
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
                        <label class="layui-form-label">选择角色</label>
                        <div class="layui-input-inline">
                            <select name="role_id" lay-verify="">
                                <option value="" selected>请选择角色</option>
                                @foreach(getUserRole(0) as $k=>$v)
                                    <option value="{!! $v['id'] !!}">{!! $v['title'] !!}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-card-header1" data-step="3" data-intro="您好! 请在文本框内输入名称/账号"
                             data-position="bottom">
                            <label class="layui-form-label">搜索</label>
                            <div class="layui-input-inline">
                                <input type="text" class="layui-input" placeholder="请输入关键字 | 名称/账号" autocomplete="off"
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
    layui.use(["admin", "element", "jquery", "table", "form", "laydate", "okLayer", "okUtils", "introJs"], function () {
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
        let userTable = table.render({
            elem: '#tableId',
            url: '{{url('/sys/pages/admin/admUserRead')}}',
            limit: '{!! frame()['limit'] !!}',
            limits: [{!! frame()['limits'] !!}],
            title: '账号列表_{{getTime(3)}}',
            page: true,
            toolbar: '<div class="layui-btn-container">\n' +
                @if(hasPower(53)) '<button class="layui-btn layui-btn-sm" lay-event="add">添加账号</button>\n' + @endif
                    @if(hasPower(75)) '<button class="layui-btn layui-btn-sm layui-btn-normal" lay-event="batchEnabled">批量启用</button>\n' +
                '<button class="layui-btn layui-btn-sm layui-btn-warm" lay-event="batchDisabled">批量停用</button>\n' + @endif
                    @if(hasPower(55)) '<button class="layui-btn layui-btn-sm layui-btn-danger" lay-event="batchDel">批量删除</button>\n' + @endif
                    '<button class="layui-btn layui-btn-normal layui-btn-sm" lay-event="log">日志</button>\n' +
                '<ul style="float: right;">' +
                '<button class="layui-btn layui-btn-sm layui-btn-primary" lay-event="useGuidance">使用指引</button>' +
                '</ul>' +
                '</div>',
            size: "sm",
            cols:
                [[
                    {type: "checkbox", fixed: "left"},
                    {field: "code", title: "编号", width: 100},
                    {field: "open_id", title: "账号", width: 120},
                    {field: "name", title: "名称", width: 150},
                    {field: "role_name", title: "角色", width: 100},
                    {
                        field: "is_lock_name",
                        title: "状态",
                        width: 85,
                        sort: true,
                        event: 'log',
                        style: 'cursor: pointer;'
                    },
                    {field: "add_name", title: "创建者", width: 90},
                    {field: "add_time", title: "创建时间", width: 145, sort: true},
                    {field: "up_name", title: "最后修改人", width: 100},
                    {field: "up_time", title: "修改时间", width: 145, sort: true},
                    {
                        title: "操作", width: 100, align: "center", fixed: "right", templet: function (d) {
                            var edit = "@if(hasPower(54))<a href=\"javascript:\" title=\"修改\" lay-event=\"edit\"><i class=\"layui-icon\">&#xe642;</i></a>@endif";
                            var del = "@if(hasPower(55))<a href=\"javascript:\" title=\"删除\" lay-event=\"del\"><i class=\"layui-icon\">&#xe640;</i></a>@endif";
                            if (d.is_lock == 1) {
                                return edit + del;
                            } else {
                                return edit;
                            }
                        }
                    }
                ]],
            done: function (res, curr, count) {
                admin.removeLoading();
                //console.info(res, curr, count);
            }
        });
        /* 表格搜索 */
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
            userTable.reload({
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
                case "batchEnabled":
                    batchEnabled();
                    break;
                case "batchDisabled":
                    batchDisabled();
                    break;
                case "add":
                    add();
                    break;
                case "batchDel":
                    batchDel();
                    break;
                case "log":
                    log();
            }
        });

        function log(id) {
            var tableName = 'adm_user';
            var tableId = id;
            var tableStr = 'open_id,name,birth_date,role_id,sex,,money_ratio';
            okLayer.open("日志", "/sys/pages/logInfo/" + tableName + '/' + tableId + '/' + tableStr, "90%", "90%", null, function () {
            })
        }

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
                case "log":
                    log(data.id);
                    break;
            }
        });

        function batchEnabled() {
            okLayer.confirm("确定要批量启用吗？", function (index) {
                layer.close(index);
                let idsStr = okUtils.tableBatchCheck(table);
                if (idsStr) {
                    okUtils.ajax("admUserStart", "post", {
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
                    okUtils.ajax("admUserStop", "post", {
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

        function batchDel() {
            var checkStatus = table.checkStatus('tableId');
            for (var i = 0; i < checkStatus['data'].length; i++) {
                if (checkStatus['data'][i]['is_lock'] == 0) {
                    layer.msg("只有在停用状态下才可以被删除哦", {icon: 5});
                    return;
                }
            }
            okLayer.confirm("确定要批量删除吗？", function (index) {
                layer.close(index);
                let idsStr = okUtils.tableBatchCheck(table);
                if (idsStr) {
                    okUtils.ajax("admUserDel", "post", {
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
            okLayer.open("添加账号", "admUser/create", "480px", "380px", null, function () {
                userTable.reload();
            })
        }

        function edit(data) {
            json = JSON.stringify(data);
            okLayer.open("编辑账号", "admUser/" + data.id + "/edit", "480px", "380px", null, function () {
                userTable.reload();
            })
        }


        function del(id) {
            okLayer.confirm("确定要删除吗？", function () {
                okUtils.ajax("admUserDel", "post", {
                    id: id,
                    _token: '{{csrf_token()}}'
                }, true).done(function (response) {
                    okUtils.tableSuccessMsg(response.msg);
                }).fail(function (error) {
                    console.log(error)
                });
            })
        }
    })
</script>
</body>
</html>
