<?php

namespace App\Http\Controllers\Sys\Pages\Admin;

use App\Http\Controllers\Controller;
use App\Model\Pages\Admin\AdmGroup;
use App\Model\Pages\Admin\AdmUser;
use App\Model\Pages\Admin\AdmUserInfo;
use App\Model\Pages\Admin\AdmUserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class AdmUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('.sys.pages.admin.admUser');
    }

    public function read(Request $request)
    {
        $inp = $request->all();
        $where =
            function ($query) use ($inp) {
                $query->whereIn('a.add_code', getInjoin(_admCodes()));//数据权限控制
                if (isset($inp['is_lock'])) {
                    $query->where('a.is_lock', $inp['is_lock'] == "n" ? 1 : 0);
                }
                if (isset($inp['key'])) {
                    $query->where('a.open_id', $inp['key']);
                    $query->orWhere('b.name', 'like', '%' . $inp['key'] . '%');
                }
                if (isset($inp['role_id'])) {
                    $query->where('c.role_id', $inp['role_id']);
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
            ->select('a.id', 'a.code', 'a.open_type', 'a.open_id', 'a.is_lock', 'b.sex', 'b.name', 'b.birth_date', 'b.money_ratio', 'a.add_code', 'a.add_time', 'a.up_code', 'a.up_time', 'c.role_id','b.email')
            ->where(['a.is_del' => 0, 'a.open_type' => 'mobile'])
            ->where($where)
            ->orderBy('a.is_lock', 'asc')
            ->orderBy('a.add_time', 'asc')
            ->paginate($inp['limit'])
            ->all();
        //生成redis缓存
        $redisArr = [];
        foreach ($db as $k => $v) {
            $redisArr['adm_user:' . $v->id] = json_encode($v);//redis不存在,获取数据库
        }
        Redis::mset($redisArr);//提交缓存
        //读取缓存
        $dbData = [];
        foreach ($redisArr as $k => $v) {
            $this_id = json_decode($v)->id;//当前id
            $redisVal = json_decode(Redis::get('adm_user:' . $this_id));//读取缓存
            $dbData[] = [
                'id' => $redisVal->id,//id
                'code' => $redisVal->code,//编号
                'open_type' => $redisVal->open_type,
                'open_id' => $redisVal->open_id,
                'is_lock_name' => getIsLock($redisVal->is_lock),//状态
                'is_lock' => $redisVal->is_lock,//状态
                'sex' => getSex($redisVal->sex),//性别
                'name' => $redisVal->name,//姓名
                'birth_date' => $redisVal->birth_date,//出生日期
                'money_ratio' => $redisVal->money_ratio ?? 0,//提成比例
                'role_name' => getRoleName($redisVal->role_id),//权限角色
                'add_name' => getAdmName($redisVal->add_code),//创建者
                'add_time' => $redisVal->add_time,//创建时间
                'up_name' => getAdmName($redisVal->up_code),//最后修改人
                'up_time' => $redisVal->up_time,//修改时间
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
        if (_admAttState() != 2) {
            return '仅限同业使用, 请在"个人中心"进行"同行认证", 如果已经认证成功, 请重新登录.';
        }
        return view('.sys.pages.admin.admUserEdit', ['db' => $db, 'role_id' => []]);
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
        $role = DB::table('adm_user_role')->where('adm_id', $id)->select('role_id')->get();
        $result = json_decode($role, true);
        return view('.sys.pages.admin.admUserEdit', ['db' => $db[0], 'role_id' => $result[0]['role_id'] ?? []]);
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
        //验证用户名是否存在
        if (getIsExist('adm_user', 'open_id', $inp['open_id'], 0)) {
            return getSuccess('账号已存在, 重新换一个吧.');
        }
        $code = getNewId();
        $adm = new AdmUser();
        $adm['code'] = $code;
        $adm['open_type'] = 'mobile';
        $adm['open_id'] = $inp['open_id'];
        $adm['pass_word'] = Hash::make($inp['pass_word']);
        $adm['is_lock'] = 0;
        $adm['is_del'] = 0;
        $adm['add_code'] = _admCode();
        $adm['add_time'] = getTime(1);
        if ($adm->save()) {
            //创建admInfo信息
            $info = new AdmUserInfo();
            $info['adm_code'] = $adm['code'];
            $info['name'] = $inp['name'];
            $info['sex'] = $inp['sex'] == 0 ? 0 : 1;
            $info['birth_date'] = $inp['birth_date'];
            $info['money_ratio'] = $inp['money_ratio'] ?? 0;
            $info['pub_user_id'] = getErpNewId('user_base');
            $info['attestation_state'] = 2;
            $info->save();
            //
            //在角色关系表中查找当前账号是否存在, 如果存在则获取主账号, 否则忽略
            $isGroupDb = AdmGroup::where('adm_code', _admCode())->select('group_number')->first();
            DB::table('adm_group')->insert(['adm_code' => $code, 'group_number' => $isGroupDb['group_number']]);
            //保存角色
            if ($inp['role_id']) {
                DB::table('adm_user_role')->insert(['adm_id' => $adm['id'], 'role_id' => $inp['role_id'], 'add_time' => getTime(1)]);
            }
            //需要往小强的user_bsse表写入联系人信息
            //主账号在创建子账号时,子账号的公司信息直接使用主账号的公司id
            $cpyInfo = DB::connection('sqlsrv')->table('User_Base')->where(['ERPID' => 895, 'pubuserId' => _admPubUserId(), 'isDel' => 0])->select('cpyId', 'cpyName')->get()[0];
            DB::connection('sqlsrv')->table('User_Base')
                ->insert(array(
                    'ERPID' => '895',
                    'code' => '1',
                    'ID' => $info['pub_user_id'],
                    'PubUserID' => $info['pub_user_id'],
                    'CpyID' => $cpyInfo->cpyId,
                    'trueName' => $inp['name'],
                    'Mobile' => $inp['open_id'],
                    'cpyName' => $cpyInfo->cpyName,
                    'remark' => '从小助手系统中通过主账号创建',
                    'type' => 1,
                ));
            //生成redis缓存
            $redisArr['adm_user:' . $adm['id']] = json_encode($adm);
            Redis::mset($redisArr);//提交缓存
            //
            opLog('adm_user', [['type' => '添加', 'this_id' => $adm['id'], 'content' => json_encode($inp)]]);//记录日志
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
        $adm = AdmUser::find($id);
        if ($inp['pass_word']) {
            $adm['pass_word'] = Hash::make($inp['pass_word']);
        }
        $adm['up_code'] = _admCode();
        $adm['up_time'] = getTime(1);
        $adm->save();
        //修改admInfo信息
        AdmUserInfo::where('adm_code', $adm['code'])
            ->update([
                'name' => $inp['name'],
                'sex' => $inp['sex'] == 0 ? 0 : 1,
                'birth_date' => $inp['birth_date'],
                'money_ratio' => $inp['money_ratio'] ?? 0,
            ]);

        //保存角色
        if ($inp['role_id']) {
            AdmUserRole::where('adm_id', $id)->delete();
            DB::table('adm_user_role')->insert(['adm_id' => $adm['id'], 'role_id' => $inp['role_id'], 'add_time' => getTime(1)]);
        }
        //生成redis缓存
        $redisArr['adm_user:' . $adm->id] = json_encode($adm);
        Redis::mset($redisArr);//提交缓存
        //
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

    public function del(Request $request)
    {
        //
        $inp = $request->all();
        setDel('adm_user', $inp['id']);
        AdmUserRole::whereIn('adm_id', getInjoin($inp['id']))->delete();//删除关系表
        return getSuccess(1);
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
