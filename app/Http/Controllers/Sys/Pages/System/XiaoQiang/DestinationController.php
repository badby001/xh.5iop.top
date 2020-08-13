<?php

namespace App\Http\Controllers\Sys\Pages\System\XiaoQiang;

use App\Http\Controllers\Controller;
use App\Model\Pages\System\Destination;
use App\Model\Pages\System\LineCityDestination;
use App\Model\Pages\XQERPV3\BaseLine;
use App\Model\Pages\XQERPV3\LinePlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class DestinationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('.sys.pages.xiaoqiang.destination');
    }


    public function read(Request $request)
    {
        $inp = $request->all();

        $db = Destination::select('id', 'father_id', 'name', 'is_lock', 'by_sort', 'is_senic', 'top_sort', 'img_url')
            ->where('is_del', 0)
            ->orderBy('is_lock', 'asc')
            ->orderBy('by_sort', 'desc')
            ->orderBy('add_time', 'asc')
            ->get();
        //生成redis缓存
        $redisArr = [];
        foreach ($db as $k => $v) {
            $redisArr['pub_destination:' . $v->id] = json_encode($v);//redis不存在,获取数据库
        }
        Redis::mset($redisArr);//提交缓存
        //读取缓存
        $dbData = [];
        foreach ($redisArr as $k => $v) {
            $this_id = json_decode($v)->id;//当前id
            $redisVal = json_decode(Redis::get('pub_destination:' . $this_id));
            $dbData[] = [
                'id' => $redisVal->id,
                'father_id' => $redisVal->father_id,
                'label' => $redisVal->name,
            ];
        }
        $data = [];
        $children = [];
        $children['label'] = '目的地目录';
        $children['id'] = 0;
        $children['spread'] = true;
        $children['children'] = createTree(arrayToObject($dbData));;
        $data['code'] = 0;
        $data['msg'] = '查询成功';
        $data['data'] = [$children];
        return $data;

    }


    //拖动
    public function target(Request $request)
    {
        $inp = $request->all();
        //首先获取到当前要拖动的节点
        $current_id = $inp['current']['id'];
        //获取到目标节点id
        $target_id = $inp['target'];
        //进行位置移动
        $db = Destination::find($current_id);
        $db['father_id'] = $target_id;
        $db->save();
        opLog('pub_destination', [['type' => '拖动', 'this_id' => $current_id, 'content' => json_encode($inp)]]);//记录日志
        return getSuccess(1);
    }

    //排序
    public function sort(Request $request)
    {
        $inp = $request->all();
        $i = count($inp['sort']);
        foreach ($inp['sort'] as $k => $v) {
            Destination::where('id', $v['id'])->update(['by_sort' => $i]);
            $i--;
        }
        return getSuccess(1);
    }


    public function lineRead(Request $request)
    {
        $inp = $request->all();
        //
        $where =
            function ($query) use ($inp) {
                if (isset($inp['is_lock'])) {
                    $query->where('is_lock', $inp['is_lock'] == "n" ? 1 : 0);
                }
                if (isset($inp['key'])) {
                    //根据title 获取到线路的line_id
                    $db = BaseLine::where(['erpid' => 895, 'isDel' => 0, 'isLock' => 0])
                        ->select('id')
                        ->where('title', 'like', '%' . $inp['key'] . '%')
                        ->get();
                    $line_id = '';
                    foreach ($db as $k => $v) {
                        $line_id = $v['id'] . ',' . $line_id;
                    }
                    $query->whereIn('line_id', getInjoin($line_id));
                }
                if (isset($inp['dateType'])) {
                    if ($inp['dateType'] == 'addTime') {
                        if (isset($inp['start_time']) && isset($inp['end_time'])) {
                            $query->whereBetween('Add_Time', [$inp['start_time'], $inp['end_time']]);
                        } else if (isset($inp['start_time'])) {
                            $query->where('Add_Time', '>=', $inp['start_time']);
                        } else if (isset($inp['end_time'])) {
                            $query->where('Add_Time', '<=', $inp['end_time']);
                        }
                    }
                }
            };
        //
        //接收到目录id后 通过目录id获取到与其所有关联的子项
        $d = Destination::where('is_del', 0)->get();
        $destination_id = zSonId($inp['destination_id'], $d) . ',' . $inp['destination_id'];
        //将导航中已经绑定过的线路获取出来,进行排除
        $LineCityDestinationDB = LineCityDestination::where(['type' => 1])
            ->whereIn('to_id', getInjoin($destination_id))
            ->where($where)
            ->orderBy('is_lock', 'asc')
            ->orderBy('by_sort', 'desc')
            ->orderBy('add_time', 'asc')
            ->paginate($inp['limit'])
            ->all();
        //读取缓存
        $dbData = [];
        foreach ($LineCityDestinationDB as $k => $v) {
            $this_id = $v['id'];//当前id
            $baseLineRedisVal = json_decode(Redis::get('base_line:' . $v['line_id']));
            $pubDepartureCityRedisVal = json_decode(Redis::get('pub_destination:' . $v['to_id']));
            //团期数量
            $baseLineDB = LinePlan::where(['erpid' => 895, 'isDel' => 0, 'lineId' => $v['line_id']])->where('PlanDate', '>=', getTime(2))->count();
            $dbData[] = [
                'line_id' => $baseLineRedisVal->ID,
                'id' => $this_id,
                'line_title' => $baseLineRedisVal->Title,//线路名称
                'destination_name' => $pubDepartureCityRedisVal->name,//目的地名称
                'planCount' => $baseLineDB,//团期数量
                'by_sort' => $v->by_sort,
                'is_lock_name' => getIsLock($v->is_lock),//状态
                'is_lock' => $v->is_lock,//状态
                'add_name' => getAdmName($v->add_code),
                'add_time' => $v->add_time,
                'up_name' => getAdmName($v->up_code),
                'up_time' => $v->up_time,
            ];
        }
        //
        //总记录
        $total = LineCityDestination::select(1)
            ->where(['type' => 0])
            ->whereIn('to_id', getInjoin($destination_id))
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
        $this_father_id = $inp['this_father_id'] ?? $inp['father_id'];
        if ($this_father_id == 0) {
            $father_id = 0;
        } else {
            $father_id = $inp['father_id'];
        }
        //根据父id获取到树路径
//        $fdb = Destination::where('id', $father_id)->select('layer_path')->first();
//        $layer_path = $fdb['layer_path'];
        $db = new Destination();
        $db['name'] = $inp['title'];
//        $db['layer_path'] = $layer_path == 0 ? '' . $father_id : $layer_path . ',' . $father_id;
        $db['father_id'] = $father_id;
        $db['add_code'] = _admCode();
        $db['add_time'] = getTime(1);
        if ($db->save()) {
            //生成redis缓存
            $redisArr['pub_destination:' . $db->id] = json_encode($db);
            Redis::mset($redisArr);//提交缓存
            $res = [];
            $res['id'] = $db['id'];
            $res['title'] = $inp['title'];
            $res['code'] = 0;
            $res['msg'] = '操作成功!';
            //
            opLog('pub_destination', [['type' => '添加', 'this_id' => $db->id, 'content' => json_encode($inp)]]);//记录日志
            return $res;
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
        $db = Destination::where('id', $id)->first();
        return View('.sys.pages.xiaoqiang.destinationEdit', ['db' => $db]);
    }

    public function nodeEdit(Request $request, $id)
    {
        //
        $inp = $request->all();
        if ($id == 0) {
            return getSuccess('当前目录名称不允许被修改哦');
        }
        $db = Destination::find($id);
        $db['name'] = $inp['title'];
        $db['up_code'] = _admCode();
        $db['up_time'] = getTime(1);
        $db->save();
        //生成redis缓存
        $redisArr['pub_destination:' . $db->id] = json_encode($db);
        Redis::mset($redisArr);//提交缓存
        opLog('pub_destination', [['type' => '修改', 'this_id' => $id, 'content' => json_encode($inp)]]);//记录日志
        return getSuccess(1);
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
        $db = Destination::find($id);
        //
        $db['name'] = $inp['name'];
        $db['is_lock'] = $inp['is_lock'] == "0" ? 0 : 1;
        $db['by_sort'] = $inp['by_sort'];
        $db['up_code'] = _admCode();
        $db['up_time'] = getTime(1);
        $db->save();
        //生成redis缓存
        $redisArr['pub_destination:' . $db->id] = json_encode($db);
        Redis::mset($redisArr);//提交缓存
        //
        opLog('pub_destination', [['type' => '修改', 'this_id' => $id, 'content' => json_encode($inp)]]);//记录日志
        return getSuccess(1);
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
        if (getIsExist('pub_destination', 'father_id', $id, 0) > 0) {
            return getSuccess('当前导航存在下级,无法进行删除操作');
        }
//        if (getIsExist('pub_departure_city_line', 'navigation_id', $id, 1) > 0) {
//            return getSuccess('当前导航存在绑定的产品, 无法进行删除操作');
//        }
        setDel('pub_destination', $id);
        return getSuccess(1);
    }


    public function del(Request $request)
    {
        //
        $inp = $request->all();
        setDestroy('pub_line_city_destination', $inp['id']);
        setDelRedis('pub_line_city_destination', $inp['id']);
        return getSuccess(1);
    }

    public function start(Request $request)
    {
        $inp = $request->all();
        setNoLock('pub_line_city_destination', $inp['id']);
        return getSuccess(1);
    }

    public function stop(Request $request)
    {
        $inp = $request->all();
        setLock('pub_line_city_destination', $inp['id']);
        return getSuccess(1);
    }

    public function tableEdit(Request $request)
    {
        $inp = $request->all();
        $res = setTableEdit('pub_line_city_destination', $inp['id'], $inp['field'], $inp['value'], 0);
        if ($res > 0) {
            return getSuccess(1);
        } else {
            return getSuccess($res);
        }
    }

}
