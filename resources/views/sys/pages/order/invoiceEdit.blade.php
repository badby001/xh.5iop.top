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
            <div class="layui-inline">
                <label class="layui-form-label">订单编号</label>
                <div class="layui-input-inline" style="width: 100px;">
                    <input type="text" name="orderId" id="orderId" autocomplete="off" class="layui-input"
                           lay-verify="required" value="{{$db['orderId']}}" disabled>
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">开票内容</label>
                <div class="layui-input-inline" style="width: 100px;">
                    <input type="text" name="invoice" id="invoice" autocomplete="off" class="layui-input"
                           placeholder="" lay-verify="required" value="{{$db['invoice']}}" readonly>
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label"><span class="red">*</span>开票金额</label>
                <div class="layui-input-inline" style="width: 120px;">
                    <input type="text" name="amount" id="amount" autocomplete="off" class="layui-input"
                           placeholder="开票金额"  lay-verType="tips" lay-verify="required|numberX|h5" value="{{$db['pingAmount']}}"  min="1" readonly>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label"><span class="red">*</span>发票抬头</label>
                <div class="layui-input-inline" style="width: 260px;">
                    <input type="text" name="cpyName" id="cpyName" autocomplete="off" class="layui-input"
                           placeholder="公司名称 / 个人姓名" lay-verify="required" value="{{$db['cpyName']}}">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">识别号</label>
                <div class="layui-input-inline" style="width: 200px;">
                    <input type="text" name="taxpayerIdentificationNumber" id="taxpayerIdentificationNumber"
                           autocomplete="off" class="layui-input"
                           placeholder="请输入纳税人识别号" value="{{$db['taxpayerIdentificationNumber']}}">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">注册地址</label>
                <div class="layui-input-inline" style="width: 200px;">
                    <input type="text" name="addr" id="addr" autocomplete="off"
                           placeholder="发票抬头为公司名称时必填" class="layui-input">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">注册电话</label>
                <div class="layui-input-inline" style="width: 200px;">
                    <input type="text" name="phone" id="phone" autocomplete="off"
                           placeholder="发票抬头为公司名称时必填" class="layui-input">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">开户银行</label>
                <div class="layui-input-inline" style="width: 200px;">
                    <input type="text" name="accBank" id="accBank" autocomplete="off"
                           placeholder="发票抬头为公司名称时必填" class="layui-input">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">银行账号</label>
                <div class="layui-input-inline" style="width: 200px;">
                    <input type="text" name="accCard" id="accCard" autocomplete="off"
                           placeholder="发票抬头为公司名称时必填" class="layui-input">
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">备注说明</label>
            <div class="layui-input-block">
                <input type="text" name="simDesc" id="simDesc" autocomplete="off" placeholder="请输入备注说明" maxlength="250"
                       class="layui-input">
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
<script type="text/javascript">
    layui.use(["admin","form",'formX', "okUtils", "okLayer"], function () {
	    let admin = layui.admin;
        let form = layui.form;
        let formX = layui.formX;
        let okUtils = layui.okUtils;
        let okLayer = layui.okLayer;
        let $ = layui.jquery;
        form.val("filter", eval('(' + parent.json + ')'));
        //
        admin.removeLoading();
        //
        // 自定义验证规则
        // form.verify({
        //     by_sort: function (value) {
        //         if (value > 1000) {
        //             return '排序最大值请控制在1000以内';
        //         }
        //         if (value < 0) {
        //             return '排序最小值请为0';
        //         }
        //     },
        // });
        form.on("submit(edit)", function (data) {
            okUtils.ajax("{{url('sys/pages/order/invoice/'.$db['id'])}}", "{{$db['id']?'put':'post'}}", data.field, true).done(function (response) {
                okLayer.greenTickMsg(response.msg, function () {
                    parent.location.reload();
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
