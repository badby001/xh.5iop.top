<?php

namespace App\Http\Controllers\Sys\Pages\System;

use App\Http\Controllers\Controller;
use App\Model\Pages\Admin\AdmUser;
use App\Model\Pages\Admin\AdmUserInfo;
use App\Model\Pages\XQERPV3\UserBase;
use App\Model\Pages\XQERPV3\UserCpy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class AttestationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('.sys.pages.attestation.index');
    }


    public function read(Request $request)
    {
        $inp = $request->all();
        $where =
            function ($query) use ($inp) {
                if (isset($inp['state'])) {
                    $query->where('attestation_state', $inp['state']);
                }
                if (isset($inp['key'])) {
                    $query->where('name', 'like', '%' . $inp['key'] . '%')
                        ->orwhere('attestation_tourist_agency', 'like', '%' . $inp['key'] . '%')
                        ->orwhere('attestation_address', 'like', '%' . $inp['key'] . '%');
                }
            };
        $db = DB::table('adm_user_info')
            ->select('adm_code', 'name', 'sex', 'pub_user_id', 'attestation_state', 'attestation_tourist_agency', 'attestation_area', 'attestation_area_code', 'attestation_address', 'attestation_business_license_img')
            ->where($where)
            ->orderBy('attestation_state', 'desc')
            ->paginate($inp['limit'])
            ->all();
        //生成redis缓存
        $redisArr = [];
        foreach ($db as $k => $v) {
            $redisArr['adm_user_info_attestation:' . $v->adm_code] = json_encode($v);//redis不存在,获取数据库
        }
        Redis::mset($redisArr);//提交缓存
        //读取缓存
        $dbData = [];
        foreach ($redisArr as $k => $v) {
            $this_id = json_decode($v)->adm_code;//当前id
            $redisVal = json_decode(Redis::get('adm_user_info_attestation:' . $this_id));//读取缓存
            $dbData[] = [
                'adm_code' => $redisVal->adm_code,//编号
                'name' => $redisVal->name,
                'sex' => getSex($redisVal->sex),//性别
                'pub_user_id' => $redisVal->pub_user_id,
                'attestation_state' => $redisVal->attestation_state,
                'attestation_state_show' => getAttestationState($redisVal->attestation_state),
                'attestation_tourist_agency' => $redisVal->attestation_tourist_agency,
                'attestation_area' => $redisVal->attestation_area,
                'attestation_address' => $redisVal->attestation_address,
                'attestation_business_license_img' => $redisVal->attestation_business_license_img,
            ];
        }


        //
        //总记录
        $total = DB::table('adm_user_info')
            ->select('1')
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
        $redisVal = json_decode(Redis::get('adm_user_info_attestation:' . $id));//读取缓存
        $attestation_tourist_agency = $redisVal->attestation_tourist_agency;
        $attestation_area = $redisVal->attestation_area;
        $attestation_address = $redisVal->attestation_address;
        $attestation_business_license_img = $redisVal->attestation_business_license_img;
        //
        $data = [];
        $data['id'] = $id;
        $data['mobile'] = getDbData('adm_user', ['code' => $redisVal->adm_code], 'open_id', 0.5)[0]->open_id ?? 0;
        $data['name'] = $redisVal->name;;
        $data['attestation_tourist_agency'] = $attestation_tourist_agency;
        $data['address'] = $attestation_area . $attestation_address;
        $data['attestation_business_license_img'] = $attestation_business_license_img;
        return view('.sys.pages.attestation.check', ['db' => $data]);
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
        $inp = $request->all();
        $type = '认证失败';
        if ($inp['state'] == 2) {
            $redisVal = json_decode(Redis::get('adm_user_info_attestation:' . $id));//读取缓存
            //首先往小强数据库写入公司信息然后更新联系人信息中的cpyId
            $userCpyId = getErpNewId('user_cpy');
            $db = new UserCpy();
            $db['ID'] = $userCpyId;
            $db['code'] = $userCpyId;
            $db['ERPID'] = '895';
            $db['isVip'] = '1';
            $db['simName'] = $redisVal->attestation_tourist_agency;
            $db['cpyName'] = $redisVal->attestation_tourist_agency;
            $db['leader'] = $redisVal->name;
            $db['isLock'] = '-1';
            $db['addr'] = $redisVal->attestation_area . $redisVal->attestation_address;
            $db['remark'] = '通过小助手同行认证生成';
            if ($db->save()) {
                //新公司创建成功后, 更新联系人公司
                UserBase::where('pubuserid', $redisVal->pub_user_id)->update(['cpyId' => $db->ID, 'cpyName' => $db->simName]);
                $type = '认证成功';
            }
        }
        AdmUserInfo::where('adm_code', $id)->update(['attestation_state' => $inp['state']]);
        $dbAdm = AdmUser::where('code', $id)->select('id')->first();
        opLog('adm_user', [['type' => $type, 'this_id' => $dbAdm->id, 'content' => json_encode($inp)]]);//记录日志
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
}
