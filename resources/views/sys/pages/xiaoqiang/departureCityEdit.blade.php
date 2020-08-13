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
            <label class="layui-form-label"><span class="red">*</span>名称</label>
            <div class="layui-input-inline">
                <input type="text" name="name" placeholder="请输入名称" autocomplete="off" class="layui-input"
                       lay-verify="required" value="{{$db['name']}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-block">
                <input type="radio" name="is_lock" value="0" title="启用" {{$db['is_lock']==0?'checked':''}}>
                <input type="radio" name="is_lock" value="1" title="停用" {{$db['is_lock']==1?'checked':''}}>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="by_sort" class="layui-form-label">
                <span class="x-red">*</span>排序
            </label>
            <div class="layui-input-inline">
                <input type="text" id="by_sort" name="by_sort" required="" value="{{$db['by_sort']}}"
                       lay-verify="required|by_sort"
                       autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">*</span>数字越大排序越靠前, 最大数值为1000
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="edit">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
        {{csrf_field()}}
    </form>
</div>
<!--js逻辑-->
<script>
    layui.use(["admin","form", "okUtils", "okLayer"], function () {
	    let admin = layui.admin;
        let form = layui.form;
        let okUtils = layui.okUtils;
        let okLayer = layui.okLayer;
        let $ = layui.jquery;
        form.val("filter", eval('(' + parent.json + ')'));
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
            okUtils.ajax("{{url('sys/pages/system/xiaoqiang/departureCity/'.$db['id'])}}", "put", data.field, true).done(function (response) {
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
