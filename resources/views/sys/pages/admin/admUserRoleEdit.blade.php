<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
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
    <!--form表单-->
    <form class="layui-form layui-form-pane ok-form" lay-filter="filter">
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="red">*</span>角色名</label>
            <div class="layui-input-inline">
                <input type="text" name="title" placeholder="请输入角色名" autocomplete="off" class="layui-input"
                       lay-verify="required" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">备注</label>
            <div class="layui-input-block">
                <input type="text" name="remarks" placeholder="请输入备注" autocomplete="off" class="layui-input"
                       lay-verify="" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="by_sort" class="layui-form-label">
                <span class="x-red">*</span>排序
            </label>
            <div class="layui-input-inline">
                <input type="text" id="by_sort" name="by_sort" required="" value="0"
                       lay-verify="required|by_sort"
                       autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">*</span>数字越大排序越靠前, 最大数值为1000
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">权限</label>
            <div class="layui-input-block">
                <div id="permissionTree"></div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="edit">立即提交</button>
            </div>
        </div>
        {{csrf_field()}}
    </form>
</div>
<!--js逻辑-->
<script type="text/javascript">
    layui.use(["admin","element", "form", "tree", "okLayer", "okUtils"], function () {
	    let admin = layui.admin;
        let form = layui.form;
        let tree = layui.tree;
        let okLayer = layui.okLayer;
        let okUtils = layui.okUtils;
        let data = [{!! $data !!}]
        form.val("filter", eval('(' + parent.json + ')'));
        tree.render({
            elem: "#permissionTree",
            data: data,
            id: 'permissionTreeId',
            showCheckbox: true,
            showLine: true //是否开启连接线

        });
        tree.setChecked('permissionTreeId', {!! $role !!});
        admin.removeLoading();
        //自定义验证规则
        form.verify({
            by_sort: function (value) {
                if (value > 1000) {
                    return '排序最大值请控制在1000以内';
                }
                if (value < 0) {
                    return '排序最小值请为0';
                }
            },
        });

        form.on("submit(edit)", function (data) {
            // TODO 权限节点校验
            // 请求后台
            okUtils.ajax("{{url('sys/pages/admin/admUserRole/'.$db['id'])}}", "{{$db['id']?'put':'post'}}", data.field, true).done(function (response) {
                okLayer.greenTickMsg(response.msg, function () {
                    parent.layer.close(parent.layer.getFrameIndex(window.name));
                });
            }).fail(function (error) {
                console.log(error)
            });
            return false;
        });
    })
</script>
</body>
</html>
