<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
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
    <form class="layui-form layui-form-pane ok-form">
        <div class="layui-form-item">
            <label class="layui-form-label">子名称</label>
            <div class="layui-input-block">
                <input type="text" name="title" placeholder="请输入子项路由名称" autocomplete="off" class="layui-input"
                       lay-verify="required" value="{{$title ?? ''}}">
            </div>
        </div>
        @if($is_type==0)
        <div class="layui-form-item">
            <label class="layui-form-label">子地址</label>
            <div class="layui-input-block">
                <input type="text" name="href" placeholder="请输入子项路由地址" autocomplete="off" class="layui-input"
                       lay-verify="required" value="{{$href ?? "/"}}">
            </div>
        </div>
        @endif
        <div class="layui-form-item">
            <label class="layui-form-label">类型</label>
            <div class="layui-input-block">
                <select name="is_type" lay-verify="required">
                    <option value=""></option>
                    <option value="8" {{$is_type==8 ? 'selected':''}}>数据</option>
                    <option value="4" {{$is_type==4 ? 'selected':''}}>按钮</option>
                    <option value="0" {{$is_type==0 ? 'selected':''}}>页面</option>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">图标资源</label>
            <div class="layui-input-block">
                <select name="font_family" lay-verify="required">
                    <option value=""></option>
                    <option value="ok-icon" {{$font_family=='ok-icon' ? 'selected':''}}>ok-icon</option>
                    <option value="layui-icon" {{$font_family=='layui-icon' ? 'selected':''}}>layui-icon</option>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">ICON</label>
            <div class="layui-input-block">
                <input type="text" name="icon" placeholder="请输入icon" autocomplete="off" class="layui-input"
                       lay-verify="required" value="{{$icon ?? 'layui-icon-right'}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="x-red">*</span>系统路由</label>
            <div class="layui-input-inline">
                <select name="is_sys" lay-verify="required">
                    <option value=""></option>
                    <option value="0" {{$is_sys=='0'?'selected':''}}>否</option>
                    <option value="1" {{$is_sys=='1'?'selected':''}}>是</option>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="phone" class="layui-form-label">
                <span class="x-red">*</span>排序
            </label>
            <div class="layui-input-inline">
                <input type="text" id="by_sort" name="by_sort" required="" value="{!! $by_sort ?? 0 !!}"
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
        <input type="hidden" name="father_id" value="{{$father_id}}">
        <input type="hidden" name="id" value="{{$id??0}}">
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
            okUtils.ajax("{{url('sys/pages/system/storeSon/')}}", "post", data.field, true).done(function (response) {
                okLayer.greenTickMsg(response.msg + ' 稍后请自行刷新', function () {
                    parent.layer.close(parent.layer.getFrameIndex(window.name));
                    //parent.location.reload();
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
