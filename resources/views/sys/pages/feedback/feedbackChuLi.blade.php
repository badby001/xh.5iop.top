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
            <div class="layui-card-header">不满意办件复核反馈原件</div>
            <div class="layui-card-body">
                <div class="layui-form-item layui-row">
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label">办件编号:</label>
                        <div class="layui-input-block">
                            <input placeholder="请输入办件编号" class="layui-input" value="{{$db['event_number']}}" disabled/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label layui-form-required">派发时间:</label>
                        <div class="layui-input-block">
                            <input id="distribution_time" name="distribution_time" placeholder="请输入派发时间"
                                   class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['distribution_time']}}"
                                   required/>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item layui-row">
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label">来电市民:</label>
                        <div class="layui-input-block">
                            <input placeholder="请输入来电市民" class="layui-input" value="{{$db['plaintiff']}}" disabled/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label">来电日期:</label>
                        <div class="layui-input-block">
                            <input placeholder="请输入来电日期" class="layui-input" value="{{$db['recording_time']}}"
                                   disabled/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label">联系电话:</label>
                        <div class="layui-input-block">
                            <input placeholder="请输入联系电话" class="layui-input" value="{{$db['contact_number']}}"
                                   disabled/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md6">
                        <label class="layui-form-label">联系地址:</label>
                        <div class="layui-input-block">
                            <input placeholder="请输入联系地址" class="layui-input" value="{{$db['event_address']}}"
                                   disabled/>
                        </div>
                    </div>
                    <div class="layui-form-item layui-row">
                        <div class="layui-inline layui-col-md3">
                            <label class="layui-form-label">诉求内容:</label>
                            <div class="layui-input-block" style="width: 520px;">
                                 <textarea name="public_feedback" placeholder="请输入市民反馈说明" class="layui-textarea"
                                           lay-verType="tips" lay-verify="required"
                                           disabled/>{{$db['details_of_the_incident']}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-card">
            <div class="layui-card-header">不满意办件复核反馈结果</div>
            <div class="layui-card-body">
                <div class="layui-form-item layui-row">
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label layui-form-required">转办意见:</label>
                        <div class="layui-input-block" style="width: 520px;">
                            <textarea name="opinions_on_transfer" placeholder="请输入转办意见" class="layui-textarea"
                                      lay-verType="tips" lay-verify="required" style="width: 510px;"
                                      required/>{{$db['opinions_on_transfer']}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item layui-row">
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label layui-form-required">复核情况:</label>
                        <div class="layui-input-block" style="width: 520px;">
                            <textarea name="review" placeholder="请输入复核情况" class="layui-textarea"
                                      lay-verType="tips" lay-verify="required" style="width: 510px;"
                                      required/>{{$db['review']}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item layui-row">
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label layui-form-required">答复市民时间:</label>
                        <div class="layui-input-block">
                            <input id="reply_time" name="reply_time" placeholder="请输入答复市民时间" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['reply_time']}}" required/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md4">
                        <label class="layui-form-label layui-form-required">答复方式:</label>
                        <div class="layui-input-block">
                            <input type="radio" name="reply_mode" value="电话"
                                   title="电话" {{$db['reply_mode']==='电话'?'checked':''}}>
                            <input type="radio" name="reply_mode" value="书面"
                                   title="书面" {{$db['reply_mode']==='书面'?'checked':''}}>
                            <input type="radio" name="reply_mode" value="入户"
                                   title="入户" {{$db['reply_mode']==='入户'?'checked':''}}>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"><span class="red">*</span>答复附件</label>
                        <div class="layui-inline" style="width: 520px;">
                            <input type="text" id="reply_annex" name="reply_annex"
                                   lay-verify="required"
                                   value="{{$db['reply_annex']}}"
                                   autocomplete="off" class="layui-input" placeholder="请上传答复方式附件图片">
                        </div>
                        <div class="layui-inline">
                            <div class="layui-form-mid layui-word-aux">
                                <button type="button" class="layui-btn layui-btn-normal layui-btn-xs"
                                        id="reply_annex_img">
                                    <i class="layui-icon">&#xe67c;</i>上传图片
                                </button>
                            </div>
                        </div>
                        <div class="layui-input-block">
                            <label class="layui-form"><img class="layui-upload-img" id="reply_annex_show"
                                                           src="{{$db['reply_annex']}}"></label>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item layui-row">
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label layui-form-required">是否解决:</label>
                        <div class="layui-input-block">
                            <input type="radio" name="is_it_solved" value="实际解决"
                                   title="实际解决" {{$db['is_it_solved']==='实际解决'?'checked':''}}>
                            <input type="radio" name="is_it_solved" value="解释说明"
                                   title="解释说明" {{$db['is_it_solved']==='解释说明'?'checked':''}}>
                            <input type="radio" name="is_it_solved" value="参考备案"
                                   title="参考备案" {{$db['is_it_solved']==='参考备案'?'checked':''}}>
                            <input type="radio" name="is_it_solved" value="诉求过高"
                                   title="诉求过高" {{$db['is_it_solved']==='诉求过高'?'checked':''}}>
                            <input type="radio" name="is_it_solved" value="未解决"
                                   title="未解决" {{$db['is_it_solved']==='未解决'?'checked':''}}>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md6">
                        <label class="layui-form-label layui-form-required">办理态度是否满意:</label>
                        <div class="layui-input-block">
                            <input type="radio" name="is_the_handling_attitude_satisfactory" value="满意"
                                   title="满意" {{$db['is_the_handling_attitude_satisfactory']==='满意'?'checked':''}}>
                            <input type="radio" name="is_the_handling_attitude_satisfactory" value="不满意"
                                   title="不满意" {{$db['is_the_handling_attitude_satisfactory']==='不满意'?'checked':''}}>
                            <input type="radio" name="is_the_handling_attitude_satisfactory" value="认可"
                                   title="认可" {{$db['is_the_handling_attitude_satisfactory']==='认可'?'checked':''}}>
                            <input type="radio" name="is_the_handling_attitude_satisfactory" value="未评价"
                                   title="未评价" {{$db['is_the_handling_attitude_satisfactory']==='未评价'?'checked':''}}>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md6">
                        <label class="layui-form-label layui-form-required">办理结果是否满意:</label>
                        <div class="layui-input-block">
                            <input type="radio" name="is_the_result_satisfactory" value="满意"
                                   title="满意" {{$db['is_the_result_satisfactory']==='满意'?'checked':''}}>
                            <input type="radio" name="is_the_result_satisfactory" value="不满意"
                                   title="不满意" {{$db['is_the_result_satisfactory']==='不满意'?'checked':''}}>
                            <input type="radio" name="is_the_result_satisfactory" value="认可"
                                   title="认可" {{$db['is_the_result_satisfactory']==='认可'?'checked':''}}>
                            <input type="radio" name="is_the_result_satisfactory" value="未评价"
                                   title="未评价" {{$db['is_the_result_satisfactory']==='未评价'?'checked':''}}>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label layui-form-required">市民反馈说明:</label>
                        <div class="layui-input-block" style="width: 520px;">
                                <textarea name="public_feedback" placeholder="请输入市民反馈说明" class="layui-textarea"
                                          lay-verType="tips" lay-verify="required"
                                          required/>{{$db['public_feedback']}}</textarea>
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
    layui.use(["admin", "form", "okUtils", "okLayer", "upload", 'laydate'], function () {
        let admin = layui.admin;
        let form = layui.form;
        let okUtils = layui.okUtils;
        let okLayer = layui.okLayer;
        let upload = layui.upload;
        let laydate = layui.laydate;
        let $ = layui.jquery;
        form.val("filter", eval('(' + parent.json + ')'));
        admin.removeLoading();

        laydate.render({
            elem: '#distribution_time',
            type: 'datetime',
            trigger: 'click'
        });
        laydate.render({
            elem: '#reply_time',
            type: 'datetime',
            trigger: 'click'
        });


        upload.render({
            elem: '#reply_annex_img' //绑定元素
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
                    $('#reply_annex_show').attr('src', result); //图片链接（base64）
                });
            }
            , done: function (res) {
                //上传完毕回调
                okLayer.greenTickMsg('上传成功!');
                $("#reply_annex").val(res.file);
                layer.closeAll('loading'); //关闭loading
            }
            , error: function () {
                //请求异常回调
                layer.closeAll('loading'); //关闭loading
            }
        });


        form.on("submit(edit)", function (data) {
            okUtils.ajax("{{url('sys/pages/handlingManagement/feedbackChuLi/'.$db['id'])}}", "{{$db['id']?'post':'post'}}", data.field, true).done(function (response) {
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
