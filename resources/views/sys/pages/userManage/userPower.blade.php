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
<div class="body">
    <!--form表单-->
    <form class="layui-form ok-form" lay-filter="filter">
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                <div class="layui-form-item">
                    <div class="layui-input-inline" style="width: 575px;">
                        <input type="text" name="powers" placeholder="请输入权限值,多个请用','隔开" autocomplete="off"
                               class="layui-input" lay-verType="tips" lay-verify="required"
                               value="">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-card">
                        <div class="layui-card-body" style="height: 385px;overflow: auto;box-sizing: border-box;">
                            <div id="layDemoTransfer1" style="min-width: 490px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-form-item" style="float: right;">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit lay-filter="edit">立即提交</button>
                </div>
            </div>
            <input name="roleId" id="roleId" type="text" hidden value="" lay-verify="required">
        </div>
        {{csrf_field()}}
    </form>
</div>
<!--js逻辑-->
<script type="text/javascript">
    layui.use(["admin", "element", "form", "transfer", "okLayer", "okUtils"], function () {
        let admin = layui.admin;
        let form = layui.form;
        let transfer = layui.transfer;
        let okLayer = layui.okLayer;
        let okUtils = layui.okUtils;
        let $ = layui.jquery;
        form.val("filter", eval('(' + parent.json + ')'));
        admin.removeLoading();
        // 穿梭框
        transfer.render({
            elem: '#layDemoTransfer1',
            id: 'layDemoTransfer1', //定义索引
            showSearch: true,
            title: ['未选择', '已选择'],
            data: {!! $db !!},
            parseData: function (res) {
                return {
                    "value": res.id, //数据值
                    "title": res.title + '(' + res.count + ')' + ' [' + res.code + ']' //数据标题
                }
            },
            onchange: function (data, index) {
                var values = '';
                let getData = transfer.getData('layDemoTransfer1');
                getData.forEach((item, index) => {
                    values += item.value + ',';
                    return;
                })
                $("#roleId").val(values);
            }
        });

        form.on("submit(edit)", function (data) {
            okUtils.ajax("{{url('sys/pages/system/userPowerStore')}}", "post", data.field, true).done(function (response) {
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
