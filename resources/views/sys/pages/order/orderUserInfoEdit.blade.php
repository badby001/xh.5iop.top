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
                <label class="layui-form-label"><span class="red">*</span>中文名</label>
                <div class="layui-input-inline" style="width: 120px;">
                    <input type="text" name="cnName" id="cnName" placeholder="请输入中文名" autocomplete="off"
                           class="layui-input"
                           lay-verify="required">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label"><span class="red">*</span>英文姓</label>
                <div class="layui-input-inline" style="width: 100px;">
                    <input type="text" name="enName1" id="enName1" placeholder="英文姓" autocomplete="off"
                           class="layui-input"
                           lay-verify="required">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label"><span class="red">*</span>英文名</label>
                <div class="layui-input-inline" style="width: 100px;">
                    <input type="text" name="enName2" id="enName2" placeholder="英文名" autocomplete="off"
                           class="layui-input"
                           lay-verify="required">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label"><span class="red">*</span>性质</label>
                <div class="layui-inline" style="width: 110px;">
                    <select name="perType" lay-verify="required">
                        <option value="">请选择性质</option>
                        <option value="成人">成人</option>
                        <option value="儿童">儿童</option>
                        <option value="婴儿">婴儿</option>
                        <option value="青年">青年</option>
                        <option value="老人">老人</option>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label"><span class="red">*</span>身份证</label>
                    <div class="layui-input-inline">
                        <input type="text" name="idCard" id="idCard" autocomplete="off" maxlength="18"
                               placeholder="请输入身份证号码"
                               class="layui-input" lay-verify="required">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label"><span class="red">*</span>出生日期</label>
                    <div class="layui-input-inline" style="width: 120px;">
                        <input type="text" name="birth" id="birth" lay-verify="date" placeholder="yyyy-MM-dd"
                               autocomplete="off" class="layui-input" lay-verify="required">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label"><span class="red">*</span>性别</label>
                    <div class="layui-inline" style="width: 120px;">
                        <select name="sex" id="sex" lay-verify="required">
                            <option value="">请选择性别</option>
                            <option value="男">男</option>
                            <option value="女">女</option>
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">民族</label>
                    <div class="layui-input-inline" style="width: 120px;">
                        <input type="text" name="nation" id="nation" placeholder="请输入民族" maxlength="20"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">证件类型</label>
                <div class="layui-inline" style="width: 130px;">
                    <select name="idType" id="idType">
                        <option value="">请选择证件类型</option>
                        <option value="02">户口簿</option>
                        <option value="03">护照</option>
                        <option value="04">军人证件</option>
                        <option value="07">港澳身份证</option>
                        <option value="09">赴台通行证</option>
                        <option value="25">港澳居民来往内地通行证</option>
                        <option value="99">其他</option>
                    </select>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">证件号码</label>
                    <div class="layui-input-inline">
                        <input type="text" name="passport" id="passport" autocomplete="off" maxlength="20"
                               placeholder="请输入证件号码" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">签发日期</label>
                    <div class="layui-input-inline" style="width: 120px;">
                        <input type="text" name="doi" id="doi" placeholder="yyyy-MM-dd" autocomplete="off"
                               class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">有效日期</label>
                    <div class="layui-input-inline" style="width: 120px;">
                        <input type="text" name="doe" id="doe" placeholder="yyyy-MM-dd" autocomplete="off"
                               class="layui-input">
                    </div>
                </div>
            </div>
            <div class="layui-form-item">

                <div class="layui-inline">
                    <label class="layui-form-label">出生地</label>
                    <div class="layui-input-inline" style="width: 120px;">
                        <input type="text" name="pob" id="pob" autocomplete="off" maxlength="10" placeholder="请输入出生地"
                               class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">签发地</label>
                    <div class="layui-input-inline" style="width: 120px;">
                        <input type="text" name="poi" id="poi" autocomplete="off" maxlength="10" placeholder="请输入签发地"
                               class="layui-input">
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label"><span class="red">*</span>联系方式</label>
                    <div class="layui-input-inline" style="width: 120px;">
                        <input type="text" name="ctInfo" id="ctInfo" autocomplete="off" placeholder="请输入联系电话"
                               maxlength="20" class="layui-input" lay-verify="required"
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">地址</label>
                    <div class="layui-input-inline" style="width: 440px;">
                        <input type="text" name="address" id="address" autocomplete="off" placeholder="请输入联系地址"
                               maxlength="150" class="layui-input">
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">备注</label>
                <div class="layui-input-block">
                    <input type="text" name="remark" id="remark" autocomplete="off" placeholder="请输入备注" maxlength="250"
                           class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit lay-filter="edit">立即提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
            <input type="hidden" id="id" name="id" value="{{$db['id']}}}">
            <input type="hidden" name="cnName1" id="cnName1" value=""/>
            <input type="hidden" name="cnName2" id="cnName2" value=""/>
        {{csrf_field()}}
    </form>
</div>
<!--js逻辑-->
<script type="text/javascript" src="{{asset('/js/jquery-1.9.0.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/js/jQuery.Hz2Py-min.js')}}"></script>
<script type="text/javascript">
    layui.use(["admin","form", "okUtils", "okLayer", "laydate"], function () {
	    let admin = layui.admin;
        let form = layui.form;
        let okUtils = layui.okUtils;
        let okLayer = layui.okLayer;
        let laydate = layui.laydate;
        let $ = layui.jquery;
        form.val("filter", eval('(' + parent.json + ')'));
        laydate.render({
            elem: '#birth', //指定元素
            //max: "2020-1-1",
            value: '',
        });
        laydate.render({
            elem: '#doi', //指定元素
            done: function (value) { //监听日期被切换
                if ($('#doe').val() == '') {
                    doeTime = new Date(value);
                    doeTime = (doeTime.getFullYear() + 10) + "-" + (doeTime.getMonth() + 1) + "-" + (doeTime.getDate() - 1);
                    lay('#doe').val(doeTime);
                }
            }
        });
        laydate.render({
            elem: '#doe', //指定元素
            //max: "2020-1-1",
            value: '',
        });
        admin.removeLoading();

        $("#idCard").blur(function () {
            var idCard = $(this).val();
            var bo = /^(\d{6})(18|19|20)?(\d{2})([01]\d)([0123]\d)(\d{3})(\d|X)?$/.test(idCard);
            var year = idCard.substr(6, 4);
            var month = idCard.substr(10, 2);
            var day = idCard.substr(12, 2);
            if (bo == false || month > 12 || day > 31 || (idCard.length != 18)) {
                if (idCard != "") {
                    layer.msg('身份证号码错误，请注意修改！', {icon: 5});
                }
                return false;
            } else {
                var sex = '';
                var _sex = idCard.substr(16, 1);
                _sex % 2 == 0 ? sex = '女' : sex = '男';
                $('#sex').siblings("div.layui-form-select").find('dl').find('dd[lay-value=' + sex + ']').click();
                if ($("#birth").val() == "") {
                    $('#birth').val(year + "-" + month + "-" + day);
                }
            }
        });

        //姓名转拼音
        var cnName1 = $("#cnName1");
        var cnName2 = $("#cnName2");
        $("#cnName").blur(function () {
            var obj = $(this);
            var str = obj.val();
            var enName1 = $("#enName1");
            var enName2 = $("#enName2");
            if ($.isEmptyObject(enName1.attr("readonly"))) {
                if (enName1.val() == "") {
                    cnName1.val(str.substring(0, 1));
                    enName1.val(cnName1.toPinyin());
                }
                if (enName2.val() == "") {
                    cnName2.val(str.substring(1));
                    enName2.val(cnName2.toPinyin());
                }
            }
        });


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
            okUtils.ajax("{{url('/sys/pages/order/orderUserInfo/')}}", "post", data.field, true).done(function (response) {
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
