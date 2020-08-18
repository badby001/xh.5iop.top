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
                        <label class="layui-form-label">派发时间:</label>
                        <div class="layui-input-block">
                            <input id="distribution_time" name="distribution_time" placeholder="请输入派发时间"
                                   class="layui-input"
                                   lay-verType="tips" value="{{$db['distribution_time']}}"
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
                    <div class="layui-inline layui-col-md7">
                        <label class="layui-form-label">诉求内容:</label>
                        <div class="layui-input-block" style="width: 510px;">
                                 <textarea placeholder="请输入市民反馈说明" class="layui-textarea"
                                           disabled/>{{$db['details_of_the_incident']}}</textarea>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md7">
                        <label class="layui-form-label">转办意见:</label>
                        <div class="layui-input-block" style="width: 510px;">
                            <textarea placeholder="请输入转办意见" class="layui-textarea"
                                      disabled/>{{$db['opinions_on_transfer']}}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-card">
            <div class="layui-card-header">二次派件督办</div>
            <div class="layui-card-body">
                <div class="layui-form-item layui-row">
                    <div class="layui-inline layui-col-md7">
                        <label class="layui-form-label">复核情况:</label>
                        <div class="layui-input-block" style="width: 510px;">
                            <textarea name="review" placeholder="请输入复核情况" class="layui-textarea"
                                      lay-verType="tips" style="width: 510px;"
                                      required/>{{$db['review']}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item layui-row">
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label">答复市民时间:</label>
                        <div class="layui-input-block">
                            <input id="reply_time" name="reply_time" placeholder="请输入答复市民时间" class="layui-input"
                                   lay-verType="tips" value="{{$db['reply_time']}}" required/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label">答复方式:</label>
                        <div class="layui-input-block">
                            <div id="reply_mode"></div>
                            <input name="reply_mode" hidden value="{{$db['reply_mode_old']}}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">答复资料</label>
                        <div class="layui-inline">
                            <script id="reply_annex" name="reply_annex" type="text/plain"
                                    style="width:800px;height:360px;">{!! $db['reply_annex'] !!}</script>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item layui-row">
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label">是否解决:</label>
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
                        <label class="layui-form-label">办理态度是否满意:</label>
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
                        <label class="layui-form-label">办理结果是否满意:</label>
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
                    <div class="layui-inline layui-col-md7">
                        <label class="layui-form-label">市民反馈:</label>
                        <div class="layui-input-block" style="width: 510px;">
                                <textarea name="public_feedback" placeholder="请输入市民反馈" class="layui-textarea"
                                          lay-verType="tips"
                                          required/>{{$db['public_feedback']}}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-card">
            <div class="layui-card-header">三次派件督办</div>
            <div class="layui-card-body">
                <div class="layui-form-item layui-row">
                    <div class="layui-inline layui-col-md7">
                        <label class="layui-form-label">复核情况:</label>
                        <div class="layui-input-block">
                            <textarea name="review3" placeholder="请输入复核情况" class="layui-textarea"
                                      lay-verType="tips" style="width: 510px;"
                                      required/>{{$db['review3']}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item layui-row">
                    <div class="layui-inline layui-col-md6">
                        <label class="layui-form-label">多部门协调:</label>
                        <div class="layui-input-block">
                            <div id="multi_sector_coordination3"></div>
                            <input name="multi_sector_coordination3" hidden
                                   value="{{$db['multi_sector_coordination3_old']}}">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item layui-row">
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label">答复市民时间:</label>
                        <div class="layui-input-block">
                            <input id="reply_time3" name="reply_time3" placeholder="请输入答复市民时间" class="layui-input"
                                   lay-verType="tips" value="{{$db['reply_time3']}}" required/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label">答复方式:</label>
                        <div class="layui-input-block">
                            <div id="reply_mode3"></div>
                            <input name="reply_mode3" hidden value="{{$db['reply_mode3_old']}}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">答复资料</label>
                        <div class="layui-inline">
                            <script id="reply_annex3" name="reply_annex3" type="text/plain"
                                    style="width:800px;height:360px;">{!! $db['reply_annex3'] !!}</script>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item layui-row">
                    <div class="layui-inline layui-col-md3">
                        <label class="layui-form-label">是否解决:</label>
                        <div class="layui-input-block">
                            <input type="radio" name="is_it_solved3" value="实际解决"
                                   title="实际解决" {{$db['is_it_solved3']==='实际解决'?'checked':''}}>
                            <input type="radio" name="is_it_solved3" value="解释说明"
                                   title="解释说明" {{$db['is_it_solved3']==='解释说明'?'checked':''}}>
                            <input type="radio" name="is_it_solved3" value="参考备案"
                                   title="参考备案" {{$db['is_it_solved3']==='参考备案'?'checked':''}}>
                            <input type="radio" name="is_it_solved3" value="诉求过高"
                                   title="诉求过高" {{$db['is_it_solved3']==='诉求过高'?'checked':''}}>
                            <input type="radio" name="is_it_solved3" value="未解决"
                                   title="未解决" {{$db['is_it_solved3']==='未解决'?'checked':''}}>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md6">
                        <label class="layui-form-label">办理态度是否满意:</label>
                        <div class="layui-input-block">
                            <input type="radio" name="is_the_handling_attitude_satisfactory3" value="满意"
                                   title="满意" {{$db['is_the_handling_attitude_satisfactory3']==='满意'?'checked':''}}>
                            <input type="radio" name="is_the_handling_attitude_satisfactory3" value="不满意"
                                   title="不满意" {{$db['is_the_handling_attitude_satisfactory3']==='不满意'?'checked':''}}>
                            <input type="radio" name="is_the_handling_attitude_satisfactory3" value="认可"
                                   title="认可" {{$db['is_the_handling_attitude_satisfactory3']==='认可'?'checked':''}}>
                            <input type="radio" name="is_the_handling_attitude_satisfactory3" value="未评价"
                                   title="未评价" {{$db['is_the_handling_attitude_satisfactory3']==='未评价'?'checked':''}}>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md6">
                        <label class="layui-form-label">办理结果是否满意:</label>
                        <div class="layui-input-block">
                            <input type="radio" name="is_the_result_satisfactory3" value="满意"
                                   title="满意" {{$db['is_the_result_satisfactory3']==='满意'?'checked':''}}>
                            <input type="radio" name="is_the_result_satisfactory3" value="不满意"
                                   title="不满意" {{$db['is_the_result_satisfactory3']==='不满意'?'checked':''}}>
                            <input type="radio" name="is_the_result_satisfactory" value="认可"
                                   title="认可" {{$db['is_the_result_satisfactory3']==='认可'?'checked':''}}>
                            <input type="radio" name="is_the_result_satisfactory3" value="未评价"
                                   title="未评价" {{$db['is_the_result_satisfactory3']==='未评价'?'checked':''}}>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <div class="layui-inline layui-col-md7">
                        <label class="layui-form-label">市民反馈:</label>
                        <div class="layui-input-block" style="width: 510px;">
                                <textarea name="public_feedback3" placeholder="请输入市民反馈说明" class="layui-textarea"
                                          lay-verType="tips"
                                          required/>{{$db['public_feedback3']}}</textarea>
                        </div>
                    </div>
                </div>

                <div class="layui-form-item layui-form-text">
                    <div class="layui-inline layui-col-md7">
                        <label class="layui-form-label">处理意见:</label>
                        <div class="layui-input-block" style="width: 510px;">
                                <textarea name="handling_opinions3" placeholder="请输入处理意见" class="layui-textarea"
                                          lay-verType="tips"
                                          required/>{{$db['handling_opinions3']}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <div class="layui-inline layui-col-md7">
                        <label class="layui-form-label">负责人批示:</label>
                        <div class="layui-input-block" style="width: 510px;">
                                <textarea name="instructions_from_person_in_charge3" placeholder="请输负责人批示"
                                          class="layui-textarea"
                                          lay-verType="tips"
                                          required/>{{$db['instructions_from_person_in_charge3']}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <div class="layui-inline layui-col-md7">
                        <label class="layui-form-label">结案:</label>
                        <div class="layui-input-block" style="width: 510px;">
                                <textarea name="case_closed3" placeholder="请输入结案信息" class="layui-textarea"
                                          lay-verType="tips"
                                          required/>{{$db['case_closed3']}}</textarea>
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
@include('.sys.public.ueditor')
<script type="text/javascript">
    layui.use(["admin", "form", 'xmSelect', "okUtils", "okLayer", 'laydate'], function () {
        let admin = layui.admin;
        let form = layui.form;
        let okUtils = layui.okUtils;
        let okLayer = layui.okLayer;
        let xmSelect = layui.xmSelect;
        let laydate = layui.laydate;
        let $ = layui.jquery;
        form.val("filter", eval('(' + parent.json + ')'));
        UE.getEditor('reply_annex').focus();
        UE.getEditor('reply_annex3').focus();
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
        laydate.render({
            elem: '#reply_time3',
            type: 'datetime',
            trigger: 'click'
        });

        // 渲染多选下拉
        xmSelect.render({
            el: '#reply_mode',
            toolbar: {show: true},
            filterable: true,
            searchTips: '请输入关键字',
            tips: '请选择答复方式(可多选)',
            empty: '呀, 没有数据呢',
            initValue: {!! $db['reply_mode'] !!},
            on: function (data) {
                let dateList = data.arr.map(function (item) {
                    return item['value'];
                });
                $('input[name="reply_mode"]').val(dateList);
            },
            data: [
                {name: '电话', value: '电话'},
                {name: '书面', value: '书面'},
                {name: '入户', value: '入户'}
            ]
        });

        // 渲染多选下拉
        xmSelect.render({
            el: '#reply_mode3',
            toolbar: {show: true},
            filterable: true,
            searchTips: '请输入关键字',
            tips: '请选择答复方式(可多选)',
            empty: '呀, 没有数据呢',
            initValue: {!! $db['reply_mode3'] !!},
            on: function (data) {
                let dateList = data.arr.map(function (item) {
                    return item['value'];
                });
                $('input[name="reply_mode3"]').val(dateList);
            },
            data: [
                {name: '电话', value: '电话'},
                {name: '书面', value: '书面'},
                {name: '入户', value: '入户'}
            ]
        });

        // 渲染多选下拉
        xmSelect.render({
            el: '#multi_sector_coordination3',
            toolbar: {show: true},
            filterable: true,
            searchTips: '请输入关键字',
            tips: '请选择多部门协调(可多选)',
            empty: '呀, 没有数据呢',
            initValue: {!! $db['multi_sector_coordination3'] !!},
            on: function (data) {
                let dateList = data.arr.map(function (item) {
                    return item['value'];
                });
                $('input[name="multi_sector_coordination3"]').val(dateList);
            },
            data: [
                {name: '市场监督管理局', value: '市场监督管理局'},
                {name: '水务局', value: '水务局'},
                {name: '城市管理局', value: '城市管理局'},
                {name: '环卫服务中心', value: '环卫服务中心'},
                {name: '生态环境局', value: '生态环境局'},
                {name: '公安分局', value: '公安分局'},
                {name: '教育局', value: '教育局'},
                {name: '住建局', value: '住建局'},
                {name: '西湖街道纪工委', value: '西湖街道纪工委'},
                {name: '物业管理办公室', value: '物业管理办公室'}
            ]
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
