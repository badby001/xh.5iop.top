<?php

namespace App\Http\Controllers\Sys\Pages\System;

use App\Http\Controllers\Controller;
use App\Model\Pages\Admin\AdmRole;
use App\Model\Pages\Admin\AdmUser;
use App\Model\Pages\Admin\AdmUserInfo;
use App\Model\Pages\XQERPV3\UserBase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('.sys.pages.userManage.user');
    }


    public function read(Request $request)
    {
        $inp = $request->all();
        $where =
            function ($query) use ($inp) {
                if (isset($inp['is_lock'])) {
                    $query->where('a.is_lock', $inp['is_lock'] == "n" ? 1 : 0);
                }
                if (isset($inp['state'])) {
                    $query->where('b.attestation_state', $inp['state']);
                }
                if (isset($inp['key'])) {
                    $query->where('b.name', 'like', '%' . $inp['key'] . '%')
                        ->orwhere('a.open_id', 'like', '%' . $inp['key'] . '%');
                }
                if (isset($inp['dateType'])) {
                    if ($inp['dateType'] == 'addTime') {
                        if (isset($inp['start_time']) && isset($inp['end_time'])) {
                            $query->whereBetween('a.add_time', [$inp['start_time'], $inp['end_time']]);
                        } else if (isset($inp['start_time'])) {
                            $query->where('a.add_time', '>=', $inp['start_time']);
                        } else if (isset($inp['end_time'])) {
                            $query->where('a.add_time', '<=', $inp['end_time']);
                        }
                    }
                }
            };
        $db = DB::table('adm_user as a')
            ->leftJoin('adm_user_info as b', 'a.code', '=', 'b.adm_code')
            ->leftJoin('adm_user_role as c', 'a.id', '=', 'c.adm_id')
            ->leftJoin('adm_group as d', 'a.code', '=', 'd.adm_code')
            ->select('a.id', 'a.code', 'a.open_type', 'a.open_id', 'a.is_lock', 'b.sex', 'b.name', 'b.birth_date', 'b.money_ratio', 'a.add_code', 'a.add_time', 'a.up_code', 'a.up_time', 'c.role_id', 'd.group_number', 'b.attestation_state', 'b.attestation_tourist_agency','b.email')
            ->where(['a.is_del' => 0, 'a.open_type' => 'mobile'])
            ->where($where)
            ->orderBy('a.is_lock', 'asc')
            ->orderBy('a.add_time', 'asc')
            ->paginate($inp['limit'])
            ->all();
        //生成redis缓存
        $redisArr = [];
        foreach ($db as $k => $v) {
            $redisArr['user:' . $v->id] = json_encode($v);//redis不存在,获取数据库
        }
        Redis::mset($redisArr);//提交缓存
        //读取缓存
        $dbData = [];
        foreach ($redisArr as $k => $v) {
            $this_id = json_decode($v)->id;//当前id
            $redisVal = json_decode(Redis::get('user:' . $this_id));//读取缓存
            $adm_user_login_db = DB::connection('logSystem')->table('adm_user_login')
                ->select('add_time')
                ->orderBy('add_Time', 'desc')
                ->where('add_code', $redisVal->code)//数据权限控制
                ->paginate(1)
                ->first();
            $last_login_time = $adm_user_login_db->add_time ?? '';
            $wei_xin = getDbData('adm_user', ['code' => $redisVal->code, 'open_type' => 'weixin'], 'open_id', 0.5)[0]->open_id ?? 0;
            $dbData[] = [
                'id' => $redisVal->id,//id
                'code' => $redisVal->code,//编号
                'attestation_tourist_agency' => '[' . getAttestationState($redisVal->attestation_state) . ']' . $redisVal->attestation_tourist_agency,
                'group_number_name' => getGroupNumber($redisVal->group_number),//分组编号
                'open_type' => $redisVal->open_type,
                'open_id' => $redisVal->open_id,
                'is_lock_name' => getIsLock($redisVal->is_lock),//状态
                'is_lock' => $redisVal->is_lock,//状态
                'sex' => getSex($redisVal->sex),//性别
                'name' => $redisVal->name,//姓名
                'birth_date' => $redisVal->birth_date,//出生日期
                'role_name' => getRoleName($redisVal->role_id),//权限角色
                'add_name' => getAdmName($redisVal->add_code),//创建者
                'add_time' => $redisVal->add_time,//创建时间
                'up_name' => getAdmName($redisVal->up_code),//最后修改人
                'up_time' => $redisVal->up_time,//修改时间
                'last_login_time' => $last_login_time,//最后登录时间
                'wei_xin' => $wei_xin === 0 ? '-' : '已绑定',//微信快捷账号绑定状态
                'email' => $redisVal->email,//email
            ];
        }


        //
        //总记录
        $total = DB::table('adm_user as a')
            ->leftJoin('adm_user_info as b', 'a.code', '=', 'b.adm_code')
            ->leftJoin('adm_user_role as c', 'a.id', '=', 'c.adm_id')
            ->select('1')
            ->where(['a.is_del' => 0, 'a.open_type' => 'mobile'])
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
        $db['id'] = '';
        $db['is_lock'] = '';
        $db['admUserInfo']['sex'] = 1;
        return view('.sys.pages.userManage.userEdit', ['db' => $db]);
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
        $db = AdmUser::where('id', $id)
            ->select('id', 'code', 'is_lock')
            ->with('admUserInfo:adm_code,sex')
            ->get();
        return view('.sys.pages.userManage.userEdit', ['db' => $db[0]]);
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
        $adm = AdmUser::find($id);
        if ($inp['pass_word']) {
            $adm['pass_word'] = Hash::make($inp['pass_word']);
        }
        $adm['up_code'] = _admCode();
        $adm['up_time'] = getTime(1);
        $adm->save();
        //
        $info = AdmUserInfo::where('adm_code', $adm['code'])
            ->update([
                'name' => $inp['name'],
            ]);
        $admPubUserId = getDbData('adm_user_info', ['adm_code' => $adm['code']], 'pub_user_id', 0.5)[0]->pub_user_id ?? 0;
        //同步更新小强数据库
        UserBase::where('pubUserId', $admPubUserId)
            ->update([
                'trueName' => $inp['name'],
            ]);
        opLog('adm_user', [['type' => '修改', 'this_id' => $id, 'content' => json_encode($inp)]]);//记录日志
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


    public function userPower()
    {
        //
        $db = AdmRole::select('id', 'code', 'title')
            ->where(['is_lock' => 0, 'is_del' => 0])
            ->get();
        $list = [];
        foreach ($db as $k => $v) {
            $list[] = [
                'id' => $v->id,
                'code' => getAdmName($v->code) ?? '',
                'title' => $v->title,
                'count' => count(getDbData('adm_role_route', ['role_id' => $v->id], 'role_id', 0.5)),
            ];
        }

        return view('.sys.pages.userManage.userPower', ['db' => json_encode($list)]);
    }

    public function userPowerStore(Request $request)
    {
        //
        $inp = $request->all();
        $powers = $inp['powers'];//权限值,多个用逗号分开
        $roleId = $inp['roleId'];//角色id
        if (substr_count($powers, ',') > 100) {
            return getSuccess('当前操作对服务器的消耗非常严重, 请将所要设置的权限值的数量控制在10条以内;');
        }
        //遍历权限值id开始组合角色id
        $logList = [];
        $list = [];
        $vv = [];
        foreach (getInjoin($powers) as $k) {
            foreach (getInjoin($roleId) as $kk) {
                //为了避免冲突,先删除有关的权限值id
                DB::table('adm_role_route')->where(['route_id' => $k, 'role_id' => $kk])->delete();
                $list[] = array('role_id' => $kk, 'route_id' => $k, 'add_time' => getTime(1));
            }
            //日志记录
            $vv['type'] = '赋予权限';
            $vv['this_id'] = $k;
            $vv['content'] = '当前角色 ' . $roleId . ' 已被设置权限值为' . $powers;
            $logList[] = $vv;
        }
        $res = DB::table('adm_role_route')->insert($list);
        if ($res) {
            opLog('pub_logs', $logList);//记录日志
            return getSuccess(1);
        } else {
            return getSuccess(2);
        }
    }


    public function start(Request $request)
    {
        $inp = $request->all();
        setNoLock('adm_user', $inp['id']);
        return getSuccess(1);
    }

    public function stop(Request $request)
    {
        $inp = $request->all();
        setLock('adm_user', $inp['id']);
        return getSuccess(1);
    }
}
