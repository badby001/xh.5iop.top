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
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label layui-form-required">事件编号:</label>
                        <div class="layui-input-block">
                            <input name="event_number" placeholder="请输入事件编号" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['event_number']}}" required/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label layui-form-required">记录时间:</label>
                        <div class="layui-input-block">
                            <input name="recording_time" placeholder="请输入记录时间" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['recording_time']}}" required/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label layui-form-required">诉求人:</label>
                        <div class="layui-input-block">
                            <input name="plaintiff" placeholder="请输入诉求人" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['plaintiff']}}" required/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label layui-form-required">诉求电话:</label>
                        <div class="layui-input-block">
                            <input name="appeal_telephone" placeholder="请输入诉求电话" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['appeal_telephone']}}" required/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label layui-form-required">联系电话:</label>
                        <div class="layui-input-block">
                            <input name="contact_number" placeholder="请输入联系电话" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['contact_number']}}" required/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label layui-form-required">事件来源:</label>
                        <div class="layui-input-block">
                            <input name="source_of_the_incident" placeholder="请输入事件来源" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['source_of_the_incident']}}" required/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label layui-form-required">事件类型:</label>
                        <div class="layui-input-block">
                            <input name="event_type" placeholder="请输入事件类型" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['event_type']}}" required/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label layui-form-required">事件标题:</label>
                        <div class="layui-input-block">
                            <input name="event_title" placeholder="请输入事件标题" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['event_title']}}" required/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label layui-form-required">事件大类:</label>
                        <div class="layui-input-block">
                            <input name="event_max_category" placeholder="请输入事件大类" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['event_max_category']}}" required/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label layui-form-required">事件小类:</label>
                        <div class="layui-input-block">
                            <input name="event_min_category" placeholder="请输入事件小类" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['event_min_category']}}" required/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label layui-form-required">诉求区域:</label>
                        <div class="layui-input-block">
                            <input name="appeal_area" placeholder="请输入诉求区域" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['appeal_area']}}" required/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label layui-form-required">事件地址:</label>
                        <div class="layui-input-block">
                            <input name="event_address" placeholder="请输入事件地址" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['event_address']}}" required/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label layui-form-required">记录人:</label>
                        <div class="layui-input-block">
                            <input name="recorded_by" placeholder="请输入记录人" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['recorded_by']}}" required/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label layui-form-required">事件状态:</label>
                        <div class="layui-input-block">
                            <input name="event_status" placeholder="请输入事件状态" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['event_status']}}" required/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label layui-form-required">紧急程度:</label>
                        <div class="layui-input-block">
                            <input name="emergency_degree" placeholder="请输入紧急程度" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['emergency_degree']}}" required/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label layui-form-required">审批状态:</label>
                        <div class="layui-input-block">
                            <input name="approval_status" placeholder="请输入审批状态" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['approval_status']}}" required/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md6">
                        <label class="layui-form-label layui-form-required">事件详情:</label>
                        <div class="layui-input-block">
                            <input name="details_of_the_incident" placeholder="请输入事件详情" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['details_of_the_incident']}}" required/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="layui-card">
            <div class="layui-card-header">原件处理信息</div>
            <div class="layui-card-body">
                <div class="layui-form-item layui-row">
                    <div class="layui-inline layui-col-md6">
                        <label class="layui-form-label layui-form-required">附件信息:</label>
                        <div class="layui-input-block">
                            <input name="attachment_information" placeholder="请输入附件信息" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['attachment_information']}}" required/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md6">
                        <label class="layui-form-label layui-form-required">关联数据:</label>
                        <div class="layui-input-block">
                            <input name="linked_data" placeholder="请输入关联数据" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['linked_data']}}" required/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md6">
                        <label class="layui-form-label layui-form-required">退回意见:</label>
                        <div class="layui-input-block">
                            <input name="return_comments" placeholder="请输入退回意见" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['return_comments']}}" required/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md6">
                        <label class="layui-form-label layui-form-required">督办意见:</label>
                        <div class="layui-input-block">
                            <input name="supervision_opinions" placeholder="请输入督办意见" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['supervision_opinions']}}" required/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md6">
                        <label class="layui-form-label layui-form-required">领导批示:</label>
                        <div class="layui-input-block">
                            <input name="leaders_instructions" placeholder="请输入领导批示" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['leaders_instructions']}}" required/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md6">
                        <label class="layui-form-label layui-form-required">是否公开:</label>
                        <div class="layui-input-block">
                            <input name="is_it_public" placeholder="请输入是否公开" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['is_it_public']}}" required/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="layui-card">
            <div class="layui-card-header">原件办理过程</div>
            <div class="layui-card-body">
                <div class="layui-form-item layui-row">
                    <script id="process" name="process" type="text/plain"
                            style="width:100%;height:360px;">{!! $db['process'] !!}</script>
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
@include('.sys.public.ueditor')
<script type="text/javascript">
    layui.use(["admin", "form", "okUtils", "okLayer"], function () {
        let admin = layui.admin;
        let form = layui.form;
        let okUtils = layui.okUtils;
        let okLayer = layui.okLayer;
        let $ = layui.jquery;
        UE.getEditor('process').focus();
        form.val("filter", eval('(' + parent.json + ')'));
        admin.removeLoading();

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
