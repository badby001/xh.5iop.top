<?php

namespace App\Http\Controllers\Sys\Pages\Admin;

use App\Http\Controllers\Controller;
use App\Model\Pages\Admin\AdmRole;
use App\Model\Pages\Admin\AdmRoleRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class AdmUserRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('.sys.pages.admin.admUserRole');
    }

    public function read(Request $request)
    {

        $inp = $request->all();
        $where =
            function ($query) use ($inp) {
                $query->whereIn('add_code', getInjoin(_admCodes()));//数据权限控制
                if (isset($inp['is_lock'])) {
                    $query->where('is_lock', $inp['is_lock'] == "n" ? 1 : 0);
                }
                if (isset($inp['key'])) {
                    $query->where('title', 'like', '%' . $inp['key'] . '%');
                }
                if (isset($inp['dateType'])) {
                    if ($inp['dateType'] == 'addTime') {
                        if (isset($inp['start_time']) && isset($inp['end_time'])) {
                            $query->whereBetween('add_time',[$inp['start_time'],$inp['end_time']]);
                        } else if (isset($inp['start_time'])) {
                            $query->where('add_time', '>=', $inp['start_time']);
                        } else if (isset($inp['end_time'])) {
                            $query->where('add_time', '<=', $inp['end_time']);
                        }
                    }
                }
            };

        $db = AdmRole::select('id', 'code', 'title', 'remarks', 'by_sort', 'is_lock', 'add_code', 'add_time', 'up_code', 'up_time')
            ->where('is_del', 0)
            ->where($where)
            ->orderBy('is_lock', 'asc')
            ->orderBy('by_sort', 'desc')
            ->orderBy('add_time', 'asc')
            ->paginate($inp['limit'])
            ->all();
        //生成redis缓存
        $redisArr = [];
        foreach ($db as $k => $v) {
            $redisArr['adm_role:' . $v->id] = json_encode($v);//redis不存在,获取数据库
        }
        Redis::mset($redisArr);//提交缓存
        //读取缓存
        $dbData = [];
        foreach ($redisArr as $k => $v) {
            $this_id = json_decode($v)->id;//当前id
            $redisVal = json_decode(Redis::get('adm_role:' . $this_id));
            $dbData[] = [
                'id' => $redisVal->id,
                'code' => $redisVal->code,
                'title' => $redisVal->title,
                'remarks' => $redisVal->remarks,
                'is_lock_name' => getIsLock($redisVal->is_lock),//状态
                'is_lock' => $redisVal->is_lock,//状态
                'by_sort' => $redisVal->by_sort,
                'add_name' => getAdmName($redisVal->add_code),
                'add_time' => $redisVal->add_time,
                'up_name' => getAdmName($redisVal->up_code),
                'up_time' => $redisVal->up_time,
            ];
        }

        //
        //总记录
        $total = AdmRole::select(1)
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
        $list = [];
        $list["title"] = "根目录";
        $list["spread"] = true;
        $list["children"] = createTree(arrayToObject(getRouteData(2)));
        $db['id'] = '';
        return view('.sys.pages.admin.admUserRoleEdit', ['db' => $db, 'data' => json_encode($list), 'role' => '']);
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
        $db['id'] = $id;
        $list = [];
        $list["title"] = "根目录";
        $list["spread"] = true;
        $list["children"] = createTree(arrayToObject(getRouteData(2)));//getRouteMabage();
        $role = DB::table('adm_role_route')->where('role_id', $id)->select('route_id')->get();
        $roleList = collect([]);
        foreach ($role as $k) {
            $roleList->push($k->route_id);
        }
        return view('.sys.pages.admin.admUserRoleEdit', ['data' => json_encode($list), 'db' => $db, 'role' => $roleList]);
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
        //
        $db = new AdmRole();
        $db['code'] = getNewId();
        $db['is_lock'] = 0;
        $db['is_del'] = 0;
        $db['add_code'] = _admCode();
        $db['add_time'] = getTime(1);
        $db['title'] = $inp['title'];
        $db['remarks'] = $inp['remarks'];
        $db['by_sort'] = $inp['by_sort'];
        if ($db->save()) {
            //往角色关联表写入数据
            $list = getRouteDataValue($inp, $db['id']);
            DB::table('adm_role_route')->insert($list);
            //生成redis缓存
            $redisArr['adm_role:' . $db->id] = json_encode($db);
            Redis::mset($redisArr);//提交缓存
            opLog('adm_role', [['type' => '添加', 'this_id' => $db['id'], 'content' => json_encode($inp)]]);//记录日志
            return getSuccess(1);
        } else {
            return getSuccess(2);
        }
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
        //
        //删除所有当前角色相关的role数据
        AdmRoleRoute::where('role_id', $id)->delete();
        //
        $db = AdmRole::find($id);
        $db['up_code'] = _admCode();
        $db['up_time'] = getTime(1);
        $db['title'] = $inp['title'];
        $db['remarks'] = $inp['remarks'];
        $db['by_sort'] = $inp['by_sort'];
        if ($db->save()) {
            //往角色关联表写入数据
            $list = getRouteDataValue($inp, $db['id']);
            DB::table('adm_role_route')->insert($list);
            Redis::set('adm_role:' . $db['id'], json_encode($db));//进行缓存处理
            //生成redis缓存
            $redisArr['adm_role:' . $db->id] = json_encode($db);
            Redis::mset($redisArr);//提交缓存
            opLog('adm_role', [['type' => '修改', 'this_id' => $id, 'content' => json_encode($inp)]]);//记录日志
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

    }

    public function del(Request $request)
    {
        //
        $inp = $request->all();
        if (getIsExist('adm_user_role', 'role_id', $inp['id'], 0) > 0) {
            return getSuccess('当前权限正在被使用中,无法进行删除操作');
        }
        setDel('adm_role', $inp['id']);
        AdmRoleRoute::whereIn('role_id', getInjoin($inp['id']))->delete();//删除关系表
        return getSuccess(1);
    }

    public function start(Request $request)
    {
        $inp = $request->all();
        setNoLock('adm_role', $inp['id']);
        return getSuccess(1);
    }

    public function stop(Request $request)
    {
        $inp = $request->all();
        setLock('adm_role', $inp['id']);
        return getSuccess(1);
    }

    public function tableEdit(Request $request)
    {
        $inp = $request->all();
        $res = setTableEdit('adm_role', $inp['id'], $inp['field'], $inp['value'], 0);
        if ($res > 0) {
            return getSuccess(1);
        } else {
            return getSuccess($res);
        }
    }
}
