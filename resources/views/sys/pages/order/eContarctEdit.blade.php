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
                <label class="layui-form-label"><span class="red">*</span>游客选择</label>
                <div class="layui-inline" style="width: 120px;">
                    <div id="userIds" name="userIds"></div>
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label" style="width: 120px;">最低成团人数</label>
                <div class="layui-input-inline" style="width: 100px;">
                    <input type="text" name="minPlanNum" id="minPlanNum" autocomplete="off" class="layui-input"
                           lay-verify="required" value="{{$db['minPlanNum']}}" disabled>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label"><span class="red">*</span>成人费用</label>
                <div class="layui-input-inline" style="width: 100px;">
                    <input type="text" name="aduAmount" id="aduAmount" autocomplete="off" class="layui-input"
                           placeholder="成人费用/人" lay-verify="required" value="{{$db['aduAmount']}}">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label"><span class="red">*</span>儿童费用</label>
                <div class="layui-input-inline" style="width: 120px;">
                    <input type="text" name="childAmount" id="childAmount" autocomplete="off" class="layui-input"
                           placeholder="儿童费用/人" lay-verify="required" value="{{$db['childAmount']}}">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label" style="width: 120px;">旅游费用合计</label>
                <div class="layui-inline" style="width: 120px;">
                    <input type="text" name="amountAll" id="amountAll" autocomplete="off" class="layui-input"
                           lay-verify="required" value="{{$db['amountAll']}}" disabled>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">支付方式</label>
            <div class="layui-inline" style="width: 110px;">

                <select name="payType" id="payType">
                    <option value="1" {!! $db['payType']==1?'selected':'' !!}>现金</option>
                    <option value="2" {!! $db['payType']==2?'selected':'' !!}>转账</option>
                    <option value="3" {!! $db['payType']==3?'selected':'' !!}>线上支付</option>
                </select>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">支付日期</label>
                <div class="layui-input-inline" style="width: 120px;">
                    <input type="text" name="payTime" id="payTime" autocomplete="off"
                           lay-verify="date" placeholder="yyyy-MM-dd" class="layui-input">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">经办人姓名</label>
                <div class="layui-input-inline" style="width: 100px;">
                    <input type="text" name="transactorName" id="transactorName" autocomplete="off"
                           class="layui-input" lay-verify="required" value="{{$db['transactorName']}}" disabled>
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">经办人电话</label>
                <div class="layui-input-inline" style="width: 120px;">
                    <input type="text" name="transactorPhone" id="transactorPhone" autocomplete="off"
                           class="layui-input" lay-verify="required" value="{{$db['transactorPhone']}}" disabled>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">其他约定</label>
            <div class="layui-input-block">
                <input type="text" name="other" id="other" autocomplete="off" placeholder="请输入约定备注" maxlength="250"
                       class="layui-input" value="{{$db['other']}}">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="edit">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
        <input type="hidden" name="adultNum" id="adultNum" value="0"/>
        <input type="hidden" name="childNum" id="childNum" value="0"/>
        {{csrf_field()}}
    </form>
</div>
<!--js逻辑-->
<script type="text/javascript">
    layui.use(["admin","form", "okUtils", "okLayer", "laydate","xmSelect"], function () {
	    let admin = layui.admin;
        let form = layui.form;
        let okUtils = layui.okUtils;
        let okLayer = layui.okLayer;
        let laydate = layui.laydate;
        let xmSelect = layui.xmSelect;
        let $ = layui.jquery;
        form.val("filter", eval('(' + parent.json + ')'));
        var myDate = new Date();
        var payTimeVal = '';
        @if($db['payTime'])
            payTimeVal = '{!! $db['payTime'] !!}';
        @else
            payTimeVal = myDate.getFullYear() + '-' + (myDate.getMonth() + 1) + '-' + myDate.getDate();
        @endif
        laydate.render({
            elem: '#payTime', //指定元素
            min: '{{$db['orderAddTime']}}',
            max: '{{$db['backDate']}}',
            value: payTimeVal,
        });
        //
        var adultNum = {!! $db['adultNum'] !!};
        var childNum = {!! $db['childNum'] !!};
        var aduAmount = {!! $db['aduAmount'] !!};
        var childAmount = {!! $db['childAmount'] !!};
        var amountAll = {!! $db['amountAll'] !!};
        $('#adultNum').val(adultNum);
        $('#childNum').val(childNum);
        $('#aduAmount').val(aduAmount);
        $('#childAmount').val(childAmount);
        $('#amountAll').val(amountAll);
        //
        var sadultPrice = {!! $db['sadultPrice'] !!};
        var schildPrice = {!! $db['schildPrice'] !!};
        var adultPrice = {!! $db['adultPrice'] !!};
        var childPrice = {!! $db['childPrice'] !!};
        admin.removeLoading();
        //
        var userIds = xmSelect.render({
            el: '#userIds',
            language: 'zn',
            initValue: [{!! $db['userIds'] !!}],
            on: function (data) {
                var arr = data.change;
                var isAdult = arr[0]['isAdult'];
                var ctInfo = arr[0]['ctInfo'];
                var isAdd = data.isAdd;//运算符
                if (isAdd == true) {
                    if (isAdult == 1) {
                        adultNum++;
                    } else {
                        childNum++;
                    }
                } else {
                    if (isAdult == 1) {
                        adultNum--;
                    } else {
                        childNum--;
                    }
                }
                $('#adultNum').val(adultNum)
                $('#childNum').val(childNum)
                _amountAll($('#adultNum').val(), $('#aduAmount').val(), $('#childNum').val(), $('#childAmount').val());
            },
            data: {!! $db['linePlanOrdUserApis'] !!}
        });
        $("#aduAmount").blur(function () {
            var obj = $(this);
            var aduAmount = obj.val();
            if (aduAmount > sadultPrice) {
                layer.msg('费用不能超过成人市场价！', {icon: 5});
                $('#aduAmount').val(sadultPrice);
            }
            if (aduAmount < adultPrice) {
                layer.msg('费用不能低于成人同行价！', {icon: 5});
                $('#aduAmount').val(adultPrice);
            }
            _amountAll($('#adultNum').val(), $('#aduAmount').val(), $('#childNum').val(), $('#childAmount').val());
            //
        });
        $("#childAmount").blur(function () {
            var obj = $(this);
            var childNum = obj.val();
            if (childNum > schildPrice) {
                layer.msg('费用不能超过儿童市场价！', {icon: 5});
                $('#childAmount').val(schildPrice);
            }
            if (childNum < childPrice) {
                layer.msg('费用不能低于儿童同行价！', {icon: 5});
                $('#childAmount').val(childPrice);
            }
            _amountAll($('#adultNum').val(), $('#aduAmount').val(), $('#childNum').val(), $('#childAmount').val());
        });

        //计算
        function _amountAll(adultNum, aduAmount, childNum, childAmount) {
            var adu = adultNum * aduAmount;
            var chi = childNum * childAmount;
            $('#amountAll').val(adu + chi);
        }


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
            okUtils.ajax("{{url('sys/pages/order/allContract/'.$db['id'])}}", "{{$db['id']?'put':'post'}}", data.field, true).done(function (response) {
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
