<?php

namespace App\Http\Controllers\Sys\Pages\Feedback;

use App\Http\Controllers\Controller;
use App\model\pages\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('.sys.pages.feedback.index');
    }


    //列表
    public function read(Request $request)
    {
        $inp = $request->all();

        $where =
            function ($query) use ($inp) {
                if (isset($inp['is_lock'])) {
                    $query->where('is_lock', $inp['is_lock'] == "n" ? 1 : 0);
                }
                if (isset($inp['key'])) {
                    $query->where('title', 'like', '%' . $inp['key'] . '%');
                }
                if (isset($inp['dateType'])) {
                    if ($inp['dateType'] == 'addTime') {
                        if (isset($inp['start_time']) && isset($inp['end_time'])) {
                            $query->whereBetween('add_time', [$inp['start_time'], $inp['end_time']]);
                        } else if (isset($inp['start_time'])) {
                            $query->where('add_time', '>=', $inp['start_time']);
                        } else if (isset($inp['end_time'])) {
                            $query->where('add_time', '<=', $inp['end_time']);
                        }
                    }
                }
            };

        $db = Feedback::where('is_del', 0)
            ->where($where)
            ->orderBy('is_lock', 'asc')
            ->orderBy('by_sort', 'desc')
            ->orderBy('add_time', 'asc')
            ->paginate($inp['limit'])
            ->all();
        //生成redis缓存
        $redisArr = [];
        foreach ($db as $k => $v) {
            $redisArr['feedback:' . $v->id] = json_encode($v);//redis不存在,获取数据库
        }
        Redis::mset($redisArr);//提交缓存
        //读取缓存
        $dbData = [];
        foreach ($redisArr as $k => $v) {
            $this_id = json_decode($v)->id;//当前id
            $redisVal = json_decode(Redis::get('feedback:' . $this_id));//读取缓存
            $dbData[] = [
                "id" => $redisVal->id,// 21904,//发票id
                "code" => $redisVal->code,
                "add_code" => $redisVal->add_code,
                "add_time" => $redisVal->add_time,
                "event_number" => $redisVal->event_number,
                "recording_time" => $redisVal->recording_time,
                "plaintiff" => $redisVal->plaintiff,
                "appeal_telephone" => $redisVal->appeal_telephone,
                "contact_number" => $redisVal->contact_number,
                "source_of_the_incident" => $redisVal->source_of_the_incident,
                "event_type" => $redisVal->event_type,
                "event_title" => $redisVal->event_title,
                "event_max_category" => $redisVal->event_max_category,
                "event_min_category" => $redisVal->event_min_category,
                "appeal_area" => $redisVal->appeal_area,
                "event_address" => $redisVal->event_address,
                "recorded_by" => $redisVal->recorded_by,
                "event_status" => $redisVal->event_status,
                "emergency_degree" => $redisVal->emergency_degree,
                "approval_status" => $redisVal->approval_status,
                "details_of_the_incident" => $redisVal->details_of_the_incident,
                "attachment_information" => $redisVal->attachment_information,
                "linked_data" => $redisVal->linked_data,
                "return_comments" => $redisVal->return_comments,
                "supervision_opinions" => $redisVal->supervision_opinions,
                "leaders_instructions" => $redisVal->leaders_instructions,
                "is_it_public" => $redisVal->is_it_public,
                "process" => $redisVal->process,
            ];
        }
        //总记录
        $total = Feedback::select(1)
            ->where('is_del', 0)
            ->where($where)
            ->count();
        $data = [];
        $data['code'] = 0;
        $data['msg'] = '查询成功';
        $data['data'] = $dbData;
        $data['count'] = $total;
        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $data = [];
        $data['id'] = '';
        $data['event_number'] = '';
        $data['recording_time'] = '';
        $data['plaintiff'] = '';
        $data['appeal_telephone'] = '';
        $data['contact_number'] = '';
        $data['source_of_the_incident'] = '';
        $data['event_type'] = '';
        $data['event_title'] = '';
        $data['event_max_category'] = '';
        $data['event_min_category'] = '';
        $data['appeal_area'] = '';
        $data['event_address'] = '';
        $data['recorded_by'] = '';
        $data['event_status'] = '';
        $data['emergency_degree'] = '';
        $data['approval_status'] = '';
        $data['details_of_the_incident'] = '';
        $data['attachment_information'] = '';
        $data['linked_data'] = '';
        $data['return_comments'] = '';
        $data['supervision_opinions'] = '';
        $data['leaders_instructions'] = '';
        $data['is_it_public'] = '';
        $data['process'] = '';
        return view('.sys.pages.feedback.edit', ['db' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $inp = $request->all();
        $data = new Feedback();
        $data['code'] = getNewId();
        $data['add_code'] = _admCode();
        $data['add_time'] = getTime(1);
        $data['event_number'] = $inp['event_number'];
        $data['recording_time'] = $inp['recording_time'];
        $data['plaintiff'] = $inp['plaintiff'];
        $data['appeal_telephone'] = $inp['appeal_telephone'];
        $data['contact_number'] = $inp['contact_number'];
        $data['source_of_the_incident'] = $inp['source_of_the_incident'];
        $data['event_type'] = $inp['event_type'];
        $data['event_title'] = $inp['event_title'];
        $data['event_max_category'] = $inp['event_max_category'];
        $data['event_min_category'] = $inp['event_min_category'];
        $data['appeal_area'] = $inp['appeal_area'];
        $data['event_address'] = $inp['event_address'];
        $data['recorded_by'] = $inp['recorded_by'];
        $data['event_status'] = $inp['event_status'];
        $data['emergency_degree'] = $inp['emergency_degree'];
        $data['approval_status'] = $inp['approval_status'];
        $data['details_of_the_incident'] = $inp['details_of_the_incident'];
        $data['attachment_information'] = $inp['attachment_information'];
        $data['linked_data'] = $inp['linked_data'];
        $data['return_comments'] = $inp['return_comments'];
        $data['supervision_opinions'] = $inp['supervision_opinions'];
        $data['leaders_instructions'] = $inp['leaders_instructions'];
        $data['is_it_public'] = $inp['is_it_public'];
        $data['process'] = $inp['process'] ?? '';
        if ($data->save()) {
            //生成redis缓存
            $redisArr['feedback:' . $data->id] = json_encode($data);
            Redis::mset($redisArr);//提交缓存
            //opLog('feedback', [['type' => '添加', 'this_id' => $data->id, 'content' => json_encode($inp)]]);//记录日志
            return getSuccess(1);
        } else {
            return getSuccess(2);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $redisVal = json_decode(Redis::get('line_plan_ordInfo:' . $id));//读取缓存
        $pingAmount = $redisVal->pingAmount;//已付款金额
        $dbData = [];
        $dbData['id'] = '';
        $dbData['orderId'] = $id;
        $dbData['pingAmount'] = $pingAmount;
        $dbData['cpyName'] = _admName();
        $dbData['invoice'] = '旅游服务费';
        $dbData['taxpayerIdentificationNumber'] = '';
        return view('sys.pages.order.invoiceEdit', ['db' => $dbData]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $redisVal = json_decode(Redis::get('feedback:' . $id));//读取缓存
        $dbData[] = [
            "id" => $redisVal->id,// 21904,//发票id
            "code" => $redisVal->code,
            "add_code" => $redisVal->add_code,
            "add_time" => $redisVal->add_time,
            "event_number" => $redisVal->event_number,
            "recording_time" => $redisVal->recording_time,
            "plaintiff" => $redisVal->plaintiff,
            "appeal_telephone" => $redisVal->appeal_telephone,
            "contact_number" => $redisVal->contact_number,
            "source_of_the_incident" => $redisVal->source_of_the_incident,
            "event_type" => $redisVal->event_type,
            "event_title" => $redisVal->event_title,
            "event_max_category" => $redisVal->event_max_category,
            "event_min_category" => $redisVal->event_min_category,
            "appeal_area" => $redisVal->appeal_area,
            "event_address" => $redisVal->event_address,
            "recorded_by" => $redisVal->recorded_by,
            "event_status" => $redisVal->event_status,
            "emergency_degree" => $redisVal->emergency_degree,
            "approval_status" => $redisVal->approval_status,
            "details_of_the_incident" => $redisVal->details_of_the_incident,
            "attachment_information" => $redisVal->attachment_information,
            "linked_data" => $redisVal->linked_data,
            "return_comments" => $redisVal->return_comments,
            "supervision_opinions" => $redisVal->supervision_opinions,
            "leaders_instructions" => $redisVal->leaders_instructions,
            "is_it_public" => $redisVal->is_it_public,
            "process" => $redisVal->process,
        ];
//         return $dbData[0];
        return view('.sys.pages.feedback.edit', ['db' => $dbData[0]]);


    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $inp = $request->all();
        $data = Feedback::find($id);
        $data['event_number'] = $inp['event_number'];
        $data['recording_time'] = $inp['recording_time'];
        $data['plaintiff'] = $inp['plaintiff'];
        $data['appeal_telephone'] = $inp['appeal_telephone'];
        $data['contact_number'] = $inp['contact_number'];
        $data['source_of_the_incident'] = $inp['source_of_the_incident'];
        $data['event_type'] = $inp['event_type'];
        $data['event_title'] = $inp['event_title'];
        $data['event_max_category'] = $inp['event_max_category'];
        $data['event_min_category'] = $inp['event_min_category'];
        $data['appeal_area'] = $inp['appeal_area'];
        $data['event_address'] = $inp['event_address'];
        $data['recorded_by'] = $inp['recorded_by'];
        $data['event_status'] = $inp['event_status'];
        $data['emergency_degree'] = $inp['emergency_degree'];
        $data['approval_status'] = $inp['approval_status'];
        $data['details_of_the_incident'] = $inp['details_of_the_incident'];
        $data['attachment_information'] = $inp['attachment_information'];
        $data['linked_data'] = $inp['linked_data'];
        $data['return_comments'] = $inp['return_comments'];
        $data['supervision_opinions'] = $inp['supervision_opinions'];
        $data['leaders_instructions'] = $inp['leaders_instructions'];
        $data['is_it_public'] = $inp['is_it_public'];
        $data['process'] = $inp['process'] ?? '';
        $data['up_code'] = _admCode();
        $data['up_time'] = getTime(1);
        if ($data->save()) {
            //生成redis缓存
            $redisArr['feedback:' . $data->id] = json_encode($data);
            Redis::mset($redisArr);//提交缓存
            //opLog('feedback', [['type' => '添加', 'this_id' => $data->id, 'content' => json_encode($inp)]]);//记录日志
            return getSuccess(1);
        } else {
            return getSuccess(2);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
