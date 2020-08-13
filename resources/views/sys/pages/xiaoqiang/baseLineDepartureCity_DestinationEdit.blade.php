<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    @include('.sys.public.css')
    <link rel="stylesheet" href="{{asset('/assets/libs/layui/css/okmodules/eleTree.css')}}"/>
    @include('.sys.public.js')
</head>
<body class="page-no-scroll">
<!-- 小球样式 -->
<div class="page-loading">
    <div class="ball-loader">
        <span></span><span></span><span></span><span></span>
    </div>
</div>
<style>
    .left {
        float: left;
        width: 49%;
    }

    .right {
        float: right;
        width: 49%;
    }
</style>
<div class="ok-body">
    <!--form表单-->
    <form class="layui-form layui-form-pane ok-form" lay-filter="filter">
        <div class="left">
            <div class="layui-col-md3 eleTree">
                <div id="permissionTree" lay-filter="data1"></div>
            </div>
            <input type="hidden" name="departureCityList" id="departureCityList" value="{{$departureCityList}}">
        </div>
        <div class="right">
            <div class="layui-col-md3 eleTree">
                <div id="permissionTrees" lay-filter="data2"></div>
            </div>
            <input type="hidden" name="destinationList" id="destinationList" value="{{$destinationList}}">
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="edit">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
        <input type="hidden" name="lineId" id="lineId" value="{{$lineId}}">
        {{csrf_field()}}
    </form>
</div>
<!--js逻辑-->
<script>
    layui.use(["admin","form", "okUtils", "okLayer", "eleTree"], function () {
	    let admin = layui.admin;
        let form = layui.form;
        let okUtils = layui.okUtils;
        let okLayer = layui.okLayer;
        let eleTree = layui.eleTree;
        let $ = layui.jquery;
        admin.removeLoading();
        function initPermissionTree() {
            okUtils.ajax("{{url('/sys/pages/system/xiaoqiang/departureCityRead')}}", "get", null, true).done(function (response) {
                var destinationRead = eleTree.render({
                    elem: '#permissionTree',
                    data: response.data,
                    showCheckbox: true,
                    //expandOnClickNode: false,
                    //renderAfterExpand: false,
                    defaultExpandedKeys: [0],
                    highlightCurrent: true,
                    defaultCheckedKeys: [{{$departureCityList}}],
                    indent: 10,
                    id: 'treeId'
                });

                // input被选中事件
                eleTree.on("nodeChecked(data1)", function (d) {
                    var checkedData = destinationRead.getChecked(true, false);
                    var checkedCount = destinationRead.getChecked(true, false).length;
                    var departureCityList = '';
                    for (i = 0; i < checkedCount; i++) {
                        departureCityList = checkedData[i]['id'] + ',' + departureCityList;
                    }
                    $("#departureCityList").val(departureCityList);
                })
            }).fail(function (error) {
                console.log(error)
            });
        }

        function initPermissionTrees() {
            okUtils.ajax("{{url('/sys/pages/system/xiaoqiang/destinationRead')}}", "get", null, true).done(function (response) {
                var destinationRead = eleTree.render({
                    elem: '#permissionTrees',
                    data: response.data,
                    showCheckbox: true,
                    defaultExpandedKeys: [0],
                    highlightCurrent: true,
                    defaultCheckedKeys: [{{$destinationList}}],
                    indent: 10,
                    id: 'treeId'
                });

                // input被选中事件
                eleTree.on("nodeChecked(data2)", function (d) {
                    var checkedData = destinationRead.getChecked(true, false);
                    var checkedCount = destinationRead.getChecked(true, false).length;
                    var destinationList = '';
                    for (j = 0; j < checkedCount; j++) {
                        destinationList = checkedData[j]['id'] + ',' + destinationList;
                    }
                    $("#destinationList").val(destinationList);
                })
            }).fail(function (error) {
                console.log(error)
            });
        }

        initPermissionTree();
        initPermissionTrees();
        form.on("submit(edit)", function (data) {
            okUtils.ajax("{{url('sys/pages/system/xiaoqiang/baseLineDepartureCity_Destination/')}}", "post", data.field, true).done(function (response) {
                okLayer.greenTickMsg(response.msg, function () {
                    parent.layer.close(parent.layer.getFrameIndex(window.name));
                });
            }).fail(function (error) {
                console.log(error)
            });
            return false;
        });

    });
</script>
</body>
</html>
