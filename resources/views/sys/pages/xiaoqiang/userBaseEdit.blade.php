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
                    <label class="layui-form-label"><span class="red">*</span>用户名</label>
                    <div class="layui-input-inline">
                        <input type="text" name="open_id" placeholder="请输入用户名" autocomplete="off"
                               class="layui-input"
                               lay-verify="required" value="" {{$db['id']?'disabled':''}}>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">姓名</label>
                    <div class="layui-input-inline">
                        <input type="text" name="name" placeholder="请输入真实姓名" autocomplete="off" class="layui-input"
                               value="" {{$db['id']?'disabled':''}}>
                    </div>
                </div>
{{--                <div class="layui-form-item">--}}
{{--                    <label class="layui-form-label">手机号码</label>--}}
{{--                    <div class="layui-input-inline">--}}
{{--                        <input type="text" name="mobile" placeholder="请输入手机号码" autocomplete="off"--}}
{{--                               class="layui-input"--}}
{{--                               value="" {{$db['id']?'disabled':''}}>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="layui-form-item">--}}
{{--                    <label class="layui-form-label">邮箱</label>--}}
{{--                    <div class="layui-input-block">--}}
{{--                        <input type="text" name="email" placeholder="请输入邮箱" autocomplete="off" class="layui-input"--}}
{{--                               value="" {{$db['id']?'disabled':''}}>--}}
{{--                    </div>--}}
{{--                </div>--}}
                <div class="layui-form-item">
                    <label class="layui-form-label">密码</label>
                    <div class="layui-input-inline">
                        <input type="password" name="pass_word" placeholder="密码为空时不进行修改操作" autocomplete="off"
                               class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">出生日期</label>
                    <div class="layui-input-inline">
                        <input type="text" name="birth_date" placeholder="请选择出生日期" autocomplete="off"
                               class="layui-input" id="birth_date" value="" {{$db['id']?'disabled':''}}>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">性别</label>
                    <div class="layui-input-block">
                        <input type="radio" name="sex" value="1"
                               title="男" {{$db['admUserInfo']['sex']==1?'checked':''}} disabled>
                        <input type="radio" name="sex" value="0"
                               title="女" {{$db['admUserInfo']['sex']==0?'checked':''}} disabled>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <div class="layui-form-mid layui-word-aux">当前界面仅支持初始化客户账号密码</div>
                    </div>
                </div>
                {{--                <div class="layui-form-item layui-form-text">--}}
                {{--                    <label class="layui-form-label">备注</label>--}}
                {{--                    <div class="layui-input-block">--}}
                {{--                        <textarea name="remarks" placeholder="请输入内容" class="layui-textarea"></textarea>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
            </div>

            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit lay-filter="edit">立即提交</button>
                </div>
            </div>
        </div>
        {{csrf_field()}}
    </form>
</div>
<!--js逻辑-->
<script type="text/javascript">
    layui.use(["admin","element", "form", "laydate", "okLayer", "okUtils"], function () {
	    let admin = layui.admin;
        let form = layui.form;
        let laydate = layui.laydate;
        let okLayer = layui.okLayer;
        let okUtils = layui.okUtils;
        form.val("filter", eval('(' + parent.json + ')'));
        laydate.render({elem: "#birth_date", type: "date"});
		admin.removeLoading();
        form.verify({
            birthdayVerify: [/^((((1[6-9]|[2-9]\d)\d{2})-(0?[13578]|1[02])-(0?[1-9]|[12]\d|3[01]))|(((1[6-9]|[2-9]\d)\d{2})-(0?[13456789]|1[012])-(0?[1-9]|[12]\d|30))|(((1[6-9]|[2-9]\d)\d{2})-0?2-(0?[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))-0?2-29-))(\s(([01]\d{1})|(2[0123])):([0-5]\d):([0-5]\d))?$/, '日期格式不正确']
        });

        form.on("submit(edit)", function (data) {
            okUtils.ajax("{{url('sys/pages/system/user/'.$db['id'])}}", "{{$db['id']?'put':'post'}}", data.field, true).done(function (response) {
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
