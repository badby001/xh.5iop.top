<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    @include('.sys.public.css')
    @include('.sys.public.js')
</head>
<body>
<div class="ok-body">
    <!--模糊搜索区域-->
    <div class="layui-row">
        <form class="layui-form layui-col-md12 ok-search">
            <input class="layui-input" placeholder="开始日期" autocomplete="off" id="start_time" name="start_time">
            <input class="layui-input" placeholder="截止日期" autocomplete="off" id="end_time" name="end_time">
            <input class="layui-input" placeholder="操作类型" autocomplete="off" name="type" style="width: 100px;">
            <input class="layui-input" placeholder="请输入编号" autocomplete="off" name="id" style="width: 100px;">
            <input class="layui-input" placeholder="请输入关键字" autocomplete="off" name="key">
            <button class="layui-btn icon-btn" lay-filter="search" lay-submit>
                <i class="layui-icon">&#xe615;</i>搜索
            </button>
        </form>
    </div>
    <!--数据表格-->
    <table class="layui-hide" id="tableId" lay-filter="tableFilter"></table>
</div>
<!--js逻辑-->
<script>
    var json;
    layui.use(["element", "jquery", "table", "form", "laydate"], function () {
        let table = layui.table;
        let form = layui.form;
        let laydate = layui.laydate;
        let $ = layui.jquery;
        laydate.render({elem: "#start_time", type: "datetime"});
        laydate.render({elem: "#end_time", type: "datetime"});
        let logInfo = table.render({
            elem: "#tableId",
            url: '{{url('/sys/pages/logInfoRead')}}',
            title: '日志列表_{{getTime(3)}}',
            where: {'tableName': '{{$tableName}}', 'tableId': '{{$tableId}}', 'tableStr': '{{$tableStr}}'},
            page: true,
            even: true,
            size: "sm",
            cols: [[
                {field: "id", title: "序号", width: 60, align: "center"},
                {field: "this_id", title: "编号", width: 80, align: "center"},
                {field: "type", title: "操作类型", width: 100, align: "center"},
                {field: "content", title: "操作内容", width: 650},
                {field: "add_name", title: "操作人", width: 90},
                {field: "add_time", title: "操作时间", width: 145, sort: true},
            ]],
            done: function (res, curr, count) {
                //console.log(res, curr, count)
            }
        });

        form.on("submit(search)", function (data) {
            logInfo.reload({
                where: data.field,
                page: {curr: 1}
            });
            return false;
        });
    })
</script>
</body>
</html>
