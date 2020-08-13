<?php

namespace App\Http\Controllers\Sys\Pages\OftenInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('.sys.pages.oftenInformation.address');
    }


    public function read(Request $request)
    {

        $inp = $request->all();

        $where = [];
        $where['pageNum'] = $inp['page'];
        $where['pageSize'] = $inp['limit'];
        //$where['status'] = $inp['status'] ?? 0;
        $apiWebRes = callWebApi($where, site()['callWebApi'] . 'api/admUserAddr/selectAddrs');//调用接口
        if ($apiWebRes->code == 200) {
            //生成redis缓存
            $redisArr = [];
            foreach ($apiWebRes->data as $k => $v) {
                $redisArr['address:' . $v->id] = json_encode($v);//redis不存在,获取数据库
            }
            Redis::mset($redisArr);//提交缓存
            //读取缓存
            $dbData = [];
            foreach ($redisArr as $k => $v) {
                $this_id = json_decode($v)->id;//当前id
                $redisVal = json_decode(Redis::get('address:' . $this_id));
                $dbData[] = [
                    'id' => $redisVal->id,
                    'code' => $redisVal->code,
                    'name' => $redisVal->name,
                    'mobile' => $redisVal->mobile,
                    'province_andCity' => $redisVal->provinceAndCity,
                    'addr' => $redisVal->addr,
                    'postcode' => $redisVal->postcode,
                    'area_code' => $redisVal->areaCode,
                    'by_sort' => $redisVal->bySort,
                    'is_default' => getYes($redisVal->isDefault),
                    'is_lock_name' => getIsLock($redisVal->isLock),//状态
                    'is_lock' => $redisVal->isLock,//状态
                    'add_name' => getAdmName($redisVal->addCode),
                    'add_time' => $redisVal->addTime,
                    'up_name' => getAdmName($redisVal->upCode),
                    'up_time' => $redisVal->upTime,
                ];
            }
            //
            $data = [];
            $data['code'] = 0;
            $data['msg'] = '查询成功';
            $data['data'] = $dbData;
            $data['count'] = 0;
            return $data;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        //
        $data = [];
        $data['id'] = '';
        $data['area_code'] = '';
        $data['is_default'] = '';
        $data['area_code_value'] = '';
        return view('.sys.pages.oftenInformation.addressEdit', ['db' => $data]);
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
        $data = [];
        $data['name'] = $inp['name'];
        $data['mobile'] = $inp['mobile'];
        $data['provinceAndCity'] = getPubProCit($inp['area_code'])->title;
        $data['addr'] = $inp['addr'];
        $data['postcode'] = $inp['postcode'];
        $data['isDefault'] = $inp['is_default'];
        $data['areaCode'] = $inp['area_code'];
        $data['bySort'] = 0;
        $data['isLock'] = 0;

        if ($data) {
            $apiWebRes = callWebApi($data, site()['callWebApi'] . 'api/admUserAddr/add');//调用接口
            if ($apiWebRes->code == 200) {
                //opLog('address', [['type' => '添加', 'this_id' => $db->id, 'content' => json_encode($inp)]]);//记录日志
                return getSuccess(1);
            } else {
                return getSuccess(2);
            }
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
        $redisVal = json_decode(Redis::get('address:' . $id));
        $data = [];
        $data['id'] = $redisVal->id;
        $data['area_code'] = getPubProCit($redisVal->areaCode ?? '')->code;
        $data['is_default'] = $redisVal->isDefault;
        $data['area_code_value'] = $redisVal->areaCode;
        return view('.sys.pages.oftenInformation.addressEdit', ['db' => $data]);
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
        $redisVal = json_decode(Redis::get('address:' . $id));
        $data = [];
        $data['id'] = $id;
        $data['name'] = $inp['name'];
        $data['mobile'] = $inp['mobile'];
        $data['provinceAndCity'] = getPubProCit($inp['area_code'])->title;
        $data['addr'] = $inp['addr'];
        $data['postcode'] = $inp['postcode'];
        $data['isDefault'] = $inp['is_default'];
        $data['areaCode'] = $inp['area_code'];
        $data['bySort'] = $redisVal->bySort;
        $data['isLock'] = $redisVal->isLock;
        if ($data) {
            $apiWebRes = callWebApi($data, site()['callWebApi'] . 'api/admUserAddr/update');//调用接口
            if ($apiWebRes->code == 200) {
                //opLog('address', [['type' => '添加', 'this_id' => $db->id, 'content' => json_encode($inp)]]);//记录日志
                return getSuccess(1);
            } else {
                return getSuccess(2);
            }
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

    public function del(Request $request)
    {
        //
        $inp = $request->all();
        setDel('adm_user_addr', $inp['id']);
        return getSuccess(1);
    }

    public function start(Request $request)
    {
        $inp = $request->all();
        setNoLock('adm_user_addr', $inp['id']);
        return getSuccess(1);
    }

    public function stop(Request $request)
    {
        $inp = $request->all();
        setLock('adm_user_addr', $inp['id']);
        return getSuccess(1);
    }

    public function tableEdit(Request $request)
    {
        $inp = $request->all();
        $res = setTableEdit('adm_user_addr', $inp['id'], $inp['field'], $inp['value'], 0);
        if ($res > 0) {
            return getSuccess(1);
        } else {
            return getSuccess($res);
        }
    }
}
