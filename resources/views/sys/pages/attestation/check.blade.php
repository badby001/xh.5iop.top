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
    <form class="layui-form ok-form" lay-filter="filter">

        <div class="layui-tab-content" style="height: 100px;">
            <div class="layui-tab-item layui-show">
                <div class="layui-form-item">
                    <label class="layui-form-label">旅行社名称</label>
                    <div class="layui-input-inline">
                        <input type="text" name="attestation_tourist_agency" class="layui-input" readonly>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">姓名</label>
                    <div class="layui-input-inline">
                        <input type="text" name="name" class="layui-input" readonly>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">手机号码</label>
                    <div class="layui-input-inline">
                        <input type="text" name="mobile" class="layui-input" value="{{$db['mobile']}}" readonly>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">地址</label>
                    <div class="layui-input-inline" style="width: 440px;">
                        <input type="text" name="address" class="layui-input" value="{{$db['address']}}" readonly>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">营业执照</label>
                    <div class="layui-input-inline" style="width: 440px;">
                        <input type="text" name="attestation_business_license_img" class="layui-input" readonly>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-form-mid layui-word-aux">
                            <a href="javascript:" title="{{$db['attestation_business_license_img']}}"
                               onclick="javascript:window.open(this.title)">查看原图</a>
                        </div>
                    </div>
                    <div class="layui-input-block">
                        <label class="layui-form"><img class="layui-upload-img"
                                                       src="{{$db['attestation_business_license_img']}}"></label>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span class="red">*</span>审核</label>
                    <div class="layui-input-block">
                        <input type="radio" name="state" value="2" title="认证成功" checked>
                        <input type="radio" name="state" value="-1" title="认证失败">
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit lay-filter="check">立即提交</button>
                </div>
            </div>
        </div>
        {{csrf_field()}}
    </form>
</div>
<!--js逻辑-->
<script type="text/javascript">
    layui.use(["admin", "element", "form", "okLayer", "jquery", "okUtils"], function () {
        let admin = layui.admin;
        let form = layui.form;
        let okLayer = layui.okLayer;
        let okUtils = layui.okUtils;
        let $ = layui.jquery;
        form.val("filter", eval('(' + parent.json + ')'));
        admin.removeLoading();

        form.on("submit(check)", function (data) {
            okUtils.ajax("{{url('sys/pages/system/attestation/'.$db['id'])}}", "PUT", data.field, true).done(function (response) {
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
