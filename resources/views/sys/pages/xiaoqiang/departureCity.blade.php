<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    @include('.sys.public.css')
    <link rel="stylesheet" href="{{asset('/assets/libs/layui/css/okmodules/eleTree.css')}}"/>
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
                        <div class="layui-card-header1" data-step="3" data-intro="您好! 请在文本框内输产品名称的关键字"
                             data-position="bottom">
                            <label class="layui-form-label">搜索</label>
                            <div class="layui-input-inline">
                                <input type="text" class="layui-input" placeholder="请输入关键字 | 产品名称" autocomplete="off"
                                       name="key">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="departureCity_id" id="departureCity_id" value="">
                    <div class="layui-inline" style="padding-right: 110px;">
                        <button class="layui-btn icon-btn" lay-filter="search" lay-submit>
                            <i class="layui-icon">&#xe615;</i>搜索
                        </button>
                    </div>
                </div>
            </form>
            <!--数据目录-->
            <div class="layui-col-md3 eleTree">
                <div id="permissionTree" lay-filter="data1"></div>
            </div>
            <!--数据表格-->
            <div class="layui-col-md9">
                <table id="tableId" lay-filter="tableFilter"></table>
            </div>
        </div>
    </div>
</div>
<!--js逻辑-->
<script>
    layui.use(["admin","element", "table", "form", "laydate", "okLayer", "eleTree", "okUtils", "okLayx", "introJs"], function () {
	    let admin = layui.admin;
        let table = layui.table;
        let form = layui.form;
        let util = layui.util;
        let laydate = layui.laydate;
        let okLayer = layui.okLayer;
        let eleTree = layui.eleTree;
        let okUtils = layui.okUtils;
        let okLayx = layui.okLayx;
        let introJs = layui.introJs;
        let $ = layui.jquery;
        /* 渲染时间选择 */
        laydate.render({
            elem: 'input[name="dateRange"]',
            type: 'date',
            range: true,
            trigger: 'click'
        });
        util.fixbar({});
        okLayx.notice({
            title: "温馨提示",
            type: "warning",
            message: "{!! frame()['message'] !!}"
        });
        function initPermissionTree() {
            okUtils.ajax("{{url('/sys/pages/system/xiaoqiang/departureCityRead')}}", "get", null, true).done(function (response) {
                eleTree.render({
                    elem: '#permissionTree',
                    data: response.data,
                    @if(hasPower(235)) draggable: true, @endif
                    showLine: true,
                    expandOnClickNode: false,
                    renderAfterExpand: false,
                    defaultExpandedKeys: [0],
                    contextmenuList: [@if(hasPower(240))"add.async", @endif @if(hasPower(235))"edit.async", @endif @if(hasPower(238))"remove.async", @endif @if(hasPower(235)){eventName: "set",text: "设置"}@endif],
                    indent: 10,
                    id: 'treeId'
                });
                // 节点被拖拽事件
                eleTree.on("nodeDrag(data1)", function (d) {
                    okUtils.ajax("{{url('sys/pages/system/xiaoqiang/departureCityTarget')}}", "post", {
                        'current': d.current.data.currentData,
                        'target': this[0]['dataset']['id'],
                        '_token': '{{csrf_token()}}'
                    }, true).done(function (response) {
                        //进行排序
                        okUtils.ajax("{{url('sys/pages/system/xiaoqiang/departureCitySort')}}", "post", {
                            'sort': d.target.data.currentData.children,
                            '_token': '{{csrf_token()}}'
                        }, true)
                        layer.msg(response.msg, {icon: 1});
                    }).fail(function (error) {
                        // 取消编辑
                        d.stop();
                        console.log(error)
                    })
                });
                // 添加子节点事件
                eleTree.on("nodeAppend(data1)", function (d) {
                    // console.log(d.data);    // 点击节点对应的数据
                    // console.log(d.node);    // 点击的dom节点
                    // console.log(this);      // 与d.node相同
                    //console.log(d.newData); // 新增加的节点数据
                    okUtils.ajax("{{url('sys/pages/system/xiaoqiang/departureCity')}}/", "post", {
                        'title': d.newData.label,
                        'father_id': d.data.id,
                        '_token': '{{csrf_token()}}'
                    }, true).done(function (response) {
                        // 异步操作必须调用之后才会修改
                        d.setData({             // 自定义数据（异步操作必须调用之后才会添加数据，如果不需要修改值，可以不传参数）
                            id: response.id,
                            label: response.title
                        });
                        layer.msg(response.msg, {icon: 1});
                    }).fail(function (error) {
                        // 取消编辑
                        d.stop();
                        console.log(error)
                    });
                });
                // 节点被编辑事件
                eleTree.on("nodeEdit(data1)", function (d) {
                    // console.log(d.data);        // 点击节点对应的数据
                    // console.log(d.node);        // 点击的dom节点
                    // console.log(d.value);       // 新输入的值
                    // console.log(this);          // 与d.node相同
                    if (d.data.id == 0) {
                        layer.msg('根目录不允许被修改', {icon: 2});
                        d.stop();
                    } else {
                        okUtils.ajax("{{url('sys/pages/system/xiaoqiang/departureCityNodeEdit')}}/" + d.data.id, "post", {
                            'title': d.value,
                            '_token': '{{csrf_token()}}'
                        }, true).done(function (response) {
                            // 异步操作必须调用之后才会修改
                            d.async();
                            layer.msg(response.msg, {icon: 1});
                        }).fail(function (error) {
                            // 取消编辑
                            d.stop();
                            console.log(error)
                        })
                    }
                });
                // 节点被删除事件
                eleTree.on("nodeRemove(data1)", function (d) {
                    //console.log(d.data);        // 点击节点对应的数据
                    //console.log(d.node);        // 点击的dom节点
                    if (d.data.id == 0) {
                        layer.msg('根目录不允许被删除', {icon: 2});
                        d.stop();
                    } else {
                        okUtils.ajax("{{url('sys/pages/system/xiaoqiang/departureCity')}}/" + d.data.id, "DELETE", {
                            '_token': '{{csrf_token()}}'
                        }, true).done(function (response) {
                            // 异步操作必须调用之后才会修改
                            d.async();
                            layer.msg(response.msg, {icon: 1});
                        }).fail(function (error) {
                            // 取消编辑
                            d.stop();
                            console.log(error)
                        })
                    }
                });
                //扩展设置
                eleTree.on("nodeSet(data1)", function (d) {
                    // console.log(d.data.id);
                    if (d.data.id == 0) {
                        layer.msg('根目录不允许被设置', {icon: 2});
                    } else {
                        okLayer.open("编辑导航", "departureCity/" + d.data.id + "/edit", "90%", "90%", null, function () {
                            layer.msg('当前属于频繁操作功能, 请自行手动进行刷新', {icon: 7});
                        })
                    }
                });
                // 节点点击事件
                eleTree.on("nodeClick(data1)", function (d) {
                    // console.log(d.data.currentData.id);    // 点击节点对应的数据
                    // console.log(d.event);   // event对象,{eventName: "test", text: "test"}
                    // console.log(d.node);    // 点击的dom节点
                    // console.log(this);      // 与d.node相同
                    $('#departureCity_id').val(d.data.currentData.id);
                    initPermissionTable(d.data.currentData.id);
                });
                //
                initPermissionTable(0);
            }).fail(function (error) {
                console.log(error)
            });
        }

        function initPermissionTable(departureCity_id) {
            let navTable = table.render({
                elem: '#tableId',
                url: '{{url('/sys/pages/system/xiaoqiang/departureCityLineRead')}}',
                where: {'departureCity_id': departureCity_id},
                limit: '{!! frame()['limit'] !!}',
                limits: [{!! frame()['limits'] !!}],
                title: '出发城市关联线路列表_{{getTime(3)}}',
                page: true,
                even: true,
                toolbar: '<div class="layui-btn-container">\n' +
                    @if(hasPower(242)) '<button class="layui-btn layui-btn-sm layui-btn-normal" lay-event="batchEnabled">批量启用</button>\n' +
                    '<button class="layui-btn layui-btn-sm layui-btn-warm" lay-event="batchDisabled">批量停用</button>\n' + @endif
                        @if(hasPower(238)) '<button class="layui-btn layui-btn-sm layui-btn-danger" lay-event="batchDel">批量删除</button>\n' + @endif
                        '<ul style="float: right;">' +
                    '<button class="layui-btn layui-btn-sm layui-btn-primary" lay-event="useGuidance">使用指引</button>' +
                    '</ul>' +
                        '</div>',
                size: "sm",
                cols: [[
                    {type: "checkbox", fixed: "left"},
                    {field: "line_id", title: "产品编号", width: 100},
                    {field: "line_title", title: "产品名称", width: 360},
                    {field: "departureCity_name", title: "城市名称", width: 100, sort: true},
                    {field: "planCount", title: "团数", width: 70, sort: true},
                    {field: "by_sort", title: "排序", width: 75, sort: true, edit: true},
                    {field: "is_lock_name", title: "状态", width: 85, sort: true},
                    {field: "add_name", title: "创建者", width: 90},
                    {field: "add_time", title: "创建时间", width: 145, sort: true},
                    {field: "up_name", title: "最后修改人", width: 100},
                    {field: "up_time", title: "修改时间", width: 145, sort: true},
                    {
                        title: "操作", width: 100, align: "center", fixed: "right", templet: function (d) {
                            var del = "@if(hasPower(238))<a href=\"javascript:\" title=\"删除\" lay-event=\"del\"><i class=\"layui-icon\">&#xe640;</i></a>@endif";
                            if (d.is_lock == 1) {
                                return del;
                            } else {
                                return '';
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
                     data.field.start_time = searchDate[0]+' 00:00:00.000';
                data.field.end_time = searchDate[1]+' 23:59:59.999';
                } else {
                    data.field.start_time = '';
                    data.field.end_time = '';
                }
                data.field.dateRange = undefined;
                navTable.reload({
                    where: data.field,
                    page: {curr: 1}
                });
                return false;
            });

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
                    case "batchDel":
                        batchDel();
                        break;
                }
            });

            table.on("tool(tableFilter)", function (obj) {
                let data = obj.data;
                switch (obj.event) {
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
                        okUtils.ajax("departureCityStart", "post", {
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
                        okUtils.ajax("departureCityStop", "post", {
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
                        okUtils.ajax("departureCityDel", "post", {
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

            function del(id) {
                okLayer.confirm("确定要删除吗？", function () {
                    okUtils.ajax("departureCityDel", "post", {
                        id: id,
                        _token: '{{csrf_token()}}'
                    }, true).done(function (response) {
                        okUtils.tableSuccessMsg(response.msg);
                    }).fail(function (error) {
                        console.log(error)
                    });
                })
            }

            //监听单元格编辑
            table.on('edit(tableFilter)', function (obj) {
                okUtils.ajax("departureCityTableEdit", "post", {
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
        }

        initPermissionTree();
    });
</script>
</body>
</html>
