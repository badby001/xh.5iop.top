<?php

namespace App\Http\Controllers\Sys\Pages\System;

use App\Http\Controllers\Controller;
use App\Model\Pages\System\Navigation;
use App\Model\Pages\System\NavigationLine;
use App\Model\Pages\XQERPV3\BaseLine;
use App\Model\Pages\XQERPV3\LinePlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class NavigationLineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    //页面
    public function bind($classify, $id)
    {
        return view('.sys.pages.system.navigationLine', ['classify' => $classify, 'id' => $id]);
    }


    public function read(Request $request)
    {
        $inp = $request->all();
        //
        //寻找导航下方是否还存在子项,如果存在,则获取所有子项id
        $navId = '';
        $navDb = Navigation::where(['is_lock' => 0, 'is_del' => 0])->select('id', 'layer_path')->get();
        foreach ($navDb as $k => $v) {
            if (in_array($inp['navigation_id'], getInjoin($v['layer_path']))) {
                $navId = $navId . ',' . $v['id'];
            }
        }
        $inp['navId'] = $inp['navigation_id'] . $navId;
        //
        //关键字的分库查询,拿到关键字从分库中找到的id,继续传递到主库进行id  in查询
        $basesLineDB = '';
        $lineId = '';
        if (isset($inp['key'])) {
            $basesLineDB = BaseLine::where(['erpid' => 895, 'isDel' => 0])->where('Title', 'like', '%' . $inp['key'] . '%')->select('id')->get();
            foreach ($basesLineDB as $k => $v) {
                $lineId = $lineId . ',' . $v['id'];
            }
            $inp['line_id'] = $lineId;
        }
        //
        $where =
            function ($query) use ($inp) {
                $query->whereIn('navigation_id', getInjoin($inp['navId']));
                if (isset($inp['is_lock'])) {
                    $query->where('is_lock', $inp['is_lock'] == "n" ? 1 : 0);
                }
                if (isset($inp['key'])) {
                    $query->whereIn('line_id', getInjoin($inp['line_id']));
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
        $db = NavigationLine::select('id', 'navigation_id', 'line_id', 'by_sort', 'is_lock', 'add_code', 'add_time', 'up_code', 'up_time')
            ->with(['basesLine:id,title'])
            ->where($where)
            ->orderBy('is_lock', 'asc')
            ->orderBy('by_sort', 'desc')
            ->orderBy('add_time', 'asc')
            ->paginate($inp['limit'])
            ->all();
        //生成redis缓存
        $redisArr = [];
        foreach ($db as $k => $v) {
            $redisArr['pub_navigation_line:' . $v->id] = json_encode($v);//redis不存在,获取数据库
        }
        Redis::mset($redisArr);//提交缓存
        //读取缓存
        $dbData = [];
        foreach ($redisArr as $k => $v) {
            $this_id = json_decode($v)->id;//当前id
            $redisVal = json_decode(Redis::get('pub_navigation_line:' . $this_id));
            $dbData[] = [
                'id' => $redisVal->id,
                'navigation_id' => $redisVal->navigation_id,
                'line_id' => (json_decode(Redis::get('base_line:' . $redisVal->line_id))->ID) ?? '已失效',
                'line_title' => (json_decode(Redis::get('base_line:' . $redisVal->line_id))->Title) ?? '缓存已失效, 请勾选当前信息进行刷新缓存',
                'navigation_name' => (json_decode(Redis::get('pub_navigation:' . $redisVal->navigation_id))->title) ?? '已失效',
                'img' => (json_decode(Redis::get('base_line:' . $redisVal->line_id))->img) ?? '',
                'video' => (json_decode(Redis::get('base_line:' . $redisVal->line_id))->video) ?? '',
                'by_sort' => $redisVal->by_sort,
                'is_lock_name' => getIsLock($redisVal->is_lock),//状态
                'is_lock' => $redisVal->is_lock,//状态
                'add_name' => getAdmName($redisVal->add_code),
                'add_time' => $redisVal->add_time,
                'up_name' => getAdmName($redisVal->up_code),
                'up_time' => $redisVal->up_time,
            ];
        }

        //
        //总记录
        $total = NavigationLine::select(1)
            ->where($where)
            ->count();
        $data = [];
        $data['code'] = 0;
        $data['msg'] = '查询成功';
        $data['data'] = $dbData;
        $data['count'] = $total;
        return $data;
    }


    public function productRead(Request $request)
    {
        $inp = $request->all();
        if ($inp['classify'] !== 'null') {
            //
            //将导航中已经绑定过的线路获取出来,进行排除
            $line_id = '';
            $navDb = NavigationLine::where('navigation_id', $inp['navigationId'])->select('line_id')->get();
            foreach ($navDb as $k => $v) {
                $line_id = $line_id . $v->line_id . ',';
            }
            $where =
                function ($query) use ($inp) {
                    if (isset($inp['key'])) {
                        $query->where('title', 'like', '%' . $inp['key'] . '%');
                    }
                    if (isset($inp['classify'])) {
                        $query->where('classify', $inp['classify']);
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

            $db = BaseLine::where(['erpid' => 895, 'isDel' => 0, 'isLock' => 0, 'classify' => $inp['classify']])
                ->with(['basesLineType:id,type'])
                ->whereNotIn('id', getInjoin($line_id))
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
                    'line_share_name' => $redisVal->lineShareID ? '<span class="layui-btn layui-btn-xs layui-btn-primary">是</span>' : '<span class="layui-btn layui-btn-xs layui-btn-primary">否</span>',//公共
                    'line_type_name' => $redisVal->lineTypeName,//线路类别
                    'type' => getBaseLineType_type($redisVal->bases_line_type->type ?? 3),//产品类型
                    'code' => $redisVal->Code,//线路代码
                    'planCount' => $baseLineDB,//团期数量
                    'title' => $redisVal->Title,//线路名称
                    'days' => $redisVal->Days,//天数
                    'night' => $redisVal->night,//住宿晚数
                    'cpyName' => getSupName($redisVal->cpyName),//供应商
                    'operate_type' => $redisVal->operate_type == 0 ? '<span class="layui-btn layui-btn-xs layui-btn-warm">自营</span>' : '<span class="layui-btn layui-btn-xs layui-btn-normal">他营</span>',//经营类型
                    'adult_price' => $redisVal->adultPrice,
                    'child_price' => $redisVal->childPrice,
                    'sadult_price' => $redisVal->sadultPrice,
                    'schild_price' => $redisVal->schildPrice,
                    'add_time' => $redisVal->AddTime,
                    'classify' => $inp['classify'],
                ];
            }

            //
            //总记录
            $total = BaseLine::select(1)
                ->where(['erpid' => 895, 'isDel' => 0, 'isLock' => 0, 'classify' => $inp['classify']])
                ->whereNotIn('id', getInjoin($line_id))
                ->where($where)
                ->count();
            $data = [];
            $data['code'] = 0;
            $data['msg'] = '查询成功';
            $data['data'] = $dbData;
            $data['count'] = $total;
            return $data;
        } else {
            return getSuccess('当前导航分类不明确, 请先对导航设置分类');
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //先把前台传过来的信息写入到库
        $inp = $request->all();
        $list = [];
        $redisNavigation_id = '';//作为导航id1
        $redisLine_ids = '';//作为线路id组
        $logList = [];
        $vv = [];
        foreach (getInjoin($inp['line_id']) as $k => $v) {
            $list[] = array('navigation_id' => $inp['navigation_id'], 'line_id' => $v, 'add_code' => _admCode(), 'add_time' => getTime(1));
            $redisNavigation_id = $inp['navigation_id'];//导航id
            $redisLine_ids = $v . ',' . $redisLine_ids;//线路id组
        }
        DB::table('pub_navigation_line')->insert($list);
        //上边写入成功后,通过navigation_id与line_ids 从库中找到对应的值 然后写入redis  用in
        $db = NavigationLine::where('navigation_id', $redisNavigation_id)
            ->whereIn('line_id', getInjoin($redisLine_ids))
            ->get();
        //生成redis缓存
        $redisArr = [];
        foreach ($db as $k => $v) {
            $redisArr['pub_navigation_line:' . $v->id] = json_encode($v);
        }
        Redis::mset($redisArr);//提交缓存
        opLog('pub_navigation', [['type' => '关联产品', 'this_id' => $inp['navigation_id'], 'content' => json_encode($inp)]]);//记录日志
        return getSuccess(1);
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


    public function del(Request $request)
    {
        //
        $inp = $request->all();
        setDestroy('pub_navigation_line', $inp['id']);
        setDelRedis('pub_navigation_line', $inp['id']);
        return getSuccess(1);
    }

    public function start(Request $request)
    {
        $inp = $request->all();
        setNoLock('pub_navigation_line', $inp['id']);
        return getSuccess(1);
    }

    public function stop(Request $request)
    {
        $inp = $request->all();
        setLock('pub_navigation_line', $inp['id']);
        return getSuccess(1);
    }

    public function tableEdit(Request $request)
    {
        $inp = $request->all();
        $res = setTableEdit('pub_navigation_line', $inp['id'], $inp['field'], $inp['value'], 0);
        if ($res > 0) {
            return getSuccess(1);
        } else {
            return getSuccess($res);
        }
    }


    public function brushRedis(Request $request)
    {
        $inp = $request->all();

        //先从navigation_line表获取ling_id
        $data = NavigationLine::select('line_id')
            ->whereIn('id', getInjoin($inp['id']))
            ->get();
        $arrs = collect();//使用数组保存当前用户所拥有的权限id
        foreach ($data as $k => $v) {
            $arrs->push($v['line_id']);
        }
        $db = BaseLine::where(['erpid' => 895, 'isDel' => 0, 'isLock' => 0])
            ->with(['basesLineType:id,type'])
            ->whereIn('ID', $arrs)
            ->get();
        //生成redis缓存
        $redisArr = [];
        foreach ($db as $k => $v) {
            $redisArr['base_line:' . $v->ID] = json_encode($v);//redis不存在,获取数据库
        }
        Redis::mset($redisArr);//提交缓存
        //日志记录
        $logList = [];
        $vv = [];
        foreach (getInjoin($inp['id']) as $k => $v) {
            $vv['type'] = '刷新缓存';
            $vv['this_id'] = $v;
            $vv['content'] = '当前项目缓存已被刷新';
            $logList[] = $vv;
        }
        opLog('pub_navigation_line', $logList);
        return getSuccess(1);
    }

}
