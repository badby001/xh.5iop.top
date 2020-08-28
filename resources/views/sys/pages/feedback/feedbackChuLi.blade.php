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
    <div class="layui-fluid" style="padding-bottom: 25px;">
        <div class="layui-card">
            <div class="layui-card-header">不满意办件复核反馈原件</div>
            <div class="layui-card-body">
                <div class="layui-form-item layui-row">
                    <div class="layui-inline layui-col-md4">
                        <label class="layui-form-label">事件编号:</label>
                        <div class="layui-input-block">
                            <input name="event_number" placeholder="请输入事件编号" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['event_number']}}" disabled/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md4">
                        <label class="layui-form-label">记录时间:</label>
                        <div class="layui-input-block">
                            <input name="recording_time" placeholder="请输入记录时间" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['recording_time']}}" disabled/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md4">
                        <label class="layui-form-label">诉求人:</label>
                        <div class="layui-input-block">
                            <input name="plaintiff" placeholder="请输入诉求人" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['plaintiff']}}" disabled/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md4">
                        <label class="layui-form-label">联系电话:</label>
                        <div class="layui-input-block">
                            <input name="contact_number" placeholder="请输入联系电话" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['contact_number']}}" disabled/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md4">
                        <label class="layui-form-label">事件类型:</label>
                        <div class="layui-input-block">
                            <input name="event_type" placeholder="请输入事件类型" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['event_type']}}" disabled/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md4">
                        <label class="layui-form-label">事件标题:</label>
                        <div class="layui-input-block">
                            <input name="event_title" placeholder="请输入事件标题" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['event_title']}}" disabled/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md4">
                        <label class="layui-form-label">事件大类:</label>
                        <div class="layui-input-block">
                            <input name="event_max_category" placeholder="请输入事件大类" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['event_max_category']}}"
                                   disabled/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md4">
                        <label class="layui-form-label">事件地址:</label>
                        <div class="layui-input-block">
                            <input name="event_address" placeholder="请输入事件地址" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['event_address']}}" disabled/>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md4">
                        <label class="layui-form-label">紧急程度:</label>
                        <div class="layui-input-block">
                            <input name="emergency_degree" placeholder="请输入紧急程度" class="layui-input"
                                   lay-verType="tips" lay-verify="required" value="{{$db['emergency_degree']}}"
                                   disabled/>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">事件详情:</label>
                        <div class="layui-input-block" style="width: 710px;">
                                 <textarea name="details_of_the_incident" placeholder="请输入事件详情" class="layui-textarea"
                                           lay-verType="tips"
                                           disabled/>{{$db['details_of_the_incident']}}</textarea>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">转办意见:</label>
                        <div class="layui-input-block" style="width: 710px;">
                            <textarea name="opinions_on_transfer" placeholder="请输入转办意见" class="layui-textarea"
                                      lay-verType="tips"
                                      disabled/>{{$db['opinions_on_transfer']}}</textarea>
                        </div>
                    </div>
                    <div class="layui-inline layui-col-md7">
                        <label class="layui-form-label">领导批示:</label>
                        <div class="layui-inline layui-col-md2">
                            <div class="layui-input-inline" style="width: 110px;">
                                <select name="approval_name" lay-verType="tips" disabled>
                                    <option value="">领导姓名</option>
                                    <option value="1">书记</option>
                                    <option value="2">副书记</option>
                                </select>
                            </div>
                        </div>
                        <div class="layui-inline layui-col-md6">
                            <div class="layui-input-inline" style="width: 350px;">
                                <input name="approval_proposal" placeholder="请输入批示建议" class="layui-input"
                                       lay-verType="tips" value="{{$db['event_address']}}"
                                       disabled/>
                            </div>
                        </div>
                        <div class="layui-inline layui-col-md2">
                            <div class="layui-input-inline" style="width: 232px;">
                                <input id="approval_time" name="approval_time" placeholder="请输入批示时间" class="layui-input"
                                       lay-verType="tips" value="{{$db['event_address']}}"
                                       disabled/>
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item layui-row">
                        <div class="layui-inline layui-col-md3">
                            <label class="layui-form-label">市级结果:</label>
                            <div class="layui-input-block">
                             <textarea name="process" placeholder="请输入市级办理结果" class="layui-textarea"
                                       style="height: 160px;"
                                       lay-verType="tips"
                                       disabled/>{{$db['process']}}</textarea>
                            </div>
                        </div>
                        <div class="layui-inline layui-col-md3">
                            <label class="layui-form-label">区级结果:</label>
                            <div class="layui-input-block">
                              <textarea name="process" placeholder="请输入区级办理结果" class="layui-textarea"
                                        style="height: 160px;"
                                        lay-verType="tips"
                                        disabled/>{{$db['process']}}</textarea>
                            </div>
                        </div>
                        <div class="layui-inline layui-col-md3">
                            <label class="layui-form-label">街道结果:</label>
                            <div class="layui-input-block">
                            <textarea name="process" placeholder="请输入街道办理结果" class="layui-textarea"
                                      style="height: 160px;"
                                      lay-verType="tips"
                                      disabled/>{{$db['process']}}</textarea>
                            </div>
                        </div>
                        <div class="layui-inline layui-col-md3">
                            <label class="layui-form-label">社区结果:</label>
                            <div class="layui-input-block">
                             <textarea name="process" placeholder="请输入社区办理结果" class="layui-textarea"
                                       style="height: 160px;"
                                       lay-verType="tips"
                                       disabled/>{{$db['process']}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="layui-card">
        <div class="layui-card-header">二次派件督办</div>
        <div class="layui-card-body">
            <div class="layui-form-item layui-row">
                <div class="layui-inline layui-col-md6">
                    <label class="layui-form-label">部门协商<br>办理单位</label>
                    <div class="layui-input-block">
                        <div id="department_consultation_unit"></div>
                        <input name="department_consultation_unit" hidden
                               value="{{$db['department_consultation_unit_old']}}">
                    </div>
                </div>
            </div>
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
                    <label class="layui-form-label"><span class="red">*</span>答复资料1</label>
                    <div class="layui-inline" style="width: 520px;">
                        <input type="text" id="reply_annex1" name="reply_annex1"
                               lay-verify="required"
                               value="{{$db['reply_annex']}}"
                               autocomplete="off" class="layui-input" placeholder="请上传默认图片">
                    </div>
                    <div class="layui-inline">
                        <div class="layui-form-mid layui-word-aux">
                            <button type="button" class="layui-btn layui-btn-normal layui-btn-xs"
                                    id="reply_annex_img1">
                                <i class="layui-icon">&#xe67c;</i>上传图片
                            </button>
                        </div>
                    </div>
                    <div class="layui-input-block">
                        <label class="layui-form"><img class="layui-upload-img" id="reply_annex_show1"
                                                       src="{{$db['reply_annex']}}"></label>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span class="red">*</span>答复资料2</label>
                    <div class="layui-inline" style="width: 520px;">
                        <input type="text" id="reply_annex2" name="reply_annex2"
                               lay-verify="required"
                               value="{{$db['reply_annex']}}"
                               autocomplete="off" class="layui-input" placeholder="请上传默认图片">
                    </div>
                    <div class="layui-inline">
                        <div class="layui-form-mid layui-word-aux">
                            <button type="button" class="layui-btn layui-btn-normal layui-btn-xs"
                                    id="reply_annex_img2">
                                <i class="layui-icon">&#xe67c;</i>上传图片
                            </button>
                        </div>
                    </div>
                    <div class="layui-input-block">
                        <label class="layui-form"><img class="layui-upload-img" id="reply_annex_show2"
                                                       src="{{$db['reply_annex']}}"></label>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span class="red">*</span>答复资料3</label>
                    <div class="layui-inline" style="width: 520px;">
                        <input type="text" id="reply_annex3" name="reply_annex3"
                               lay-verify="required"
                               value="{{$db['reply_annex']}}"
                               autocomplete="off" class="layui-input" placeholder="请上传默认图片">
                    </div>
                    <div class="layui-inline">
                        <div class="layui-form-mid layui-word-aux">
                            <button type="button" class="layui-btn layui-btn-normal layui-btn-xs"
                                    id="reply_annex_img3">
                                <i class="layui-icon">&#xe67c;</i>上传图片
                            </button>
                        </div>
                    </div>
                    <div class="layui-input-block">
                        <label class="layui-form"><img class="layui-upload-img" id="reply_annex_show3"
                                                       src="{{$db['reply_annex']}}"></label>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span class="red">*</span>答复资料4</label>
                    <div class="layui-inline" style="width: 520px;">
                        <input type="text" id="reply_annex4" name="reply_annex4"
                               lay-verify="required"
                               value="{{$db['reply_annex']}}"
                               autocomplete="off" class="layui-input" placeholder="请上传默认图片">
                    </div>
                    <div class="layui-inline">
                        <div class="layui-form-mid layui-word-aux">
                            <button type="button" class="layui-btn layui-btn-normal layui-btn-xs"
                                    id="reply_annex_img4">
                                <i class="layui-icon">&#xe67c;</i>上传图片
                            </button>
                        </div>
                    </div>
                    <div class="layui-input-block">
                        <label class="layui-form"><img class="layui-upload-img" id="reply_annex_show4"
                                                       src="{{$db['reply_annex']}}"></label>
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
                <div class="layui-inline layui-col-md6">
                    <div class="layui-input-block">
                        <input type="radio" name="is_the_handling_attitude_satisfactory" value="三次转办"
                               title="三次转办" {{$db['is_the_handling_attitude_satisfactory']==='三次转办'?'checked':''}}>
                        <input type="radio" name="is_the_handling_attitude_satisfactory" value="结案"
                               title="结案" {{$db['is_the_handling_attitude_satisfactory']==='结案'?'checked':''}}>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="layui-card">
        <div class="layui-card-header">三次派件督办</div>
        <div class="layui-card-body">
            <div class="layui-form-item layui-row">
                <div class="layui-inline layui-col-md6">
                    <label class="layui-form-label">部门协商<br>办理单位</label>
                    <div class="layui-input-block">
                        <div id="department_consultation_unit"></div>
                        <input name="department_consultation_unit" hidden
                               value="{{$db['department_consultation_unit_old']}}">
                    </div>
                </div>
            </div>
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
                <div class="layui-inline layui-col-md6">
                    <div class="layui-input-block">
                        <input type="radio" name="is_the_handling_attitude_satisfactory" value="三次转办"
                               title="三次转办" {{$db['is_the_handling_attitude_satisfactory']==='三次转办'?'checked':''}}>
                        <input type="radio" name="is_the_handling_attitude_satisfactory" value="结案"
                               title="结案" {{$db['is_the_handling_attitude_satisfactory']==='结案'?'checked':''}}>
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
    layui.use(["admin", "form", 'xmSelect', "okUtils", "okLayer", "upload", 'laydate'], function () {
        let admin = layui.admin;
        let form = layui.form;
        let okUtils = layui.okUtils;
        let okLayer = layui.okLayer;
        let xmSelect = layui.xmSelect;
        let laydate = layui.laydate;
        let upload = layui.upload;
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
            direction: 'up',
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
            el: '#department_consultation_unit',
            toolbar: {show: true},
            filterable: true,
            searchTips: '请输入关键字',
            tips: '请选择答复方式(可多选)',
            empty: '呀, 没有数据呢',
            initValue: {!! $db['department_consultation_unit'] !!},
            direction: 'up',
            on: function (data) {
                let dateList = data.arr.map(function (item) {
                    return item['value'];
                });
                $('input[name="department_consultation_unit"]').val(dateList);
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


        // 渲染多选下拉
        xmSelect.render({
            el: '#multi_sector_coordination3',
            toolbar: {show: true},
            filterable: true,
            searchTips: '请输入关键字',
            tips: '请选择多部门协调(可多选)',
            empty: '呀, 没有数据呢',
            initValue: {!! $db['multi_sector_coordination3'] !!},
            direction: 'up',
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

        upload.render({
            elem: '#reply_annex_img1' //绑定元素
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
                    $('#reply_annex_show1').attr('src', result); //图片链接（base64）
                });
            }
            , done: function (res) {
                //上传完毕回调
                okLayer.greenTickMsg('上传成功!');
                $("#reply_annex1").val(res.file);
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
