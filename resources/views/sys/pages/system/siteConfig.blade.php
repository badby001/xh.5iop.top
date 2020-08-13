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
    <div class="layui-row">
        <div class="layui-tab layui-tab-brief">
            <ul class="layui-tab-title">
                {{--                <li class="layui-this">网站配置</li>--}}
                <li class="layui-this">微站配置</li>
                <li>通用配置</li>
                <li>客服信息</li>
            </ul>
            <div class="layui-tab-content" style="height: 100px;">
                {{--                <div class="layui-tab-item layui-show">--}}
                {{--                    <form class="layui-form ok-form">开发中...(请先使用微站配置)</form>--}}
                {{--                </div>--}}
                <div class="layui-tab-item layui-show">
                    <form class="layui-form ok-form">
                        <div class="layui-form-item">
                            <label class="layui-form-label"><span class="red">*</span>微站标题</label>
                            <div class="layui-inline" style="width: 520px;">
                                <input type="text" name="title" required lay-verify="required" placeholder="请输入微站标题"
                                       autocomplete="off" class="layui-input" value="{{$db['title']}}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label"><span class="red">*</span>微站标语</label>
                            <div class="layui-inline" style="width: 520px;">
                                <input type="text" name="slogan" lay-verify="required" placeholder="请输入微站标语"
                                       autocomplete="off" class="layui-input" value="{{$db['slogan']}}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label"><span class="red">*</span>分享图标</label>
                            <div class="layui-inline" style="width: 520px;">
                                <input type="text" id="wechat_icon" name="wechat_icon"
                                       lay-verify="required"
                                       value="{{$db['wechat_icon']}}"
                                       autocomplete="off" class="layui-input" placeholder="请上传分享图标">
                            </div>
                            <div class="layui-inline">
                                <div class="layui-form-mid layui-word-aux">
                                    <button type="button" class="layui-btn layui-btn-normal layui-btn-xs"
                                            id="wechat_icon_img">
                                        <i class="layui-icon">&#xe67c;</i>上传图片
                                    </button>
                                    用于在分享微站时, 作为默认图标使用
                                </div>
                            </div>
                            <div class="layui-input-block">
                                <label class="layui-form"><img class="layui-upload-img" id="wechat_icon_show"
                                                               src="{{$db['wechat_icon']}}"></label>
                            </div>
                        </div>
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">分享描述</label>
                            <div class="layui-input-block">
                                <textarea name="wechat_describe" placeholder="请输入分享描述"
                                          class="layui-textarea">{{$db['wechat_describe']}}</textarea>
                                <div class="layui-form-mid layui-word-aux">用于在分享微站时, 作为默认描述使用(除详情页)</div>
                            </div>
                        </div>
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">外部代码</label>
                            <div class="layui-input-block">
                                <textarea name="script" placeholder="请输入外部代码"
                                          class="layui-textarea">{{$db['script']}}</textarea>
                                <div class="layui-form-mid layui-word-aux">外部代码用于存放在线客服脚本或其他认证脚本</div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                @if(hasPower(82))
                                    <button class="layui-btn" lay-submit lay-filter="micro">保存</button>
                                @endif
                            </div>
                        </div>
                        {{csrf_field()}}
                    </form>
                </div>
                <div class="layui-tab-item">
                    <form class="layui-form ok-form">
                        <input type="hidden" name="display_price" value="0">
{{--                        <div class="layui-form-item">--}}
{{--                            <label class="layui-form-label">展示价</label>--}}
{{--                            <div class="layui-input-inline">--}}
{{--                                <input type="radio" name="display_price" value=0--}}
{{--                                       title="市场价" {{$db['display_price']?'':'checked'}}>--}}
{{--                                <input type="radio" name="display_price" value=1--}}
{{--                                       title="同行价" {{$db['display_price']?'checked':''}}>--}}
{{--                            </div>--}}
{{--                            <div class="layui-form-mid layui-word-aux">产品展示页, 在用户未登录时默认展示的价格</div>--}}
{{--                        </div>--}}
                        <div class="layui-form-item">
                            <label class="layui-form-label">余位显示</label>
                            <div class="layui-input-inline">
                                <input type="text" name="remaining_position" lay-verify="required"
                                       placeholder="请输入余位显示信息"
                                       autocomplete="off" class="layui-input" value="{{$db['remaining_position']}}">
                            </div>
                            <div class="layui-form-mid layui-word-aux">请输入余位大于9时展示的内容, 留空后显示真实余位</div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label"><span class="red">*</span>默认图片</label>
                            <div class="layui-inline" style="width: 520px;">
                                <input type="text" id="default_picture" name="default_picture"
                                       lay-verify="required"
                                       value="{{$db['default_picture']}}"
                                       autocomplete="off" class="layui-input" placeholder="请上传默认图片">
                            </div>
                            <div class="layui-inline">
                                <div class="layui-form-mid layui-word-aux">
                                    <button type="button" class="layui-btn layui-btn-normal layui-btn-xs"
                                            id="default_picture_img">
                                        <i class="layui-icon">&#xe67c;</i>上传图片
                                    </button>
                                    用于系统中的默认图片
                                </div>
                            </div>
                            <div class="layui-input-block">
                                <label class="layui-form"><img class="layui-upload-img" id="default_picture_show" src="{{$db['default_picture']}}"></label>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label"><span class="red">*</span>ICO图标</label>
                            <div class="layui-inline" style="width: 520px;">
                                <input type="text" id="ico" name="ico"
                                       lay-verify="required"
                                       value="{{$db['ico']}}"
                                       autocomplete="off" class="layui-input" placeholder="请上传默认ICO图标">
                            </div>
                            <div class="layui-inline">
                                <div class="layui-form-mid layui-word-aux">
                                    <button type="button" class="layui-btn layui-btn-normal layui-btn-xs" id="ico_img">
                                        <i class="layui-icon">&#xe67c;</i>上传图片
                                    </button>
                                    用于系统中的默认ICO图标
                                </div>
                            </div>
                            <div class="layui-input-block">
                                <label class="layui-form"><img class="layui-upload-img" id="ico_show" src="{{$db['ico']}}"></label>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">ICP网备案号</label>
                            <div class="layui-input-inline">
                                <input type="text" name="siteICP" placeholder="请输入ICP网备案号" autocomplete="off" class="layui-input" value="{{$db['siteICP']}}">
                            </div>
                            <div class="layui-form-mid layui-word-aux">用于展示ICP备案号,没有备案,网站会被停止使用</div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">ESO关键词</label>
                            <div class="layui-inline" style="width: 520px;">
                                <input type="text" name="keywords" value="{{$db['keywords']}}"
                                       autocomplete="off" class="layui-input" placeholder="请输入ESO关键词">
                            </div>
                        </div>
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">ESO描述</label>
                            <div class="layui-input-block">
                                <textarea name="description" placeholder="请输入ESO描述"
                                          class="layui-textarea">{{$db['description']}}</textarea>
                                <div class="layui-form-mid layui-word-aux">用于前台站点的关键词或描述推广</div>
                            </div>
                        </div>
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">统计代码</label>
                            <div class="layui-input-block">
                                <textarea name="tj_script" placeholder="请输入统计代码"
                                          class="layui-textarea">{{$db['tj_script']}}</textarea>
                                <div class="layui-form-mid layui-word-aux">统计代码用于存放在网站/微站的访问统计</div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                @if(hasPower(82))
                                    <button class="layui-btn" lay-submit lay-filter="alike">保存</button>
                                @endif
                            </div>
                        </div>
                        {{csrf_field()}}
                    </form>
                </div>
                <div class="layui-tab-item">
                    <form class="layui-form ok-form">
                        <script id="service_info" name="service_info" type="text/plain"
                                style="width:100%;height:360px;">{!! $db['service_info'] !!}</script>
                        <br>
                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                @if(hasPower(82))
                                    <button class="layui-btn" lay-submit lay-filter="serInfo">保存</button>
                                @endif
                            </div>
                        </div>
                        {{csrf_field()}}
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
<!--js逻辑-->
@include('.sys.public.ueditor')
<script>
    layui.use(["admin","element", "form", "okLayer", "okUtils", "upload", "layer", "jquery"], function () {
	    let admin = layui.admin;
        let form = layui.form;
        let okLayer = layui.okLayer;
        let okUtils = layui.okUtils;
        let upload = layui.upload;
        let $ = layui.jquery;
        UE.getEditor('service_info').focus();
        admin.removeLoading();
        //自定义验证规则
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
        //执行实例
        var uploadInst = upload.render({
            elem: '#wechat_icon_img' //绑定元素
            , acceptMime: 'image/*'
            , size: 1024
            , multiple: false
            , drag: true
            , url: '{{url('/sys/upload/')}}' //上传接口
            , method: 'post'
            , data: {_token: '{{csrf_token()}}'}
            , before: function (obj) { //obj参数包含的信息，跟 choose回调完全一致，可参见上文。
                layer.load(); //上传loading
                obj.preview(function (index, file, result) {
                    $('#wechat_icon_show').attr('src', result); //图片链接（base64）
                });
            }
            , done: function (res) {
                //上传完毕回调
                okLayer.greenTickMsg('上传成功!');
                $("#wechat_icon").val(res.file);
                layer.closeAll('loading'); //关闭loading
            }
            , error: function () {
                //请求异常回调
                layer.closeAll('loading'); //关闭loading
            }
        });
        var uploadInst = upload.render({
            elem: '#default_picture_img' //绑定元素
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
                    $('#default_picture_show').attr('src', result); //图片链接（base64）
                });
            }
            , done: function (res) {
                //上传完毕回调
                okLayer.greenTickMsg('上传成功!');
                $("#default_picture").val(res.file);
                layer.closeAll('loading'); //关闭loading
            }
            , error: function () {
                //请求异常回调
                layer.closeAll('loading'); //关闭loading
            }
        });
        var uploadInst = upload.render({
            elem: '#ico_img' //绑定元素
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
                    $('#ico_show').attr('src', result); //图片链接（base64）
                });
            }
            , done: function (res) {
                //上传完毕回调
                okLayer.greenTickMsg('上传成功!');
                $("#ico").val(res.file);
                layer.closeAll('loading'); //关闭loading
            }
            , error: function () {
                //请求异常回调
                layer.closeAll('loading'); //关闭loading
            }
        });
        //
        //
        //
        form.on("submit(micro)", function (data) {
            var index = layer.msg('提交中，请稍候', {icon: 16, time: false, shade: 0.8});
            okUtils.ajax("{{url('sys/pages/system/microSiteSave')}}", "post", data.field, true).done(function (response) {
                setTimeout(function () {
                    okLayer.greenTickMsg(response.msg, function () {
                        parent.layer.close(parent.layer.getFrameIndex(window.name));
                    });
                    layer.close(index);
                }, 1000);
            }).fail(function (error) {
                console.log(error)
            });
            return false;
        });
        //
        form.on("submit(alike)", function (data) {
            var index = layer.msg('提交中，请稍候', {icon: 16, time: false, shade: 0.8});
            okUtils.ajax("{{url('sys/pages/system/alikeSiteSave')}}", "post", data.field, true).done(function (response) {
                setTimeout(function () {
                    okLayer.greenTickMsg(response.msg, function () {
                        parent.layer.close(parent.layer.getFrameIndex(window.name));
                    });
                    layer.close(index);
                }, 1000);
            }).fail(function (error) {
                console.log(error)
            });
            return false;
        });
        //
        form.on("submit(serInfo)", function (data) {
            var index = layer.msg('提交中，请稍候', {icon: 16, time: false, shade: 0.8});
            okUtils.ajax("{{url('sys/pages/system/serInfoSiteSave')}}", "post", data.field, true).done(function (response) {
                setTimeout(function () {
                    okLayer.greenTickMsg(response.msg, function () {
                        parent.layer.close(parent.layer.getFrameIndex(window.name));
                    });
                    layer.close(index);
                }, 1000);
            }).fail(function (error) {
                console.log(error)
            });
            return false;
        });
    })
</script>
</body>
</html>
