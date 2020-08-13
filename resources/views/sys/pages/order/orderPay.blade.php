<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Expires" content="0"/>
    <meta http-equiv="Pragma" content="no-cache"/>
    <meta http-equiv="Cache-control" content="no-cache"/>
    <meta http-equiv="Cache" content="no-cache" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.5,user-scalable=no" />
    @include('.sys.public.css')
    <script src="/js/jquery-1.9.0.min.js"></script>
    @include('.sys.public.js')
    <style>
        body {
            text-align: center;
            background-color: #eee;
        }

        .text-center {
            font: 14px/1.5 'Microsoft Yahei';
            box-sizing: border-box;
            padding: 40px 24px;
            background-color: #fff;
            position: fixed;
            top: 5%;
            left: 50%;
            width: 90%;
            -webkit-transform: translateX(-50%) translateY(-50%);
        }

        .con_qr {
            height: 35px;
            line-height: 35px;
            width: 100%;
            text-align: center;
            margin: 15px auto;
            border-bottom: 1px dashed #eee;
        }

        .qrcode_merc .merc_nm, .qrcode_amount .amount, .qrcode_subject .subject, .qrcode_order .order {
            color: #808080;
            float: right;
        }

        .content, .errMsg {
            position: relative;
        }

        .content .title {
            font-size: 18px;
            text-align: right;
            float: left;
            color: #808080;
        }

        .qrcode_amount {
            font-size: 26px;
            height: 50px;
            border-bottom: 1px dashed #888;
            color: #000;
        }

        .amount_title {
            float: left;
            color: #808080;
        }

        .errMsg {
            width: 100%;
            height: 100%;
            display: none;
            margin-top: 70px;
            display: none;
        }

        .errMsg .text {
            padding-top: 25px;
            font-size: 24px;
            color: #525252;
        }

        .errMsg .text-cont {
            padding: 0 40px;
            margin-top: 5px;
            font-size: 14px;
            color: #777;
            margin-bottom: 21px;
        }

        .qrcode {
            display: inline-block;
        }

        .qrcode img {
            border: solid 10px #fff;
        }
    </style>
</head>
<body>
<div class="ok-body text-center" id="text-center">
    @if($db['code']==200)
        <div class="content">
            <div class="qrcode_amount"><span class="amount_title">付款金额</span><span
                    class="amount">￥{!! $db['amount'] !!}</span></div>
            <div class="qrcode_merc con_qr"><span class="title">收款方</span><span
                    class="merc_nm">{!! $db['appStoreName'] !!}</span></div>
            <div class="qrcode_order con_qr"><span class="title">订单号</span><span
                    class="order">{!! $db['orderId'] !!}</span></div>
            <div class="qrcode_subject"><span class="title">支付报告</span><span
                    class="subject" style="font-size: 12px;">{!! $db['abstract'] !!}</span></div>
            <div class="qrcode_order con_qr"></div>
            <div class="qrcode"></div>
            <div id="qrcode_hide" class="qrcode_hide">

            </div>
            <div class="form_group">请使用「微信/支付宝」扫码</div>
        </div>
    @else
        <div class="errMsg">
            <div>
                <img src="https://cdn.op110.com.cn/lib/imgs/codepay/icon_fail.png"/>
                <p class="text"></p>
                <p class="text-cont"> {!! $db['msg'] !!}</p>
            </div>
        </div>
    @endif
</div>
<!--js逻辑-->
<script type="text/javascript">
    //设置背景
    if (document.body.scrollWidth > 500) {
        $(".text-center").css("max-width", "500px");
        $(".text-center").css("max-height", "650px");
        $(".con_qr").css("margin", "15px auto;");
    } else {
        $(".text-center").css("width", document.body.scrollWidth - 20);
        $(".text-center").css("height", document.body.scrollHeight - 20);
        $(".con_qr").css("margin", "5px 0");
    }
    $(".errMsg").show();
    $(".content").show();
    //
    var path = '{!! $db['qrcodeKey'] !!}';
    //显示二维码
    var qrcodeWidth = document.body.scrollWidth * 0.5;
    if (qrcodeWidth > 256) {
        qrcodeWidth = 256;
    }
    layui.use(["qrcode", "jquery"], function () {
        let qrcode = layui.qrcode;
        let $ = layui.jquery;
        var q = new qrcode($(".qrcode")[0], {
            width: qrcodeWidth,
            height: qrcodeWidth,
            text: path
        });
    });
</script>
</body>
</html>

