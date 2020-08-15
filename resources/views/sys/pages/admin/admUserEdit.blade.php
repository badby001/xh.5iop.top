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
                        <label class="layui-form-label"><span class="red">*</span>账号</label>
                        <div class="layui-input-inline">
                            <input type="text" name="open_id" placeholder="请输入账号" autocomplete="off"
                                   class="layui-input" lay-verify="required" maxlength="15" value="" {{$db['id']?'disabled':''}}>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"><span class="red">*</span>名称</label>
                        <div class="layui-input-inline">
                            <input type="text" name="name" placeholder="请输入名称" autocomplete="off" class="layui-input" lay-verify="required" value="">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">密码</label>
                        <div class="layui-input-inline">
                            <input type="password" name="pass_word"
                                   {{$db['id']?'':'lay-verify=required'}} placeholder="{{$db['id']?'密码为空时不进行修改操作':'请输入密码'}}"
                                   autocomplete="off" maxlength="18" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"><span class="red">*</span>角色</label>
                        <div class="layui-input-inline">
                            <select name="role_id" lay-verify="required">
                                <option value=""></option>
                                @foreach(getUserRole(1) as $k=>$v)
                                    <option
                                        value="{{$v['id']}}" {{$v['id']==$role_id?'selected':''}}>{{$v['title'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit lay-filter="edit">立即提交</button>
                        </div>
                    </div>
                </div>
            </div>
        {{csrf_field()}}
    </form>
</div>
<!--js逻辑-->
<script type="text/javascript">
    layui.use(["admin","element", "form",  "okLayer", "okUtils"], function () {
	    let admin = layui.admin;
        let form = layui.form;
        let okLayer = layui.okLayer;
        let okUtils = layui.okUtils;
        form.val("filter", eval('(' + parent.json + ')'));
        admin.removeLoading();

        form.on("submit(edit)", function (data) {
            okUtils.ajax("{{url('sys/pages/admin/admUser/'.$db['id'])}}", "{{$db['id']?'put':'post'}}", data.field, true).done(function (response) {
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
