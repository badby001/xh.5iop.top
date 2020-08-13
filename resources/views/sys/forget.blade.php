<!DOCTYPE html>
<html lang="en" class="page-fill">
<head>
    <meta charset="UTF-8">
    <title>{!! site()['siteWebName'] !!}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="keywords" content="{!! site()['keywords'] !!}">
    <meta name="description" content="{!! site()['description'] !!}">
    <link rel="shortcut icon" href="{!! site()['ico'] !!}" type="image/x-icon"/>
    @include('.sys.public.css')
    @include('.sys.public.js')
    <style>
        #login form.layui-form {
            margin: 0;
            transform: translate(-50%, -50%);
        }

        .register .tit {
            padding-top: 15px;
            text-align: center;
            font-size: 18px;
        }

        .register .code-box {
            display: flex;
        }

        .register .code-box .btn-auth-code {
            margin-left: 10px;
        }
    </style>
</head>
<body class="page-fill">
<div class="page-fill register" id="login">
    <form class="layui-form ">
        <div class="layui-form-item tit">忘记密码</div>
        <div class="layui-form-item input-item">
            <label for="open_id">手机号码</label>
            <input type="text" lay-verify="required|phone" name="open_id" placeholder="请输入手机号码" autocomplete="off"
                   id="open_id" maxlength="11" class="layui-input">
        </div>
        <div class="layui-form-item input-item captcha-box">
            <label for="captcha">图形验证码</label>
            <input type="text" lay-verify="required|captcha" name="captcha" placeholder="请输入图形验证码" autocomplete="off"
                   id="captcha" maxlength="4" class="layui-input">
            <div class="img ok-none-select"><img id="captchaImg" src="{{url(site()['callWebApi'].'api/code/imgCode')}}"
                                                 style="height: 38px; width: 106px;"></div>
        </div>
        <div class="layui-form-item input-item code-box">
            <label for="authCode">短信验证码</label>
            <input type="text" lay-verify="required" name="authCode" placeholder="请输入短信验证码" id="authCode"
                   autocomplete="off" maxlength="4" class="layui-input">
            <button type="button" class="layui-btn btn-auth-code">获取验证码</button>
        </div>
        <div class="layui-form-item input-item">
            <label for="pass_word">密码</label>
            <input type="password" lay-verify="required|pass_word" name="pass_word" placeholder="请输入密码"
                   autocomplete="off"
                   id="pass_word" maxlength="18" class="layui-input">
        </div>
        <div class="layui-form-item input-item">
            <label for="com_pass_word">确认密码</label>
            <input type="password" lay-verify="required|com_pass_word" name="pass_word" placeholder="请确认密码"
                   autocomplete="off" id="com_pass_word" class="layui-input">
        </div>
        <div class="layui-form-item">
            <button class="layui-btn layui-block" lay-filter="resetPassword" name="resetPassword" lay-submit="">确定设置</button>
        </div>
        <div class="login-link">
            <a href="login">有账号去登录</a>
        </div>
    </form>
</div>
<!--js逻辑-->
<script>
    layui.use(["form", "okLayer"], function () {
        let form = layui.form;
        let $ = layui.jquery;
        let okLayer = layui.okLayer;
        let regPhone = /^[1][0-9]{10}$/;
        /**手机号验证**/
        let setInter = '';
        /**定时器对象*/
        let second = 60;//设置时间
        /**
         * 初始化验证码
         */
        captchaImg.onclick = refreshCode;

        function refreshCode() {
            $("#captchaImg").attr('src', "{{url(site()['callWebApi'].'api/code/imgCode')}}");
        }

        /**
         * 数据校验
         */
        form.verify({
            pass_word: [/^[\S]{6,18}$/, "密码必须6到12位，且不能出现空格"],
            open_id: [
                regPhone,
                '输入的手机号格式不正确，请重新输入'
            ],
            com_pass_word: function (val) {
                let pass_word = $("#pass_word").val();
                return pass_word == val ? '' : "两次密码不一致";
            }
        });

        /**
         * 表单提交
         */
        form.on("submit(resetPassword)", function (data) {
            $.post("{{url('sys/forgetPwd')}}", {_token: "{{csrf_token()}}", 'data': data.field},
                function (data) {
                    if (data.success) {
                        okLayer.greenTickMsg("密码重置成功,去登陆", function () {
                            window.location = "./login";
                        });
                    } else {
                        $("#open_id").val(data.open_id);
                        $("#pass_word").val(data.pass_word);
                        layer.msg(data.msg, {icon: 5});
                    }
                }, "json");
            return false;
        });

        /**
         * 表单input组件单击时
         */
        $("#login .input-item .layui-input").click(function (e) {
            e.stopPropagation();
            $(this).addClass("layui-input-focus").find(".layui-input").focus();
        });

        /**
         * 表单input组件获取焦点时
         */
        $("#login .layui-form-item .layui-input").focus(function () {
            $(this).parent().addClass("layui-input-focus");
        });

        /**
         * 表单input组件失去焦点时
         */
        $("#login .layui-form-item .layui-input").blur(function () {
            $(this).parent().removeClass("layui-input-focus");
            if ($(this).val() != "") {
                $(this).parent().addClass("layui-input-active");
            } else {
                $(this).parent().removeClass("layui-input-active");
            }
        });

        /**获取验证码**/
        $('.btn-auth-code').click(function () {
            let that = $(this),
                open_id = $("#open_id").val();
            if ($(this).hasClass("layui-btn-disabled")) {
                return;
            }
            //
            $.post({
                url: '{{url('/sys/postXqCaptcha')}}',
                data: JSON.stringify({
                    openId: $("#open_id").val(),
                    imgCode: $("#captcha").val(),
                    _token: '{{csrf_token()}}'
                }),
                contentType: "application/json;charset=UTF-8",
                success: function (data) {
                    if (data.code == 0) {
                        layer.msg('短信验证码已发送, 5分钟内有效', {icon: 6});
                    } else {
                        layer.msg(data.msg, {icon: 5});
                        // if (data.status == 100028) {
                        //     clearInterval(setInter);
                        //     that.removeClass("layui-btn-disabled");
                        //     that.text("重新获取");
                        //     setInter = "";
                        // }
                        $("#captcha").focus();
                    }
                }
            });
            //
            if (regPhone.test(open_id)) {
                if (!setInter) {
                    clearInterval(setInter);
                    that.addClass("layui-btn-disabled");
                    that.text(second + "秒后获取");
                    setInter = setInterval(function () {
                        second--;
                        if (second < 1) {
                            clearInterval(setInter);
                            that.removeClass("layui-btn-disabled");
                            that.text("重新获取");
                            setInter = "";
                            second = 60;
                        } else {
                            that.text(second + "秒后获取");
                        }
                    }, 1000);
                }
            } else {
                layer.msg("输入的手机号格式不正确，请重新输入", {
                    icon: "5",
                    anim: "6",
                });
                $("#open_id").focus();
            }
        });
    });
</script>
</body>
</html>
