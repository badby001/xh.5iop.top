<?php

namespace App\Http\Controllers\Sys\Pages\System\XiaoQiang;

use App\Http\Controllers\Controller;
use App\Model\Pages\System\LineCityDestination;
use App\Model\Pages\XQERPV3\BaseLine;
use App\Model\Pages\XQERPV3\LinePlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class BaseLineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('.sys.pages.xiaoqiang.baseLine');
    }


    public function read(Request $request)
    {
        $inp = $request->all();
        //
        //
        $where =
            function ($query) use ($inp) {
                if (isset($inp['key'])) {
                    $query->where('title', 'like', '%' . $inp['key'] . '%')
                        ->orwhere('tag', 'like', '%' . $inp['key'] . '%');
                }
                if (isset($inp['classify'])) {
                    if ($inp['classify'] >= 0) {
                        $query->where('classify', $inp['classify']);
                    } else {
                        $query->whereNull('classify');
                    }
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
        $db = BaseLine::where(['erpid' => 895, 'isDel' => 0, 'isLock' => 0])
            ->with(['basesLineType:id,type'])
            ->where($where)
            ->orderBy('bySort', 'desc')
            ->orderBy('AddTime', 'asc')
            ->paginate($inp['limit'])
            ->all();
        //生成redis缓存
        $redisArr = [];
        foreach ($db as $k => $v) {
            $redisArr['base_line:' . $v->ID] = json_encode($v);//redis不存在,获取数据库
        }
        Redis::mset($redisArr);//提交缓存
        //读取缓存
        $dbData = [];
        foreach ($redisArr as $k => $v) {
            $this_id = json_decode($v)->ID;//当前id
            $redisVal = json_decode(Redis::get('base_line:' . $this_id));
            //团期数量
            $baseLineDB = LinePlan::where(['erpid' => 895, 'isDel' => 0, 'lineId' => $this_id])->where('PlanDate', '>=', getTime(2))->count();
            $dbData[] = [
                'id' => $redisVal->ID,
                'classify' => $redisVal->classify,
                'classify_name' => getClassify($redisVal->classify),
                'line_share_name' => $redisVal->lineShareID ? '<span class="layui-btn layui-btn-xs layui-btn-primary">是</span>' : '<span class="layui-btn layui-btn-xs layui-btn-primary">否</span>',//公共
                'line_type_name' => $redisVal->lineTypeName,//线路类别
                'type' => getBaseLineType_type($redisVal->bases_line_type->type ?? 3),//产品类型
                'code' => $redisVal->Code,//线路代码
                'planCount' => $baseLineDB,//团期数量
                'title' => $redisVal->Title,//线路名称
                'days' => $redisVal->Days,//天数
                'night' => $redisVal->night,//住宿晚数
                'cpyName' => getSupName($redisVal->cpyName),//供应商
                'cpyLeader' => $redisVal->cpyLeader,//供应商联系人
                'cpyMobile' => $redisVal->cpyMobile,//供应商手机号码
                'operate_type' => $redisVal->operate_type == 0 ? '<span class="layui-btn layui-btn-xs layui-btn-warm">自营</span>' : '<span class="layui-btn layui-btn-xs layui-btn-normal">他营</span>',//经营类型
                'adult_price' => $redisVal->adultPrice,//同行成人价
                'child_price' => $redisVal->childPrice,//同行儿童价
                'sadult_price' => $redisVal->sadultPrice,//市场成人价
                'schild_price' => $redisVal->schildPrice,//市场儿童价
                'img' => $redisVal->img1,//图片地址
                'video' => $redisVal->video,//视频地址
                'satisfaction' => $redisVal->satisfaction,//满意度
                'tag' => $redisVal->tag,//产品标签
                'tags' => $redisVal->Tags,//简要描述
                'adm_name' => $redisVal->admName,//创建者
                'add_time' => $redisVal->AddTime,
                'bySort' => $redisVal->bySort,
            ];
        }
        //
        //总记录
        $total = BaseLine::select(1)
            ->where(['erpid' => 895, 'isDel' => 0, 'isLock' => 0])
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
        return view('.sys.pages.xiaoqiang.baseLineEdit', ['id' => $id]);
    }

    public function edit_classify($id)
    {
        //
        return view('.sys.pages.xiaoqiang.baseLineEditClassify', ['id' => $id]);
    }

    public function edit_tag($id)
    {
        //
        return view('.sys.pages.xiaoqiang.baseLineEditTag', ['id' => $id]);
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
        $logList = [];
        $update = [];
        if (isset($inp['classify'])) {
            $update['classify'] = $inp['classify'];
        }
        if (isset($inp['tag'])) {
            $tag = $inp['tag'];
            $tag = str_replace("，", ",", $tag);
            $update['tag'] = $tag;
        }
        $vv = [];
        if (strpos($id, ',') !== false) {
            DB::connection('sqlsrv')->table('Base_Line')
                ->whereIn('id', getInjoin($id))
                ->update($update);
            //日志记录
            foreach (getInjoin($id) as $k => $v) {
                $vv['type'] = '修改';
                $vv['this_id'] = $v;
                $vv['content'] = '当前项目已被修改为' . json_encode($update);
                $logList[] = $vv;
            }
        } else {
            $db = BaseLine::where(['erpid' => 895, 'isDel' => 0, 'id' => $id])
                ->update($update);
            //生成redis缓存
            $redisArr['base_line:' . $id] = json_encode($db);
            Redis::mset($redisArr);//提交缓存
            //日志记录
            $vv['type'] = '修改';
            $vv['this_id'] = $id;
            $vv['content'] = '当前项目已被修改为' . json_encode($update);
            $logList[] = $vv;
        }
        opLog('base_line', $logList);
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
    }


    public function tableEdit(Request $request)
    {
        $inp = $request->all();
        $res = setTableEdit('base_line', $inp['id'], $inp['field'], $inp['value'], 1);
        if ($res > 0) {
            return getSuccess(1);
        } else {
            return getSuccess($res);
        }
    }


    public function departureCity_destination($id)
    {
        $departureCityList = '';
        $destinationList = '';
        if (substr_count($id, ',') == 1) {
            $departureCityDB = LineCityDestination::where(['line_id' => $id, 'type' => 0])->select('to_id')->get();
            foreach ($departureCityDB as $k => $v) {
                $departureCityList = $v['to_id'] . ',' . $departureCityList;
            }
            $destinationDB = LineCityDestination::where(['line_id' => $id, 'type' => 1])->select('to_id')->get();
            foreach ($destinationDB as $k => $v) {
                $destinationList = $v['to_id'] . ',' . $destinationList;
            }
        }
        return view('.sys.pages.xiaoqiang.baseLineDepartureCity_DestinationEdit', ['lineId' => $id, 'departureCityList' => $departureCityList, 'destinationList' => $destinationList]);
    }

    public function departureCity_destinationUp(Request $request)
    {
        $inp = $request->all();

        //获取线路id
        $lineId = $inp['lineId'];
        if (substr_count($lineId, ',') > 100) {
            return getSuccess('当前操作对服务器的消耗非常严重, 请将所要设置线路的数量控制在100条以内;');
        }
        //获取出发城市id
        $departureCity = $inp['departureCityList'];
        if (substr_count($departureCity, ',') > 20) {
            return getSuccess('当前操作对服务器的消耗非常严重, 请将所要设置的出发城市数量控制在20条以内;');
        }
        //获取目的地id
        $destination = $inp['destinationList'];
        if (substr_count($destination, ',') > 20) {
            return getSuccess('当前操作对服务器的消耗非常严重, 请将所要设置的目的地数量控制在20条以内;');
        }
        //首先是删除所有和当前线路相关的信息
        LineCityDestination::destroy(getInjoin($lineId));
        //遍历线路id开始组合线路与出发城市和线路与目的地
        $logList = [];
        $vv = [];
        foreach (getInjoin($lineId) as $k) {
            //出发城市的组合
            $list[] = getDepartureCityDestinationeDataValue($departureCity, $k, 0);
            //目的地的组合
            $list[] = getDepartureCityDestinationeDataValue($destination, $k, 1);
            //日志记录
            $vv['type'] = '设置出发城市/目的地';
            $vv['this_id'] = $k;
            $vv['content'] = '当前项目已被设置出发城市/目的地为' . json_encode($list);
            $logList[] = $vv;
        }
        //重新组合数组
        $arrs = [];
        foreach ($list as $k => $v) {
            foreach ($v as $kk => $vv) {
                $arrs[] = array('line_id' => $vv['line_id'], 'to_id' => $vv['to_id'], 'type' => $vv['type'], 'add_code' => _admCode(), 'add_time' => getTime(1));
            }
        }
        $res = DB::table('pub_line_city_destination')->insert($arrs);
        if ($res) {
            opLog('pub_logs', $logList);//记录日志
            return getSuccess(1);
        } else {
            return getSuccess(2);
        }
    }
}
