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
        <div class="layui-tab" lay-filter="orderInfoTab">
            <ul class="layui-tab-title">
                @if(hasPower(249))
                    <li class="layui-this" lay-id="11">订单详情</li>@endif
                @if($db['classify']==0)
                    @if(hasPower(256))
                        <li lay-id="22">账单信息</li>@endif
                    @if(hasPower(257))
                        <li lay-id="33">游客名单</li>@endif
                    @if($db['isOk']>0 && hasPower(258))
                        <li lay-id="44">电子合同</li>@endif
                    @if($db['isOk']>0 && hasPower(278))
                        <li lay-id="55">发票申请</li>@endif
                @endif
            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show">
                    <div class="layui-btn-container">
                        @if($db['classify']==0)
                            @if($db['isOk']>0)
                                @if(hasPower(251))
                                    <button class="layui-btn layui-btn-sm" id="confirmation">确认书</button>@endif
                                @if(hasPower(252))
                                    <button class="layui-btn layui-btn-sm" id="notification">出团通知书</button>@endif
                            @endif
                            {{--                        <button class="layui-btn layui-btn-normal layui-btn-sm" id="ord_log">日志</button>--}}
                        @endif
                    </div>
                    <hr>
                    <div class="layui-form-item">
                        <label class="layui-form-label">订单编号</label>
                        <div class="layui-form-mid">
                            {!! $db['id'] !!}
                            {!! $db['ordTypeName'] !!}
                            {!! $db['statusShow'] !!}
                            @if($db['isOk']==0)
                                <a class="layui-btn layui-btn-primary layui-btn-xs">{!! $db['endTime'] !!}</a>
                            @endif
                            @if($db['isOk']>=0 && $db['noPingAmount']>0)
                                <a class="layui-btn layui-bg-orange layui-btn-xs" href="javascript:" title="支付"
                                   id="pay">支付</a>
                            @endif
                        </div>
                    </div>
                    @if($db['classify']==0)
                        <div class="layui-form-item">
                            <label class="layui-form-label">线路名称</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" value="{!! $db['lineTitle'] !!}" disabled>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">出团日期</label>
                                <div class="layui-input-inline">
                                    <input type="text" class="layui-input" value="{!! $db['planDate'] !!}" disabled>
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">回团日期</label>
                                <div class="layui-input-inline">
                                    <input type="text" class="layui-input" value="{!! $db['backDate'] !!}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">出发城市</label>
                                <div class="layui-input-inline">
                                    <input type="text" class="layui-input" value="{!! $db['fromCityName'] !!}" disabled>
                                </div>
                            </div>
                            @if(!is_null($db['pubFromcityName']))
                                <div class="layui-inline">
                                    <label class="layui-form-label">联运城市</label>
                                    <div class="layui-input-inline">
                                        <input type="text" class="layui-input" value="{!! $db['pubFromcityName'] !!}"
                                               disabled>
                                    </div>
                                </div>
                            @endif
                            <div class="layui-inline">
                                <label class="layui-form-label">套餐名称</label>
                                <div class="layui-input-inline">
                                    <input type="text" class="layui-input" value="{!! $db['priceTitle'] !!}" disabled>
                                </div>
                            </div>
                        </div>
                        @if(_admIsVip()==1)
                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label">应收总额</label>
                                    <div class="layui-form-mid">{!! $db['amount'] !!}</div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">已收总额</label>
                                    <div class="layui-form-mid">{!! $db['pingAmount'] !!}</div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">未收总额</label>
                                    <div class="layui-form-mid">{!! $db['noPingAmount'] !!}</div>
                                </div>
                            </div>
                        @else
                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label">应付总额</label>
                                    <div class="layui-form-mid">{!! $db['amount'] !!}</div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">已付总额</label>
                                    <div class="layui-form-mid">{!! $db['pingAmount'] !!}</div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">未付总额</label>
                                    <div class="layui-form-mid">{!! $db['noPingAmount'] !!}</div>
                                </div>
                            </div>
                        @endif

                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">总人数</label>
                                <div class="layui-form-mid">{!! $db['perNum'] !!}</div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">成人数</label>
                                <div class="layui-form-mid">{!! $db['aduNum'] !!}</div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">老人数</label>
                                <div class="layui-form-mid">{!! $db['aduNum2'] !!}</div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">小青年</label>
                                <div class="layui-form-mid">{!! $db['aduNum1'] !!}</div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">儿童数</label>
                                <div class="layui-form-mid">{!! $db['chdNum'] !!}</div>
                            </div>
                        </div>
                    @elseif($db['classify']==1)
                        <div class="layui-form-item">
                            <label class="layui-form-label">商品名称</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" value="{!! $db['lineTitle'] !!}" disabled>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">商品规格</label>
                                <div class="layui-input-inline">
                                    <input type="text" class="layui-input"
                                           value="{!! $db['priceTitle']==='默认套餐'?'标准':$db['priceTitle'] !!}" disabled>
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">商品数量</label>
                                <div class="layui-input-inline">
                                    <input type="text" class="layui-input" value="{!! $db['perNum'] !!}" disabled>
                                </div>
                            </div>
                        </div>
                        @if(_admIsVip()==1)
                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label">应收总额</label>
                                    <div class="layui-form-mid">{!! $db['amount'] !!}</div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">已收总额</label>
                                    <div class="layui-form-mid">{!! $db['pingAmount'] !!}</div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">未收总额</label>
                                    <div class="layui-form-mid">{!! $db['noPingAmount'] !!}</div>
                                </div>
                            </div>
                        @else
                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label">应付总额</label>
                                    <div class="layui-form-mid">{!! $db['amount'] !!}</div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">已付总额</label>
                                    <div class="layui-form-mid">{!! $db['pingAmount'] !!}</div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">未付总额</label>
                                    <div class="layui-form-mid">{!! $db['noPingAmount'] !!}</div>
                                </div>
                            </div>
                        @endif
                    @endif
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">下单时间</label>
                            <div class="layui-input-inline">
                                <input type="text" class="layui-input" value="{!! $db['addTime'] !!}" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item layui-form-text">
                        <label class="layui-form-label">订单备注</label>
                        <div class="layui-input-block">
                            <textarea id="ordBak" placeholder="请输入内容"
                                      class="layui-textarea" @if(!hasPower(267)) disabled @endif
                                      @if($db['isOk']!==0) disabled @endif>{!! $db['ordBak'] !!}</textarea>
                            <div class="layui-form-mid layui-word-aux">仅在状态为 [待支付] 时可以编辑, 鼠标点击空白处进行保存</div>
                        </div>
                    </div>
                    <hr>
                    @if(_admIsVip()==1)
                        <div class="layui-form-item">
                            <label class="layui-form-label">供应商</label>
                            <div class="layui-form-mid">{!! $db['supCpyName'] !!} {!! $db['supCpyMobile'] !!}</div>
                        </div>
                    @endif
                </div>
                <div class="layui-tab-item">
                    <table class="layui-hide" id="billTableId"></table>
                </div>
                <div class="layui-tab-item">
                    <!--模糊搜索区域-->
                    <div class="layui-row">
                        <form class="layui-form layui-col-md12 ok-search">
                            {{--                            <input class="layui-input" placeholder="开始日期" autocomplete="off" id="start_time"--}}
                            {{--                                   name="start_time">--}}
                            {{--                            <input class="layui-input" placeholder="截止日期" autocomplete="off" id="end_time"--}}
                            {{--                                   name="end_time">--}}
                            {{--                            <input class="layui-input" placeholder="请输入广告标题" autocomplete="off" name="title">--}}
                            {{--                            <button class="layui-btn" lay-submit="" lay-filter="search">--}}
                            {{--                                <i class="layui-icon layui-icon-search"></i>--}}
                            {{--                            </button>--}}
                        </form>
                    </div>
                    <table class="layui-hide" id="userTableId" lay-filter="userFilter"></table>
                </div>
                <div class="layui-tab-item">
                    <!--模糊搜索区域-->
                    {{--                    <div class="layui-row">--}}
                    {{--                        <form class="layui-form layui-col-md12 ok-search">--}}
                    {{--                            <input class="layui-input" placeholder="开始日期" autocomplete="off" id="start_time" name="start_time">--}}
                    {{--                            <input class="layui-input" placeholder="截止日期" autocomplete="off" id="end_time" name="end_time">--}}
                    {{--                            <input class="layui-input" placeholder="请输入搜索关键字" autocomplete="off" name="key">--}}
                    {{--                            <div class="layui-inline">--}}
                    {{--                                <label class="layui-form-label">请选择状态</label>--}}
                    {{--                                <div class="layui-input-inline">--}}
                    {{--                                    <select name="is_lock" lay-verify="">--}}
                    {{--                                        <option value="" selected>请选择状态</option>--}}
                    {{--                                        <option value="0">待发起</option>--}}
                    {{--                                        <option value="1">签署中</option>--}}
                    {{--                                        <option value="2">已签署</option>--}}
                    {{--                                        <option value="4">已作废</option>--}}
                    {{--                                    </select>--}}
                    {{--                                </div>--}}
                    {{--                            </div>--}}
                    {{--                            <button class="layui-btn" lay-submit="" lay-filter="search">--}}
                    {{--                                <i class="layui-icon layui-icon-search"></i>--}}
                    {{--                            </button>--}}
                    {{--                        </form>--}}
                    {{--                    </div>--}}
                    <div class="layui-row">
                        <form class="layui-form layui-col-md12 ok-search">
                            {{--                            <input class="layui-input" placeholder="开始日期" autocomplete="off" id="start_time"--}}
                            {{--                                   name="start_time">--}}
                            {{--                            <input class="layui-input" placeholder="截止日期" autocomplete="off" id="end_time"--}}
                            {{--                                   name="end_time">--}}
                            {{--                            <input class="layui-input" placeholder="请输入广告标题" autocomplete="off" name="title">--}}
                            {{--                            <button class="layui-btn" lay-submit="" lay-filter="search">--}}
                            {{--                                <i class="layui-icon layui-icon-search"></i>--}}
                            {{--                            </button>--}}
                        </form>
                    </div>
                    <!--数据表格-->
                    <table class="layui-hide" id="eContractTableId" lay-filter="eContractFilter"></table>
                </div>
                <div class="layui-tab-item">
                    <blockquote class="layui-elem-quote layui-quote-nm gray2">峰景提示:&nbsp;为了响应国家环保倡议，即日起，峰景将推行电子发票(免费)，不在开具纸质发票，电子发票与纸质发票具有同等效力。<br>发票的申请时间将在出团后15天内均可申请；<br>开票金额为您的实际付款金额；
                    </blockquote>
                    <!--模糊搜索区域-->
                    <div class="layui-row">
                        <form class="layui-form layui-col-md12 ok-search">
                            {{--                            <input class="layui-input" placeholder="开始日期" autocomplete="off" id="start_time"--}}
                            {{--                                   name="start_time">--}}
                            {{--                            <input class="layui-input" placeholder="截止日期" autocomplete="off" id="end_time"--}}
                            {{--                                   name="end_time">--}}
                            {{--                            <input class="layui-input" placeholder="请输入广告标题" autocomplete="off" name="title">--}}
                            {{--                            <button class="layui-btn" lay-submit="" lay-filter="search">--}}
                            {{--                                <i class="layui-icon layui-icon-search"></i>--}}
                            {{--                            </button>--}}
                        </form>
                    </div>
                    <table class="layui-hide" id="invoicelTable" lay-filter="invoicelFilter"></table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

<!--js逻辑-->
<script>
    var json;
    layui.use(["admin", "element", "form", "jquery", "table", "okUtils", "okLayer", "layer"], function () {
        let admin = layui.admin;
        let form = layui.form;
        let table = layui.table;
        let okLayer = layui.okLayer;
        let okUtils = layui.okUtils;
        let element = layui.element;
        let $ = layui.jquery;
        //Hash地址的定位
        var layid = location.hash.replace(/^#orderInfoTab=/, '');
        element.tabChange('orderInfoTab', layid);
        element.on('tab(orderInfoTab)', function (elem) {
            location.hash = 'orderInfoTab=' + $(this).attr('lay-id');
        });
        admin.removeLoading();
        //
        $("#confirmation").click(function () {
            window.open('{{url(site()['callWebApi'].'api/order/pdf/orderConfirmation.html?ordId='. $db['id'] )}}');
        });
        $("#notification").click(function () {
            window.open('{{url(site()['callWebApi'].'api/order/pdf/orderNotice.html?ordId='. $db['id'] )}}');
        });
        $("#pay").click(function () {
            okLayer.open("支付", "../orderPay/{{$db['id']}}/-1", "380px", "520px", null, function () {
                location.reload();
            })
        });

        $("#ordBak").blur(function () {
            @if($db['isOk']==0)
            okUtils.ajax("{{url('/sys/pages/order/orderTableEdit')}}", "post", {
                id: '{!! $db['id'] !!}',
                tableName: 'line_plan_ord',
                field: "ordBak",
                value: this.value,
                _token: '{{csrf_token()}}'
            }, true).done(function (response) {
                okUtils.tableSuccessMsg(response.msg);
            }).fail(function (error) {
                console.log(error)
            });
            @endif
        });

        {{--$("#ord_log").click(function () {--}}
        {{--    var tableName = 'line_plan_ord';--}}
        {{--    var tableId = '{!! $db['id'] !!}';--}}
        {{--    var tableStr = 'null';--}}
        {{--    okLayer.open("日志", "/sys/pages/logInfo/" + tableName + '/' + tableId+ '/' + tableStr, "90%", "90%", null, function () {--}}
        {{--    })--}}
        {{--});--}}

        //账单
        let orderBillTable = table.render({
            elem: "#billTableId",
            data: {!!$db['linePlanOrdBillApis']!!},
            title: '订单账单列表_{{getTime(3)}}',
            even: true,
            totalRow: true,
            size: "sm",
            cols: [[
                {field: "id", title: "编号", width: 80, align: "center", totalRowText: '合计'},
                {field: "theTitle", title: "摘要", width: 220, sort: true},
                {field: "ordId", title: "订单编号", width: 80, align: "center"},
                {field: "theNum", title: "数量", width: 80, sort: true},
                {field: "thePrice", title: "单价", width: 100, sort: true},
                {field: "amount", title: "账单总额", width: 110, sort: true, totalRow: true},
            ]],
            done: function (res, curr, count) {
                //console.log(res, curr, count)
            }
        });
        //名单
        let orderUserTable = table.render({
            elem: "#userTableId",
            data: {!!$db['linePlanOrdUserApis']!!},
            title: '订单名单列表_{{getTime(3)}}',
            even: true,
            page: true,
            toolbar: '<div class="layui-btn-container">\n' +
                '<button class="layui-btn layui-btn-normal layui-btn-sm" lay-event="user_log">日志</button>\n' +
                '</div>',
            size: "sm",
            cols: [[
                {
                    title: "操作", width: 80, align: "center", templet: function (d) {
                        var editUserInfo = '';
                        if (d.isLock == 0) {
                            editUserInfo = "@if(hasPower(259))<a href=\"javascript:\" title=\"修改\" lay-event=\"editUser\"><i class=\"layui-icon\">&#xe642;</i></a>@endif";
                        }
                        return editUserInfo;
                    }
                },
                {field: "id", title: "编号", width: 80, align: "center"},
                {
                    field: "isLock_name",
                    title: "状态",
                    width: 70,
                    align: "center",
                    sort: true,
                    event: 'user_log',
                    style: 'cursor: pointer;'
                },
                {field: "cnName", title: "中文名", width: 80},
                {field: "enName1", title: "英文姓", width: 80},
                {field: "enName2", title: "英文名", width: 80},
                {field: "nation", title: "民族", width: 70, align: "center", sort: true},
                {field: "sex", title: "性别", width: 70, align: "center", sort: true},
                {field: "perType", title: "性质", width: 80, sort: true},
                {field: "birth", title: "出生日期", width: 100},
                {field: "pob", title: "出生地", width: 80},
                {field: "idType_name", title: "证件类型", width: 120, sort: true},
                {field: "passport", title: "证件号码", width: 180},
                {field: "doi", title: "签发日期", width: 100},
                {field: "doe", title: "有效日期", width: 100},
                {field: "poi", title: "签发地", width: 80},
                {field: "idCard", title: "身份证", width: 160},
                {field: "ctInfo", title: "联系方式", width: 120},
                {field: "address", title: "地址", width: 220},
                {field: "remark", title: "备注", width: 260},
            ]],
            done: function (res, curr, count) {
                //console.log(res, curr, count)
            }
        });
        table.on("tool(userFilter)", function (obj) {
            let data = obj.data;
            switch (obj.event) {
                case "editUser":
                    editUser(data);
                    break;
                case "user_log":
                    user_log(data.id);
                    break;
            }
        });
        table.on("toolbar(userFilter)", function (obj) {
            switch (obj.event) {
                case "user_log":
                    user_log();
                    break;
                case "":
                    break;
            }
        });

        function user_log(id) {
            var tableName = 'line_plan_ord_user';
            var tableId = id;
            var tableStr = 'null';
            okLayer.open("日志", "/sys/pages/logInfo/" + tableName + '/' + tableId + '/' + tableStr, "90%", "90%", null, function () {
            })
        }

        function editUser(data) {
            json = JSON.stringify(data);
            okLayer.open("编辑名单", "/sys/pages/order/orderUserInfo/" + data.id + "/edit", "90%", "90%", null, function () {
                    orderUserTable.reload();
                }
            )
        }

        //电子合同
        let orderEContractTable = table.render({
            elem: "#eContractTableId",
            url: '{{url('/sys/pages/order/eContractRead')}}',
            title: '订单电子合同列表_{{getTime(3)}}',
            where: {'orderId':{!! $db['id'] !!}},
            even: true,
            page: true,
            toolbar: '<div class="layui-btn-container">\n' +
                @if(hasPower(260)) '<button class="layui-btn layui-btn-sm" lay-event="e_add">添加合同</button>\n' + @endif
                    '<button class="layui-btn layui-btn-normal layui-btn-sm" lay-event="e_log">日志</button>\n' +
                '</div>',
            size: "sm",
            cols: [[
                {
                    title: "操作", width: 80, align: "center", templet: function (d) {
                        var e_edit = "@if(hasPower(261))<a href=\"javascript:\" title=\"修改\" lay-event=\"e_edit\"><i class=\"layui-icon\">&#xe642;</i></a>@endif";
                        var sign = "@if(hasPower(262))<a href=\"javascript:\" title=\"发起签署\" lay-event=\"sign\"><i class=\"layui-icon\">&#xe609;</i></a>@endif";
                        var signSms = "@if(hasPower(263))<a href=\"javascript:\" title=\"发送签署短信\" lay-event=\"signSms\"><i class=\"layui-icon\">&#xe618;</i></a>@endif";
                        var signCancel = "@if(hasPower(265))<a href=\"javascript:\" title=\"作废合同\" lay-event=\"signCancel\"><i class=\"layui-icon\">&#x1006;</i></a>@endif";
                        var signDelete = "@if(hasPower(265))<a href=\"javascript:\" title=\"删除合同\" lay-event=\"signDelete\"><i class=\"layui-icon\">&#xe640;</i></a>@endif";
                        var download = "@if(hasPower(266))<a href=\"javascript:\" title=\"下载\" lay-event=\"download\"><i class=\"layui-icon\">&#xe601;</i></a>@endif";
                        if (d.status == 0) {
                            return e_edit + sign + signDelete;
                        } else if (d.status == 1) {
                            return signSms + signCancel;
                        } else if (d.status == 2) {
                            return download;
                        } else {
                            return signDelete;
                        }
                    }
                },
                {field: "id", title: "编号", width: 80, align: "center"},
                {
                    field: "statusShow",
                    title: "状态",
                    width: 70,
                    align: "center",
                    sort: true,
                    event: 'e_log',
                    style: 'cursor: pointer;'
                },
                {field: "contractNo", title: "合同编号", width: 200},
                {field: "cusName", title: "游客代表", width: 80},
                {field: "cusMoblie", title: "代表手机号", width: 110},
                {field: "userNames", title: "合同成员", width: 160},
                {field: "others", title: "其他约定", width: 120},
                {field: "addTime", title: "添加时间", width: 145, align: "center", sort: true},
                {field: "totalPrice", title: "旅游费用", width: 80},
            ]],
            done: function (res, curr, count) {
                //console.log(res, curr, count)
            }
        });
        table.on("toolbar(eContractFilter)", function (obj) {
            switch (obj.event) {
                case "e_add":
                    e_add();
                    break;
                case "e_log":
                    e_log();
            }
        });

        function e_log(id) {
            var tableName = 'electronic_contract';
            var tableId = id;
            var tableStr = 'orderId,minPlanNum,aduAmount,childAcmount,amountAll,payTime,transactorName,transactorPhone,other,traveler,travelmobile';
            okLayer.open("日志", "/sys/pages/logInfo/" + tableName + '/' + tableId + '/' + tableStr, "90%", "90%", null, function () {
            })
        }

        function e_add() {
            json = JSON.stringify('');
            okLayer.open("添加合同", "/sys/pages/order/allContract/{{$db['id']}}", "90%", "90%", null, function () {
                window.location.reload()
            })
        }

        table.on("tool(eContractFilter)", function (obj) {
            let data = obj.data;
            switch (obj.event) {
                case "e_edit":
                    e_edit(data);
                    break;
                case "sign":
                    sign(data);
                    break;
                case "signSms":
                    signSms(data.id);
                    break;
                case "signCancel":
                    signCancel(data);
                    break;
                case "signDelete":
                    signDelete(data.id);
                    break;
                case "download":
                    download(data.fileUrl);
                    break;
                case "e_log":
                    e_log(data.id);
                    break;
            }
        });

        function e_edit(data) {
            json = JSON.stringify(data);
            okLayer.open("编辑合同", "/sys/pages/order/allContract/" + data.id + "/edit", "90%", "90%", null, function () {
                window.location.reload()
            })
        }

        function sign(data) {
            okLayer.confirm("确定要发起签署吗？", function () {
                okUtils.ajax("/sys/pages/order/eContractSign", "post", {
                    id: data.id,
                    transactorName: data.transactorName,
                    transactorPhone: data.transactorPhone,
                    _token: '{{csrf_token()}}'
                }, true).done(function (response) {
                    okUtils.tableSuccessMsg(response.msg);
                    // orderEContractTable.reload();
                    window.location.reload()
                }).fail(function (error) {
                    console.log(error)
                });
            })
        }

        function signSms(id) {
            okLayer.confirm("确定要重新发送签署短信吗？", function () {
                okUtils.ajax("/sys/pages/order/eContractSignSms", "post", {
                    id: id,
                    _token: '{{csrf_token()}}'
                }, true).done(function (response) {
                    okUtils.tableSuccessMsg(response.msg);
                    orderEContractTable.reload();
                }).fail(function (error) {
                    console.log(error)
                });
            })
        }


        function signCancel(data) {
            okLayer.confirm("确定要作废合同吗？", function () {
                okUtils.ajax("/sys/pages/order/eContractSignCancel", "post", {
                    id: data.id,
                    ordId: data.ordId,
                    planId: data.planId,
                    contractNo: data.contractNo,
                    _token: '{{csrf_token()}}'
                }, true).done(function (response) {
                    okUtils.tableSuccessMsg(response.msg);
                    // orderEContractTable.reload();
                    window.location.reload()
                }).fail(function (error) {
                    console.log(error)
                });
            })
        }


        function signDelete(id) {
            okLayer.confirm("确定要删除合同吗？", function () {
                okUtils.ajax("/sys/pages/order/eContractSignDelete", "post", {
                    id: id,
                    _token: '{{csrf_token()}}'
                }, true).done(function (response) {
                    okUtils.tableSuccessMsg(response.msg);
                    orderEContractTable.reload();
                }).fail(function (error) {
                    console.log(error)
                });
            })
        }

        function download(fileUrl) {
            window.open(fileUrl);
        }

        //发票申请
        let orderInvoicelTable = table.render({
            elem: "#invoicelTable",
            url: '{{url('/sys/pages/order/invoiceRead')}}',
            title: '订单发票列表_{{getTime(3)}}',
            where: {'orderId':{!! $db['id'] !!}},
            even: true,
            page: true,
            toolbar: '<div class="layui-btn-container">\n' +
                @if(hasPower(276) && $db['invoicing_time']<=15 && $db['invoicing_time']>0) '<button class="layui-btn layui-btn-sm" lay-event="invoice_add">申请发票</button>\n' + @endif
                    @if($db['invoicing_time']>15)'已超过发票申请时间\n' + @endif
                    @if($db['invoicing_time']<0)'请在出团后15天内进行发票申请\n' + @endif
                    '<button class="layui-btn layui-btn-normal layui-btn-sm" lay-event="invoice_log">日志</button>\n' +
                '</div>',
            totalRow: true,
            size: "sm",
            cols: [[
                {
                    title: "操作", width: 80, align: "center", templet: function (d) {
                        var invoice_edit = "@if(hasPower(277))<a href=\"javascript:\" title=\"修改\" lay-event=\"invoice_edit\"><i class=\"layui-icon\">&#xe642;</i></a>@endif";
                        var invoice_download = "@if(hasPower(279))<a href=\"javascript:\" title=\"下载\" lay-event=\"invoice_download\"><i class=\"layui-icon\">&#xe601;</i></a>@endif";
                        if (d.isOk == -1 || d.isOk == -2 || d.isOk == -3) {
                            return invoice_edit;
                        } else if (d.isOk == 1) {
                            if (d.electronicInvoiceFiles !== 'null') {
                                return invoice_download;
                            } else {
                                return '';
                            }
                        } else {
                            return '';
                        }
                    }
                },
                {field: "id", title: "编号", width: 80, align: "center", totalRowText: '合计'},
                {
                    field: "isOkShow",
                    title: "状态",
                    width: 80,
                    sort: true,
                    event: 'invoice_log',
                    style: 'cursor: pointer;'
                },
                {field: "cpyName", title: "开票抬头", width: 180, sort: true},
                {field: "amount", title: "开票金额", width: 100, align: "center", totalRow: true},
                {field: "invoice", title: "开票内容", width: 120, sort: true},
                {field: "taxpayerIdentificationNumber", title: "纳税人识别号", width: 180},
                {field: "addr", title: "开票地址", width: 220},
                {field: "phone", title: "电话", width: 110},
                {field: "accBank", title: "开户行", width: 130},
                {field: "accCard", title: "账号", width: 120},
                {field: "simDesc", title: "备注说明", width: 180},
                {field: "kindType", title: "发票种类", width: 80},
                {field: "addTime", title: "添加时间", width: 180},
            ]],
            done: function (res, curr, count) {
                //console.log(res, curr, count)
            }
        });
        table.on("toolbar(invoicelFilter)", function (obj) {
            switch (obj.event) {
                case "invoice_add":
                    invoice_add();
                    break;
                case "invoice_log":
                    invoice_log();
            }
        });

        function invoice_log(id) {
            var tableName = 'base_invoice';
            var tableId = id;
            var tableStr = 'orderId,invoice,amount,cpyName,taxpayerIdentificationNumber,addr,phone,accBank,accCard,simDesc,files';
            okLayer.open("日志", "/sys/pages/logInfo/" + tableName + '/' + tableId + '/' + tableStr, "90%", "90%", null, function () {
            })
        }


        function invoice_add() {
            json = JSON.stringify('');
            okLayer.open("申请发票", "/sys/pages/order/invoice/{{$db['id']}}", "90%", "90%", null, function () {
                window.location.reload()
            })
        }

        table.on("tool(invoicelFilter)", function (obj) {
            let data = obj.data;
            switch (obj.event) {
                case "invoice_edit":
                    invoice_edit(data);
                    break;
                case "invoice_log":
                    invoice_log(data.id);
                    break;
                case "invoice_download":
                    invoice_download(data.electronicInvoiceFiles);
                    break;
            }
        });

        function invoice_edit(data) {
            json = JSON.stringify(data);
            okLayer.open("编辑发票", "/sys/pages/order/invoice/" + data.id + "/edit", "90%", "90%", null, function () {
                window.location.reload()
            })
        }

        function invoice_download(electronicInvoiceFiles) {
            window.open(electronicInvoiceFiles);
        }

    })
</script>
</body>
</html>
