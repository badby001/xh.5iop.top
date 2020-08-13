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
                        <label class="layui-form-label">产品分类</label>
                        <div class="layui-input-inline">
                            <select name="classify" lay-verify="">
                                <option value="" selected>请选择产品分类</option>
                                <option value="-1">未归类</option>
                                <option value="0">跟团游</option>
                                <option value="1">商品</option>
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
                        <div class="layui-card-header1" data-step="3" data-intro="您好! 请在文本框内输产品名称的关键字"
                             data-position="bottom">
                            <label class="layui-form-label">搜索</label>
                            <div class="layui-input-inline">
                                <input type="text" class="layui-input" placeholder="请输入关键字 | 产品名称/标签" autocomplete="off"
                                       name="key">
                            </div>
                        </div>
                    </div>
                    <div class="layui-inline" style="padding-right: 110px;">
                        <button class="layui-btn icon-btn" lay-filter="search" lay-submit>
                            <i class="layui-icon">&#xe615;</i>搜索
                        </button>
                        <button id="tbBasicExportBtn" class="layui-btn icon-btn" type="button">
                            <div class="layui-card-header1" data-step="4" data-intro="您好! 请先在表格区域勾选需要导出的列, 然后进行个性化导出"
                                 data-position="bottom">
                                <i class="layui-icon">&#xe67d;</i>导出
                            </div>
                        </button>
                    </div>
                </div>
            </form>
            <!-- 数据表格 -->
            <table id="tableId" lay-filter="tableFilter"></table>
        </div>
    </div>
</div>
<!-- js部分 -->
<script>
    var json;
    layui.use(["admin", 'form', 'table', "laydate", "okLayer", "okUtils", "okLayx", "okNprogress", "okToastr", 'dropdown', "introJs"], function () {
        let admin = layui.admin;
        let form = layui.form;
        let table = layui.table;
        let laydate = layui.laydate;
        let okLayer = layui.okLayer;
        let okUtils = layui.okUtils;
        let okLayx = layui.okLayx;
        let okNprogress = layui.okNprogress;
        let okToastr = layui.okToastr;
        let dropdown = layui.dropdown;
        let introJs = layui.introJs;
        let $ = layui.jquery;
        okNprogress.start();
        okToastr.info("当前页面仅可以维护表头中带有 * 号的信息, 其他信息请在小强系统中维护");
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

        /* 渲染表格 */
        let baseLineTable = table.render({
            elem: '#tableId',
            url: '{{url('/sys/pages/system/xiaoqiang/baseLineRead')}}',
            limit: '{!! frame()['limit'] !!}',
            limits: [{!! frame()['limits'] !!}],
            title: '小强产品列表_{{getTime(3)}}',
            page: true,
            even: true,
            toolbar: '<div class="layui-btn-group" style="float:right;padding:5px 15px 0px 20px;">' +
               '<div class="layui-card-header1" data-step="5" data-intro="您好! 请先在表格区域勾选需要导出的列, 然后进行 分类/标签/出发城市与与目的地 的批量设置" data-position="bottom">'+
                @if(hasPower(228))'<div class="dropdown-menu">' +
                '    <button class="layui-btn icon-btn layui-btn-sm" type="button"> &nbsp;批量操作 <i class="layui-icon layui-icon-drop"></i></button>' +
                '    <ul class="dropdown-menu-nav">' +
                '   <li><a lay-event="batchEdit_classify">修改分类</a></li>'+
                '        <li><a lay-event="batchEdit_tag">修改标签</a></li>' +
                '        <li><a lay-event="batchDepartureCity_Destination">设置出发城市/目的地</a></li>' +
                '    </ul>' +
                '</div>' + @endif
                    '<ul style="float: right;">' +
                '<button class="layui-btn layui-btn-sm layui-btn-primary" lay-event="useGuidance">使用指引</button>' +
                '</ul>' +
                '</div>'+
                '</div>',
            size: "sm",
            cols: [[
                {type: "checkbox", fixed: "left"},
                {field: "id", title: "编号", width: 80},
                {field: "classify_name", title: "*产品分类", width: 100, sort: true},
                {field: "line_share_name", title: "公共", width: 55},
                {field: "line_type_name", title: "产品类别", width: 120},
                {field: "tag", title: "*产品标签", width: 120},
                {field: "type", title: "产品类型", width: 80},
                {field: "code", title: "产品代码", width: 120},
                {field: "title", title: "产品名称", width: 360},
                {field: "tags", title: "简要描述", width: 220},
                {field: "planCount", title: "团数", width: 70, sort: true},
                {field: "days", title: "天数", width: 70, sort: true},
                {field: "night", title: "晚数", width: 70, sort: true},
                {field: "satisfaction", title: "*满意度", width: 85, sort: true, edit: true},
                {field: "bySort", title: "*权重", width: 75, sort: true, edit: true},
                {field: "cpyName", title: "供应商", width: 150},
                {field: "cpyLeader", title: "联系人", width: 80},
                {field: "cpyMobile", title: "联系电话", width: 120},
                {field: "operate_type", title: "经营类型", width: 80},
                {field: "adult_price", title: "成人价(同行)", width: 120, sort: true},
                {field: "child_price", title: "小人价(同行)", width: 120, sort: true},
                {field: "sadult_price", title: "成人价(市场)", width: 120, sort: true},
                {field: "schild_price", title: "小人价(市场)", width: 120, sort: true},
                {field: "img", title: "图片地址", width: 80},
                {field: "video", title: "视频地址", width: 80},
                {field: "adm_name", title: "创建者", width: 90},
                {field: "add_time", title: "创建时间", width: 145, sort: true},
                {
                    title: "操作", width: 100, align: "center", fixed: "right", templet: function (d) {
                        var edit = "@if(hasPower(228))<a href=\"javascript:\" title=\"修改\" lay-event=\"edit\"><i class=\"layui-icon\">&#xe642;</i></a>@endif";
                        return edit;
                    }
                }
            ]],
            done: function (res, curr, count) {
                admin.removeLoading();
                okNprogress.done();
                // console.log(res, curr, count);
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
            baseLineTable.reload({
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
                case "batchEdit_classify":
                    batchEdit_classify();
                    break;
                case "batchEdit_tag":
                    batchEdit_tag();
                    break;
                case "batchDepartureCity_Destination":
                    batchDepartureCity_Destination();
                    break;
            }
            dropdown.hideAll();
        });

        function batchEdit_classify() {
            let idsStr = okUtils.tableBatchCheck(table);
            if (idsStr) {
                okLayer.open("批量编辑产品分类", "baseLine/" + idsStr + "/edit_classify", "50%", "50%", null, function () {
                    okNprogress.start();
                    baseLineTable.reload();
                })
            }
        }

        function batchEdit_tag() {
            let idsStr = okUtils.tableBatchCheck(table);
            if (idsStr) {
                okLayer.open("批量编辑产品标签", "baseLine/" + idsStr + "/edit_tag", "50%", "50%", null, function () {
                    okNprogress.start();
                    baseLineTable.reload();
                })
            }
        }

        function batchDepartureCity_Destination() {
            let idsStr = okUtils.tableBatchCheck(table);
            if (idsStr) {
                okLayer.open("批量编辑出发城市/目的地", "baseLineDepartureCity_Destination/" + idsStr + "/edit", "45%", "97%", null, function () {
                    okNprogress.start();
                    baseLineTable.reload();
                })
            }
        }

        /* 表格工具条点击事件 */
        table.on('tool(tableFilter)', function (obj) {
            var data = obj.data; // 获得当前行数据
            switch (obj.event) {
                case "edit":
                    edit(data);
                    break;
            }
            dropdown.hideAll();
        });

        function edit(data) {
            json = JSON.stringify(data);
            okLayer.open("编辑产品", "baseLine/" + data.id + "/edit", "50%", "50%", null, function () {
                okNprogress.start();
                baseLineTable.reload();
            })
        }


        // 导出excel
        $('#tbBasicExportBtn').click(function () {
            let idsStr = okUtils.tableBatchCheck(table);
            if (idsStr) {
                table.exportFile(baseLineTable.config.id, idsStr.data, 'xls');
            }
        });


        //监听单元格编辑
        table.on('edit(tableFilter)', function (obj) {
            okUtils.ajax("baseLineTableEdit", "post", {
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

    });
</script>
</body>
</html>
