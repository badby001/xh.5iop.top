<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>修改密码</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    @include('.sys.public.css')
    @include('.sys.public.js')
</head>
<body class="childrenBody seting-pass page-no-scroll">
<!-- 小球样式 -->
<div class="page-loading">
    <div class="ball-loader">
        <span></span><span></span><span></span><span></span>
    </div>
</div>
<form class="layui-form changePwd">
    <div class="layui-form-item">
        <label class="layui-form-label">用户姓名</label>
        <div class="layui-input-block">
            <input type="text" value="{{_admName()}}" disabled class="layui-input layui-disabled">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">旧密码</label>
        <div class="layui-input-block">
            <input name="old_pwd" type="password" value="" placeholder="请输入旧密码" lay-vertype="tips" lay-verify="required|old_pwd"
                   class="layui-input pwd">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">新密码</label>
        <div class="layui-input-block">
            <input name="pass_word" type="password" value="" placeholder="请输入新密码" lay-vertype="tips"  min="6" max="18" lay-verify="required|pass"
                   class="layui-input pwd">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="" lay-filter="changePwd">立即修改</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>
<script type="text/javascript">
    layui.use(["admin",'form', 'layer'], function () {
        let form = layui.form,
            layer = layui.layer,
            $ = layui.jquery,
            $form = $('form');
        let admin = layui.admin;
        admin.removeLoading();
        //添加验证规则verify
        form.verify({
            pass: [
                /^[\S]{6,18}$/
                , '密码必须6到18位，且不能出现空格'
            ]
        });
        //修改密码
        form.on("submit(changePwd)", function (data) {
            var index = layer.msg('提交中，请稍候', {icon: 16, time: false, shade: 0.8});
            setTimeout(function () {
                $.post("{{url('sys/pages/admPwd')}}", {_token: "{{csrf_token()}}", 'data': data.field},
                    function (data) {
                        if (data.success) {
                            layer.msg(data.msg);
                            $(".pwd").val('');
                        } else {
                            layer.msg(data.msg, {icon: 5});
                        }
                    }, "json");
                layer.close(index);
            }, 1000);
            return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
        })
    });
</script>
</body>
</html>
