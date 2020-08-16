<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    @include('.sys.public.css')
    @include('.sys.public.js')
    <style>
        #formAdvForm .layui-form-item {
            margin-top: 20px;
            margin-bottom: 0;
        }

        #formAdvForm .layui-form-item .layui-inline {
            margin-bottom: 25px;
            margin-right: 0;
        }

        .form-group-bottom {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            padding: 10px 20px;
            background-color: #fff;
            box-shadow: 0 -1px 2px 0 rgba(0, 0, 0, .05);
        }
    </style>
</head>
<body>
<!-- 正文开始 -->
<form class="layui-form" id="formAdvForm" lay-filter="formAdvForm">
    <div class="layui-fluid" style="padding-bottom: 75px;">
        <button id="btnPrint" name="btnPrint" class="layui-btn layui-btn-primary">打印本页内容</button>


        <p style="text-align:center">
            <strong><span style="font-size:21px;font-family:华文细黑">民情受理不满意办件反核反馈表</span></strong>
        </p>
        <p style="text-align:center">
            <strong><span style="font-size:7px;font-family:华文细黑">&nbsp;</span></strong>
        </p>
        <p style="margin-left:336px;text-align:center;text-indent:42px">
            <span style=";font-family:华文细黑">办件编号:{{$db['event_number']}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>
        </p>
        <p style="margin-right:28px;text-align:center;text-indent:14px">
            <span style=";font-family:华文细黑">承办单位盖章:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;派发时间:{{$db['distribution_time']}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>
        </p>
        <table width="553" style="margin: auto;">
            <tbody>
            <tr style=";height:35px" class="firstRow">
                <td width="104" valign="top" style="border: 1px solid windowtext; padding: 0px 7px;">
                    <p style="margin-right:28px;text-align:right;line-height:200%">
                        <span style=";line-height:200%;font-family:华文细黑">来电市民</span>
                    </p>
                </td>
                <td width="173" valign="top"
                    style="border-top: 1px solid windowtext; border-right: 1px solid windowtext; border-bottom: 1px solid windowtext; border-image: initial; border-left: none; padding: 0px 7px;">{{$db['plaintiff']}}</td>
                <td width="101" valign="top"
                    style="border-top: 1px solid windowtext; border-right: 1px solid windowtext; border-bottom: 1px solid windowtext; border-image: initial; border-left: none; padding: 0px 7px;">
                    <p style="margin-right:28px;text-align:right;line-height:200%">
                        <span style=";line-height:200%;font-family:华文细黑">来电日期</span>
                    </p>
                </td>
                <td width="175" valign="top"
                    style="border-top: 1px solid windowtext; border-right: 1px solid windowtext; border-bottom: 1px solid windowtext; border-image: initial; border-left: none; padding: 0px 7px;">{{$db['recording_time']}}</td>
            </tr>
            <tr style=";height:37px">
                <td width="104" valign="top"
                    style="border-right: 1px solid windowtext; border-bottom: 1px solid windowtext; border-left: 1px solid windowtext; border-image: initial; border-top: none; padding: 0px 7px;">
                    <p style="margin-right:28px;text-align:right;line-height:200%">
                        <span style=";line-height:200%;font-family:华文细黑">联系电话</span>
                    </p>
                </td>
                <td width="173" valign="top"
                    style="border-top: none; border-left: none; border-bottom: 1px solid windowtext; border-right: 1px solid windowtext; padding: 0px 7px;">{{$db['contact_number']}}</td>
                <td width="101" valign="top"
                    style="border-top: none; border-left: none; border-bottom: 1px solid windowtext; border-right: 1px solid windowtext; padding: 0px 7px;">
                    <p style="margin-right:28px;text-align:right;line-height:200%">
                        <span style=";line-height:200%;font-family:华文细黑">联系地址</span>
                    </p>
                </td>
                <td width="175" valign="top"
                    style="border-top: none; border-left: none; border-bottom: 1px solid windowtext; border-right: 1px solid windowtext; padding: 0px 7px;">{{$db['event_address']}}</td>
            </tr>
            <tr style=";height:38px">
                <td width="104" valign="top"
                    style="border-right: 1px solid windowtext; border-bottom: 1px solid windowtext; border-left: 1px solid windowtext; border-image: initial; border-top: none; padding: 0px 7px;">
                    <p style="margin-right:28px;text-align:right;line-height:200%">
                        <span style=";line-height:200%;font-family:华文细黑">诉求内容</span>
                    </p>
                </td>
                <td width="449" colspan="3" valign="top"
                    style="border-top: none; border-left: none; border-bottom: 1px solid windowtext; border-right: 1px solid windowtext; padding: 0px 7px;">{{$db['details_of_the_incident']}}</td>
            </tr>
            <tr style=";height:43px">
                <td width="553" colspan="4" valign="top"
                    style="border-right: 1px solid windowtext; border-bottom: 1px solid windowtext; border-left: 1px solid windowtext; border-image: initial; border-top: none; padding: 0px 7px;">
                    <p style="margin-right:28px;text-align:left;line-height:200%">
                        <span style=";line-height:200%;font-family:华文细黑">转办意见：{{$db['opinions_on_transfer']}}</span>
                    </p>
                </td>
            </tr>
            <tr>
                <td width="553" colspan="4" valign="top"
                    style="border-right: 1px solid windowtext; border-bottom: 1px solid windowtext; border-left: 1px solid windowtext; border-image: initial; border-top: none; padding: 0px 7px;">
                    <p style="margin-right:28px;text-align:left;line-height:200%">
                        <span style=";line-height:200%;font-family:华文细黑">复核情况:（可附件）{{$db['review']}}</span>
                    </p>
                </td>
            </tr>
            <tr>
                <td width="553" colspan="4" valign="top"
                    style="border-right: 1px solid windowtext; border-bottom: 1px solid windowtext; border-left: 1px solid windowtext; border-image: initial; border-top: none; padding: 0px 7px;">
                    <p style="margin-right:28px;text-align:left;line-height:115%">
                        <span style=";line-height:115%;font-family:华文细黑">答复市民时间：{{$db['reply_time']}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;答复方式：{{$db['reply_mode']=='电话'?'☑':'□'}}电话 &nbsp; {{$db['reply_mode']=='书面'?'☑':'□'}}书面 &nbsp;{{$db['reply_mode']=='入户'?'☑':'□'}}入户</span>
                    </p>
                    <p style="margin-right:28px;text-align:left;line-height:115%">
                        <span style=";line-height:115%;font-family:华文细黑">是否解决： {{$db['is_it_solved']=='实际解决'?'☑':'□'}}实际解决&nbsp; {{$db['is_it_solved']=='解释说明'?'☑':'□'}}解释说明&nbsp; {{$db['is_it_solved']=='参考备案'?'☑':'□'}}参考备案&nbsp; {{$db['is_it_solved']=='诉求过高'?'☑':'□'}}诉求过高&nbsp; {{$db['is_it_solved']=='未解决'?'☑':'□'}}未解决</span>
                    </p>
                    <p style="margin-right:28px;text-align:left;line-height:115%">
                        <span
                            style=";line-height:115%;font-family:华文细黑">办理态度是否满意：{{$db['is_the_handling_attitude_satisfactory']=='满意'?'☑':'□'}}满意&nbsp; {{$db['is_the_handling_attitude_satisfactory']=='不满意'?'☑':'□'}}不满意&nbsp; {{$db['is_the_handling_attitude_satisfactory']=='认可'?'☑':'□'}}认可&nbsp; {{$db['is_the_handling_attitude_satisfactory']=='未评价'?'☑':'□'}}未评价</span>
                    </p>
                    <p style="margin-right:28px;text-align:left;line-height:115%">
                        <span
                            style=";line-height:115%;font-family:华文细黑">办理结果是否满意：{{$db['is_the_result_satisfactory']=='满意'?'☑':'□'}}满意&nbsp; {{$db['is_the_result_satisfactory']=='不满意'?'☑':'□'}}不满意&nbsp; {{$db['is_the_result_satisfactory']=='认可'?'☑':'□'}}认可&nbsp; {{$db['is_the_result_satisfactory']=='未评价'?'☑':'□'}}未评价</span>
                    </p>
                </td>
            </tr>
            <tr style=";height:134px">
                <td width="553" colspan="4" valign="top"
                    style="border-right: 1px solid windowtext; border-bottom: 1px solid windowtext; border-left: 1px solid windowtext; border-image: initial; border-top: none; padding: 0px 7px;">
                    <p style="margin-right:28px;text-align:left;line-height:150%">
                        <span style=";line-height:150%;font-family:华文细黑">市民反馈说明：{{$db['public_feedback']}}</span>
                    </p>
                </td>
            </tr>
            </tbody>
        </table>


    </div>
</form>
<script>
    layui.use(['layer', 'printer', 'admin'], function () {
        let $ = layui.jquery;
        let printer = layui.printer;

        // 打印任意内容
        $('#btnPrint').click(function () {
            printer.print({
                hide: ['#btnPrint']
            });
            // printer.printHtml({
            //     html: '<div style="color: red;text-align: center;">Hello Word !</div>',
            //     horizontal: false  // 横向打印
            // });
        });
    });
</script>
</body>
</html>
