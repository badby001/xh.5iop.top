<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>个人中心</title>
    @include('.sys.public.css')
    @include('.sys.public.js')
    <style>
        /* 用户信息 */
        .user-info-head {
            width: 110px;
            height: 110px;
            line-height: 110px;
            position: relative;
            display: inline-block;
            border: 2px solid #eee;
            border-radius: 50%;
            overflow: hidden;
            cursor: pointer;
            margin: 0 auto;
        }

        .user-info-head:hover:after {
            content: '\e681';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            color: #fff;
            background-color: rgba(0, 0, 0, 0.3);
            font-size: 28px;
            padding-top: 2px;
            font-style: normal;
            font-family: layui-icon;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .user-info-head img {
            width: 110px;
            height: 110px;
        }

        .user-info-list-item {
            position: relative;
            padding-bottom: 8px;
        }

        .user-info-list-item > .layui-icon {
            position: absolute;
        }

        .user-info-list-item > p {
            padding-left: 30px;
        }

        .layui-line-dash {
            border-bottom: 1px dashed #ccc;
            margin: 15px 0;
        }

        /* 基本信息 */
        #userInfoForm .layui-form-item {
            margin-bottom: 25px;
        }

        /* 账号绑定 */
        .user-bd-list-item {
            padding: 14px 60px 14px 10px;
            border-bottom: 1px solid #e8e8e8;
            position: relative;
        }

        .user-bd-list-item .user-bd-list-lable {
            color: #333;
            margin-bottom: 4px;
        }

        .user-bd-list-item .user-bd-list-oper {
            position: absolute;
            top: 50%;
            right: 10px;
            margin-top: -8px;
            cursor: pointer;
        }

        .user-bd-list-item .user-bd-list-img {
            width: 48px;
            height: 48px;
            line-height: 48px;
            position: absolute;
            top: 50%;
            left: 10px;
            margin-top: -24px;
        }

        .user-bd-list-item .user-bd-list-img + .user-bd-list-content {
            margin-left: 68px;
        }
    </style>
</head>
<body>
<!-- 正文开始 -->
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <!-- 左 -->
        <div class="layui-col-sm12 layui-col-md3">
            <div class="layui-card">
                <div class="layui-card-body" style="padding: 25px;">
                    <div class="text-center layui-text">
                        <div class="user-info-head" id="userInfoHead">
                            <img src="{{_getAdmHead()}}" alt=""/>
                        </div>
                        <h2 style="padding-top: 20px;">{{$db->name}}</h2>
                        <p style="padding-top: 8px;">峰景打造有温度的旅游</p>
                    </div>
                    <div class="layui-text" style="padding-top: 30px;">
                        <div class="user-info-list-item">
                            <i class="layui-icon layui-icon-username"></i>
                            <p>同业身份 ({{$db->attestation_state_show}}) </p>
                        </div>
                        {{--                        <div class="user-info-list-item">--}}
                        {{--                            <i class="layui-icon layui-icon-release"></i>--}}
                        {{--                            <p>某某公司－某某某事业群－某某平台部－某某技术部－UED</p>--}}
                        {{--                        </div>--}}
                        <div class="user-info-list-item">
                            <i class="layui-icon layui-icon-location"></i>
                            <p>{{$db->attestation_area_title}}</p>
                        </div>
                    </div>
                    {{--                    <div class="layui-line-dash"></div>--}}
                    {{--                    <h3>标签</h3>--}}
                    {{--                    <div class="layui-badge-list" style="padding-top: 6px;">--}}
                    {{--                        <span class="layui-badge layui-bg-gray">很有想法的</span>--}}
                    {{--                        <span class="layui-badge layui-bg-gray">专注设计</span>--}}
                    {{--                        <span class="layui-badge layui-bg-gray">辣~</span>--}}
                    {{--                        <span class="layui-badge layui-bg-gray">大长腿</span>--}}
                    {{--                        <span class="layui-badge layui-bg-gray">川妹子</span>--}}
                    {{--                        <span class="layui-badge layui-bg-gray">海纳百川</span>--}}
                    {{--                    </div>--}}
                </div>
            </div>
        </div>
        <!-- 右 -->
        <div class="layui-col-sm12 layui-col-md9">
            <div class="layui-card">
                <!-- 选项卡开始 -->
                <div class="layui-tab layui-tab-brief" lay-filter="info">
                    <ul class="layui-tab-title">
                        <li class="layui-this" lay-id="11">基本信息</li>
                        <li lay-id="22">账号绑定</li>
                        @if(_isAdm()==1)
                            <li lay-id="33">同行认证 ({{$db->attestation_state_show}})</li>
                        @endif
                    </ul>
                    <div class="layui-tab-content">
                        <!-- tab1 -->
                        <div class="layui-tab-item layui-show">
                            <form class="layui-form" id="userInfoForm" lay-filter="userInfoForm"
                                  style="max-width: 520px;padding: 25px 10px 0 0;">
                                <div class="layui-form-item">
                                    <label class="layui-form-label">账号</label>
                                    <div class="layui-input-block">
                                        <input type="text" value="{{_admOpenId()}}" disabled
                                               class="layui-input layui-disabled">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">姓名</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="name" value="{{$db->name}}" class="layui-input"
                                               lay-vertype="tips" lay-verify="required">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">性别</label>
                                    <div class="layui-input-block">
                                        <input type="radio" name="sex" value=false
                                               title="女" {{$db->sex==0?'checked':''}}>
                                        <input type="radio" name="sex" value=true
                                               title="男" {{$db->sex==1?'checked':''}}>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">出生年月</label>
                                    <div class="layui-input-block">
                                        <input id="birth_date" name="birth_date" type="text" value="{{$db->birth_date}}"
                                               placeholder="请输入出生年月" lay-vertype="tips" lay-verify="required"
                                               class="layui-input userBirthday">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label"><i class="layui-icon layui-icon-tips"
                                                                       lay-tips="常用邮箱用于接收系统各种业务消息, 建议填写"
                                                                       lay-direction="1"
                                                                       lay-offset="0,-10px"></i>常用邮箱</label>
                                    <div class="layui-input-block">
                                        <input id="email" name="email" class="layui-input layui-form-danger"
                                               placeholder="请输入邮箱, 用于接收各类系统消息"
                                               lay-vertype="tips" lay-verify="emailX" value="{{$db->email}}">
                                    </div>
                                </div>
                                <div class="layui-form-item" style="margin-left: 1%;">
                                    <div class="layui-input-block">
                                        <button class="layui-btn" lay-submit="" lay-filter="changeInfo">立即提交</button>
                                        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                                    </div>
                                </div>
                                <input name="user_head" id="user_head" type="text" hidden value="{{$db->user_head}}">
                            </form>
                        </div>
                        <!-- tab2 -->
                        <div class="layui-tab-item" style="padding-bottom: 20px;">
                            <div class="user-bd-list layui-text">
                                <div class="user-bd-list-item">
                                    <div class="user-bd-list-lable">密保手机</div>
                                    <div class="user-bd-list-text">已绑定手机：{{_admOpenId()}}</div>
                                    {{--                                    <a class="user-bd-list-oper">修改</a>--}}
                                </div>
                                <div class="user-bd-list-item">
                                    <div class="user-bd-list-img">
                                        <i class="layui-icon layui-icon-login-qq"
                                           style="color: #3492ED;font-size: 48px;"></i>
                                    </div>
                                    <div class="user-bd-list-content">
                                        <div class="user-bd-list-lable">绑定QQ</div>
                                        <div class="user-bd-list-text">当前未绑定QQ账号</div>
                                    </div>
                                    {{--                                    <a class="user-bd-list-oper">绑定</a>--}}
                                </div>
                                <div class="user-bd-list-item">
                                    <div class="user-bd-list-img">
                                        <i class="layui-icon layui-icon-login-wechat"
                                           style="color: #4DAF29;font-size: 48px;"></i>
                                    </div>
                                    <div class="user-bd-list-content">
                                        <div class="user-bd-list-lable">绑定微信</div>
                                        <div class="user-bd-list-text">当前未绑定绑定微信账号</div>
                                    </div>
                                    {{--                                    <a class="user-bd-list-oper">绑定</a>--}}
                                </div>
                            </div>
                        </div>
                        <!-- tab3 -->
                        <div class="layui-tab-item">
                            <form class="layui-form" style="max-width: 520px;padding: 25px 10px 0 0;">
                                <div class="layui-form-item">
                                    <label class="layui-form-label"><span class="red">*</span>旅行社名称</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="attestation_tourist_agency"
                                               value="{{$db->attestation_tourist_agency}}"
                                               class="layui-input" lay-vertype="tips" lay-verify="required"
                                               @if($db->attestation_state>0) readonly @endif>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label"><span class="red">*</span>省市区选择</label>
                                    <div class="layui-input-block">
                                        <input id="attestation_area_code" placeholder="请选择 / 支持输入关键字" class="layui-hide"
                                               lay-vertype="tips" lay-verify="required"
                                               value="{{$db->attestation_area_code}}"
                                               @if($db->attestation_state>0) disabled @endif>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label"><span class="red">*</span>详细地址</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="attestation_address"
                                               value="{{$db->attestation_address}}"
                                               class="layui-input" lay-vertype="tips" lay-verify="required"
                                               @if($db->attestation_state>0) readonly @endif>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label"><span class="red">*</span>营业执照</label>
                                    <div class="layui-inline" style="width: 300px;">
                                        <input type="text" id="license" name="attestation_business_license_img"
                                               lay-vertype="tips" lay-verify="required"
                                               value="{{$db->attestation_business_license_img}}"
                                               autocomplete="off" class="layui-input" placeholder="请上传营业执照"
                                               @if($db->attestation_state>0) readonly @endif>
                                    </div>
                                    <div class="layui-inline">
                                        <div class="layui-form-mid layui-word-aux">
                                            <button type="button" class="layui-btn layui-btn-normal layui-btn-xs"
                                                    @if($db->attestation_state<1) id="license_img" @endif>
                                                <i class="layui-icon">&#xe67c;</i>上传图片
                                            </button>
                                        </div>
                                    </div>
                                    <div class="layui-input-block">
                                        <label class="layui-form"><img class="layui-upload-img" id="license_show"
                                                                       src="{{$db->attestation_business_license_img}}"></label>
                                    </div>
                                </div>
                                @if($db->attestation_state==0 ||$db->attestation_state==-1)
                                    <div class="layui-form-item" style="margin-left: 1%;">
                                        <div class="layui-input-block">
                                            <button class="layui-btn" lay-submit="" lay-filter="attestation">立即提交
                                            </button>
                                            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                                        </div>
                                    </div>
                                @endif
                                <input name="attestation_area_code" type="text" hidden
                                       value="{{$db->attestation_area_code_value}}">
                            </form>
                        </div>
                    </div>
                </div>
                <!-- //选项卡结束 -->
            </div>
        </div>
    </div>
</div>

<!-- js部分 -->
<script>
    layui.use(['layer', 'form', 'formX', 'element', 'admin', 'upload', 'laydate', "okLayer", 'cascader'], function () {
        let $ = layui.jquery;
        let layer = layui.layer;
        let form = layui.form;
        let formX = layui.formX;
        let element = layui.element;
        let admin = layui.admin;
        let upload = layui.upload;
        let laydate = layui.laydate;
        let okLayer = layui.okLayer;
        let cascader = layui.cascader;

        let citysData ={!! getCitysData() !!};
        laydate.render({
            elem: '#birth_date', //指定元素
            max: "2020-1-1",
            value: '',
        });
        // 省市区选择
        cascader.render({
            elem: '#attestation_area_code',
            data: citysData,
            itemHeight: '250px',
            filterable: true,
            onChange: function (values, data) {
                // console.log(data.value);
                $("input[type=text][name=attestation_area_code]").val(data.value);
            }
        });
        var layid = location.hash.replace(/^#info=/, '');
        element.tabChange('info', layid);
        element.on('tab(info)', function (elem) {
            location.hash = 'info=' + $(this).attr('lay-id');
        });
        admin.removeLoading();

        //执行实例
        var uploadInst = upload.render({
            elem: '#license_img' //绑定元素
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
                    $('#license_show').attr('src', result); //图片链接（base64）
                });
            }
            , done: function (res) {
                //上传完毕回调
                okLayer.greenTickMsg('上传成功!');
                $("#license").val(res.file);
                layer.closeAll('loading'); //关闭loading
            }
            , error: function () {
                //请求异常回调
                layer.closeAll('loading'); //关闭loading
            }
        });
        /* 选择头像 */
        $('#userInfoHead').click(function () {
            admin.cropImg({
                imgSrc: $('#userInfoHead>img').attr('src'),
                limitSize: 2048,
                onCrop: function (res) {
                    admin.dealImage(res, 120, useImg);

                    function useImg(res) {
                        $('#userInfoHead>img').attr('src', res);
                        parent.layui.jquery('.layui-layout-admin>.layui-header .layui-nav img.layui-nav-img').attr('src', res);
                        var picUrl = admin.putb64(res, '{{ qiniu()['QINIU_PUBT64'] }}', '{{ qiniu()['QINIU_DOMAIN'] }}', '{{ qiniu()['QINIU_TOKEM'] }}');
                        $("#user_head").val(picUrl);
                        okLayer.greenTickMsg('上传成功, 请提交保存!', 1500);
                    }
                }
            });
        });

        /* 监听表单提交 */
        //修改资料
        form.on("submit(changeInfo)", function (data) {
            var index = layer.msg('提交中，请稍候', {icon: 16, time: false, shade: 0.8});
            setTimeout(function () {
                $.post("{{url('sys/pages/admInfo')}}", {_token: "{{csrf_token()}}", 'data': data.field},
                    function (data) {
                        okLayer.greenTickMsg(data.msg, function () {
                            parent.layer.close(parent.layer.getFrameIndex(window.name));
                        });
                    }, "json");
                layer.close(index);
            }, 1000);
            return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
        });
        //同行认证
        form.on("submit(attestation)", function (data) {
            var index = layer.msg('提交中，请稍候', {icon: 16, time: false, shade: 0.8});
            setTimeout(function () {
                $.post("{{url('sys/pages/admAttestation')}}", {_token: "{{csrf_token()}}", 'data': data.field},
                    function (data) {
                        okLayer.greenTickMsg(data.msg, function () {
                            location.reload();
                        });
                    }, "json");
                layer.close(index);
            }, 1000);
            return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
        });
    });
</script>
</body>
</html>
