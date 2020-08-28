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
        <div class="layui-card">
            <div class="layui-card-header">原件基本信息</div>
            <div class="layui-card-body">
                <div class="layui-form-item layui-row">
                    <div class="layui-inline layui-col-md4">
                        <label class="layui-form-label layui-form-required">事件编号:</label>
                        <div class="layui-input-block">
                            <input name="event_number" placeholder="请输入事件编号" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['event_number']}}" required/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md4">
                        <label class="layui-form-label layui-form-required">记录时间:</label>
                        <div class="layui-input-block">
                            <input name="recording_time" placeholder="请输入记录时间" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['recording_time']}}" required/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md4">
                        <label class="layui-form-label layui-form-required">诉求人:</label>
                        <div class="layui-input-block">
                            <input name="plaintiff" placeholder="请输入诉求人" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['plaintiff']}}" required/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md4">
                        <label class="layui-form-label layui-form-required">联系电话:</label>
                        <div class="layui-input-block">
                            <input name="contact_number" placeholder="请输入联系电话" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['contact_number']}}" required/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md4">
                        <label class="layui-form-label layui-form-required">事件类型:</label>
                        <div class="layui-input-block">
                            <input name="event_type" placeholder="请输入事件类型" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['event_type']}}" required/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md4">
                        <label class="layui-form-label layui-form-required">事件标题:</label>
                        <div class="layui-input-block">
                            <input name="event_title" placeholder="请输入事件标题" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['event_title']}}" required/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md4">
                        <label class="layui-form-label layui-form-required">事件大类:</label>
                        <div class="layui-input-block">
                            <input name="event_max_category" placeholder="请输入事件大类" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['event_max_category']}}"
                                   required/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md4">
                        <label class="layui-form-label layui-form-required">事件地址:</label>
                        <div class="layui-input-block">
                            <input name="event_address" placeholder="请输入事件地址" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['event_address']}}" required/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md4">
                        <label class="layui-form-label layui-form-required">紧急程度:</label>
                        <div class="layui-input-block">
                            <input name="emergency_degree" placeholder="请输入紧急程度" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['emergency_degree']}}"
                                   required/>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label layui-form-required">事件详情:</label>
                        <div class="layui-input-block" style="width: 710px;">
                                 <textarea name="details_of_the_incident" placeholder="请输入事件详情" class="layui-textarea"
                                           lay-verType="tips" lay-verify="required"
                                           required/>{{$db['details_of_the_incident']}}</textarea>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label layui-form-required">转办意见:</label>
                        <div class="layui-input-block" style="width: 710px;">
                            <textarea name="opinions_on_transfer" placeholder="请输入转办意见" class="layui-textarea"
                                      lay-verType="tips" lay-verify="required"
                                      required/>{{$db['opinions_on_transfer']}}</textarea>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md7">
                        <label class="layui-form-label layui-form-required">领导批示:</label>
                        <div class="layui-inline layui-col-md2">
                            <div class="layui-input-inline" style="width: 110px;">
                                <select name="approval_name" lay-verType="tips" lay-verify="required" required>
                                    <option value="">领导姓名</option>
                                    <option value="1">书记</option>
                                    <option value="2">副书记</option>
                                </select>
                            </div>
                        </div>
                        <div class="layui-inline layui-col-md6">
                            <div class="layui-input-inline" style="width: 350px;">
                                <input name="approval_proposal" placeholder="请输入批示建议" class="layui-input"
                                       lay-verType="tips" lay-verify="required" value="{{$db['event_address']}}"
                                       required/>
                            </div>
                        </div>
                        <div class="layui-inline layui-col-md2">
                            <div class="layui-input-inline" style="width: 232px;">
                                <input id="approval_time" name="approval_time" placeholder="请输入批示时间" class="layui-input"
                                       lay-verType="tips" lay-verify="required" value="{{$db['event_address']}}"
                                       required/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-card">
            <div class="layui-card-header">原件办理过程</div>
            <div class="layui-card-body">
                <div class="layui-form-item layui-row">
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label">市级结果:</label>
                        <div class="layui-input-block">
                             <textarea name="process" placeholder="请输入市级办理结果" class="layui-textarea"
                                       style="height: 160px;"
                                       lay-verType="tips" lay-verify="required"
                                       required/>{{$db['process']}}</textarea>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label">区级结果:</label>
                        <div class="layui-input-block">
                              <textarea name="process" placeholder="请输入区级办理结果" class="layui-textarea"
                                        style="height: 160px;"
                                        lay-verType="tips" lay-verify="required"
                                        required/>{{$db['process']}}</textarea>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label">街道结果:</label>
                        <div class="layui-input-block">
                            <textarea name="process" placeholder="请输入街道办理结果" class="layui-textarea"
                                      style="height: 160px;"
                                      lay-verType="tips" lay-verify="required"
                                      required/>{{$db['process']}}</textarea>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label">社区结果:</label>
                        <div class="layui-input-block">
                             <textarea name="process" placeholder="请输入社区办理结果" class="layui-textarea"
                                       style="height: 160px;"
                                       lay-verType="tips" lay-verify="required"
                                       required/>{{$db['process']}}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group-bottom text-right">
        <button type="reset" class="layui-btn layui-btn-primary">&emsp;重置&emsp;</button>
        <button class="layui-btn" lay-filter="edit" lay-submit>&emsp;提交&emsp;</button>
    </div>
    {{csrf_field()}}
</form>
<!--js逻辑-->
<script type="text/javascript">
    layui.use(["admin", "form", "okUtils", "okLayer", 'laydate'], function () {
        let admin = layui.admin;
        let form = layui.form;
        let okUtils = layui.okUtils;
        let okLayer = layui.okLayer;
        let laydate = layui.laydate;
        let $ = layui.jquery;
        form.val("filter", eval('(' + parent.json + ')'));
        admin.removeLoading();
        laydate.render({
            elem: '#approval_time',
            type: 'datetime',
            trigger: 'click'
        });
        form.on("submit(edit)", function (data) {
            okUtils.ajax("{{url('sys/pages/handlingManagement/feedback/'.$db['id'])}}", "{{$db['id']?'put':'post'}}", data.field, true).done(function (response) {
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
