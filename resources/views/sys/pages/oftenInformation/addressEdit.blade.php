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
            <label class="layui-form-label"><span class="red">*</span>姓名</label>
            <div class="layui-inline">
                <input type="text" name="name" placeholder="请输入收货人姓名" autocomplete="off" class="layui-input"
                       lay-vertype="tips" lay-verify="required" maxlength="50">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="red">*</span>电话</label>
            <div class="layui-inline">
                <input type="text" name="mobile" placeholder="请输入收货人电话" autocomplete="off" class="layui-input"
                       lay-vertype="tips" lay-verify="required|phone">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="red">*</span>省市区选择</label>
            <div class="layui-input-block" style="width: 260px;">
                <input id="area_code" placeholder="请选择 / 支持输入关键字" class="layui-hide"
                       lay-vertype="tips" lay-verify="required" value="{{$db['area_code']}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="red">*</span>详细地址</label>
            <div class="layui-inline" style="width: 260px;">
                <input type="text" name="addr" placeholder="请输入街道门牌、楼层房间号等信息" autocomplete="off" class="layui-input"
                       lay-vertype="tips" lay-verify="required" maxlength="250">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="red">*</span>邮政编码</label>
            <div class="layui-inline">
                <input type="text" name="postcode" placeholder="请输入邮政编码" autocomplete="off" class="layui-input"
                       lay-vertype="tips" lay-verify="required" maxlength="6">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="red">*</span>默认地址</label>
            <div class="layui-input-block">
                <input type="radio" name="is_default" value="1" title="是" {{ $db['is_default']==1?'checked':''}}>
                <input type="radio" name="is_default" value="0" title="否" {{ $db['is_default']==0?'checked':''}}>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="edit">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
        <input name="area_code" type="text" hidden value="{{$db['area_code_value']}}">
        {{csrf_field()}}
    </form>
</div>
<!--js逻辑-->
<script type="text/javascript">
    layui.use(["admin", "form", "okUtils", "okLayer", 'cascader'], function () {
        let admin = layui.admin;
        let form = layui.form;
        let okUtils = layui.okUtils;
        let okLayer = layui.okLayer;
        let cascader = layui.cascader;
        let $ = layui.jquery;
        let citysData ={!! getCitysData() !!};
        // 省市区选择
        cascader.render({
            elem: '#area_code',
            data: citysData,
            itemHeight: '250px',
            filterable: true,
            onChange: function (values, data) {
                // console.log(data.value);
                $("input[type=text][name=area_code]").val(data.value);
            }
        });
        form.val("filter", eval('(' + parent.json + ')'));
        admin.removeLoading();

        form.on("submit(edit)", function (data) {
            okUtils.ajax("{{url('sys/pages/oftenInformation/address/'.$db['id'])}}", "{{$db['id']?'put':'post'}}", data.field, true).done(function (response) {
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
