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
        @if ($db['father_id']==0)
            <div class="layui-form-item">
                <label class="layui-form-label"><span class="red">*</span>产品分类</label>
                <div class="layui-input-inline">
                    <select name="classify" lay-verify="required" @if ($db['navCount']>0) disabled @endif>
                        <option value="">请选择</option>
                        <option value="0" {{ $db['classify']==0?'selected':''}}>跟团游</option>
                        <option value="1" {{ $db['classify']==1?'selected':''}}>商品</option>
                    </select>
                </div>
                <div class="layui-form-mid layui-word-aux">请选择当前产品所归属的分类@if ($db['navCount']>0) <div class="red">(当导航目录下存在绑定产品时, 不允许修改分类)</div> @endif</div>
            </div>
        @endif
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="red">*</span>导航名称</label>
            <div class="layui-input-inline">
                <input type="text" name="title" placeholder="请输入导航名称" autocomplete="off" class="layui-input"
                       lay-verify="required" value="{{$db['title']}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="red">*</span>图片</label>
            <div class="layui-inline" style="width: 520px;">
                <input type="text" id="img_url" name="img_url" lay-verify="required"
                       value="{{$db['img_url']}}" autocomplete="off" class="layui-input" placeholder="请上传导航小图片">
            </div>
            <div class="layui-inline">
                <div class="layui-form-mid layui-word-aux">
                    <button type="button" class="layui-btn layui-btn-normal layui-btn-xs" id="img">
                        <i class="layui-icon">&#xe67c;</i>上传图片
                    </button>
                    用于在分享微站时, 作为默认图标使用
                </div>
            </div>
            <div class="layui-input-block">
                <label class="layui-form"><img class="layui-upload-img" id="img_url_show"
                                               src="{{$db['img_url']}}"></label>
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
    layui.use(["admin", "form", "okUtils", "okLayer", "upload"], function () {
        let admin = layui.admin;
        let form = layui.form;
        let okUtils = layui.okUtils;
        let okLayer = layui.okLayer;
        let upload = layui.upload;
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

        //执行实例
        var uploadInst = upload.render({
            elem: '#img' //绑定元素
            , acceptMime: 'image/*'
            , size: 10240
            , multiple: false
            , drag: true
            , url: '{{url('/sys/upload/')}}' //上传接口
            , method: 'post'
            , data: {_token: '{{csrf_token()}}'}
            , before: function (obj) { //obj参数包含的信息，跟 choose回调完全一致，可参见上文。
                layer.load(); //上传loading
                obj.preview(function (index, file, result) {
                    $('#img_url_show').attr('src', result); //图片链接（base64）
                });
            }
            , done: function (res) {
                //上传完毕回调
                okLayer.greenTickMsg('上传成功!');
                $("#img_url").val(res.file);
                layer.closeAll('loading'); //关闭loading
            }
            , error: function () {
                //请求异常回调
                layer.closeAll('loading'); //关闭loading
            }
        });
        form.on("submit(edit)", function (data) {
            okUtils.ajax("{{url('sys/pages/system/navigation/'.$db['id'])}}", "put", data.field, true).done(function (response) {
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
