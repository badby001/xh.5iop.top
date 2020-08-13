<?php

namespace App\Http\Controllers\Sys\Pages\System\XiaoQiang;

use App\Http\Controllers\Controller;
use App\Model\Pages\XQERPV3\LinePlanOrd;
use App\Model\Pages\XQERPV3\UserBase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class UserBaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('.sys.pages.xiaoqiang.userBase');
    }


    public function read(Request $request)
    {
        $inp = $request->all();
        //
        //
        $where =
            function ($query) use ($inp) {
                if (isset($inp['key'])) {
                    $query->where('trueName', 'like', '%' . $inp['key'] . '%')
                        ->orwhere('cpyName', 'like', '%' . $inp['key'] . '%')
                        ->orwhere('mobile', 'like', '%' . $inp['key'] . '%');
                }
                if (isset($inp['is_lock'])) {
                    $query->where('code', $inp['is_lock'] === 'o' ? '1' : '0');
                }
                if (isset($inp['dateType'])) {
                    if ($inp['dateType'] == 'addTime') {
                        if (isset($inp['start_time']) && isset($inp['end_time'])) {
                            $query->whereBetween('AddTime', [$inp['start_time'], $inp['end_time']]);
                        } else if (isset($inp['start_time'])) {
                            $query->where('AddTime', '>=', $inp['start_time']);
                        } else if (isset($inp['end_time'])) {
                            $query->where('AddTime', '<=', $inp['end_time']);
                        }
                    }
                }
            };
        $db = UserBase::where(['erpid' => 895, 'isDel' => 0, 'isLock' => 0, 'type' => 1])
            ->where($where)
            ->orderBy('AddTime', 'asc')
            ->paginate($inp['limit'])
            ->all();
        //生成redis缓存
        $redisArr = [];
        foreach ($db as $k => $v) {
            $redisArr['user_base:' . $v->ID] = json_encode($v);//redis不存在,获取数据库
        }
        Redis::mset($redisArr);//提交缓存
        //读取缓存
        $dbData = [];
        foreach ($redisArr as $k => $v) {
            $this_id = json_decode($v)->ID;//当前id
            $redisVal = json_decode(Redis::get('user_base:' . $this_id));
            $ordNumDb = LinePlanOrd::where(['erpid' => 895, 'isDel' => 0, 'userId' => $this_id])->whereIn('isOK', [0, 1])->count();
            $dbData[] = [
                'id' => $redisVal->ID,
                'PubUserID' => $redisVal->PubUserID,
                'Code' => $redisVal->Code,
                'trueName' => $redisVal->trueName,
                'ordNum' => $ordNumDb,
                'DeptName' => $redisVal->DeptName,
                'Job' => $redisVal->Job,
                'Mobile' => $redisVal->Mobile,
                'QQ' => $redisVal->QQ,
                'Detail' => $redisVal->Detail,
                'tel' => $redisVal->tel,
                'fax' => $redisVal->fax,
                'addr' => $redisVal->addr,
                'remark' => $redisVal->remark,
                'addTime' => $redisVal->addTime,
                'updateTime' => $redisVal->updateTime,
                'cpyName' => $redisVal->cpyName,
                'sex' => getSex($redisVal->sex == 0 ? 1 : 0),
                'weixin' => $redisVal->weixin,
                'email' => $redisVal->email,
                'website' => $redisVal->website,
                'tags' => $redisVal->tags,
                'sales_name' => $redisVal->sales_name,
                'addName' => $redisVal->admName,
                'ordOKTime' => $redisVal->ordOKTime,
                'is_leader' => $redisVal->is_leader,
            ];
        }
        //
        //总记录
        $total = UserBase::select(1)
            ->where(['erpid' => 895, 'isDel' => 0, 'isLock' => 0, 'type' => 1])
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
        $where['id'] = getInjoin($inp['id']);
        $apiWebRes = callWebApi($where, site()['callWebApi'] . 'api/regist/genUsers');//调用接口
        if ($apiWebRes->code == 200) {
            opLog('pub_logs', [['type' => '生成小助手账号', 'this_id' => 0, 'content' => json_encode($inp)]]);//记录日志
            return getSuccess(1);
        } else {
            return getSuccess($apiWebRes->msg);
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
