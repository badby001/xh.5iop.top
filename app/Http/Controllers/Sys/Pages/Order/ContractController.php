<?php

namespace App\Http\Controllers\Sys\Pages\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('.sys.pages.order.allContract');
    }

    //电子合同汇总
    public function allRead(Request $request)
    {
        $inp = $request->all();

        $where = [];
        $where['pageNum'] = $inp['page'];
        $where['pageSize'] = $inp['limit'];
        $where['status'] = $inp['status'] ?? '';
        $apiWebRes = callWebApi($where, site()['callWebApi'] . 'api/electronicContract/selectContracts');//调用接口
        if ($apiWebRes->code == 200) {
            //生成redis缓存
            $redisArr = [];
            foreach ($apiWebRes->data->list as $k => $v) {
                $redisArr['electronic_contract:' . $v->id] = json_encode($v);//redis不存在,获取数据库
            }
            Redis::mset($redisArr);//提交缓存
            //读取缓存
            $dbData = [];
            foreach ($redisArr as $k => $v) {
                $this_id = json_decode($v)->id;//当前id
                $redisVal = json_decode(Redis::get('electronic_contract:' . $this_id));//读取缓存
                $planId = getDbData('Line_Plan_Ord', ['id' => $redisVal->ordId, 'erpid' => 895], 'planId', 1)[0];//用于作废的时候需要使用团id
                $orderRedisVal = json_decode(Redis::get('line_plan_ord:' . $redisVal->ordId));//读取缓存
                $noPingAmount = $orderRedisVal->amount - $orderRedisVal->pingAmount;
                $planDate = formatDate('', '', $orderRedisVal->planDate);
                $dbData[] = [
                    "id" => $redisVal->id, //17923,//这条数据的id
                    "fileUrl" => $redisVal->fileUrl,    //电子合同的下载地址，发起之后才会有
                    "contractId" => $redisVal->contractId,    //电子合同id
                    "isResend" => $redisVal->isResend,    //0,
                    "initiator" => $redisVal->initiator,    //null,
                    "destination" => $redisVal->destination,    //null,
                    "cusMoblie" => $redisVal->cusMoblie,    //游客手机
                    "source" => $redisVal->source,    //电子合同的来源
                    "saleName" => $redisVal->saleName,    //销售名称
                    "contractCpy" => $redisVal->contractCpy, //3,//提供电子合同的企业:1金棕榈,2领签,3全国统一旅游电子合同
                    "verifyStatus" => $redisVal->verifyStatus,    //0
                    "contractNo" => $redisVal->contractNo,    //电子合同编号
                    "saleId" => $redisVal->saleId, //0,//销售id
                    "signTime" => $redisVal->signTime,    //null,//签署时间
                    "addTime" => $redisVal->addTime,    //null,//签署时间
                    "totalPrice" => $redisVal->totalPrice,    //1,//总价
                    "userIds" => $redisVal->userIds,    //签署合同游客的id
                    "identifyCode" => $redisVal->identifyCode,    //游客证件号码
                    "verifyDate" => $redisVal->verifyDate,    //null,//
                    "ordId" => $redisVal->ordId,    //209864,//订单id
                    "cusName" => $redisVal->cusName,    //游客姓名
                    "qrCode" => $redisVal->qrCode,    //是签署电子合同的二维码
                    "initiatorId" => $redisVal->initiatorId,    //null,
                    "files" => $redisVal->files,    //null,
                    "erpId" => $redisVal->erpId,    //895,
                    "status" => $redisVal->status,    //1//合同状态1签署中2已签署3已取消
                    "statusShow" => $redisVal->statusShow,
                    "userNames" => $redisVal->userNames,
                    "others" => $redisVal->others,
                    "transactorName" => $redisVal->transactorName,
                    "transactorPhone" => $redisVal->transactorPhone,
                    "planId" => $planId,
                    "planDate" => $planDate,
                    "lineTitle" => $orderRedisVal->lineTitle ?? '',
                    "amount" => $orderRedisVal->amount ?? 0,
                    "pingAmount" => $orderRedisVal->pingAmount ?? 0,//收款总额
                    "noPingAmount" => $noPingAmount,
                ];
            }
        } else {
            return getSuccess($apiWebRes->msg);
        }
        $data = [];
        $data['code'] = 0;
        $data['msg'] = '查询成功';
        $data['data'] = $dbData;
        $data['count'] = $apiWebRes->data->total;
        return $data;
    }

    //电子合同列表
    public function read(Request $request)
    {
        $inp = $request->all();

        $where = [];
        $where['pageNum'] = $inp['page'];
        $where['pageSize'] = $inp['limit'];
        $where['orderId'] = $inp['orderId'];
        $where['status'] = $inp['status'] ?? '';

        $apiWebRes = callWebApi($where, site()['callWebApi'] . 'api/electronicContract/selectContracts');//调用接口
        if ($apiWebRes->code == 200) {
            //生成redis缓存
            $redisArr = [];
            foreach ($apiWebRes->data->list as $k => $v) {
                $redisArr['electronic_contract:' . $v->id] = json_encode($v);//redis不存在,获取数据库
            }
            Redis::mset($redisArr);//提交缓存
            //读取缓存
            $dbData = [];
            foreach ($redisArr as $k => $v) {
                $this_id = json_decode($v)->id;//当前id
                $redisVal = json_decode(Redis::get('electronic_contract:' . $this_id));//读取缓存
                $redisOrderVal = json_decode(Redis::get('line_plan_ordInfo:' . $redisVal->ordId));//读取缓存
                $dbData[] = [
                    "id" => $redisVal->id, //17923,//这条数据的id
                    "fileUrl" => $redisVal->fileUrl,    //电子合同的下载地址，发起之后才会有
                    "contractId" => $redisVal->contractId,    //电子合同id
                    "isResend" => $redisVal->isResend,    //0,
                    "initiator" => $redisVal->initiator,    //null,
                    "destination" => $redisVal->destination,    //null,
                    "cusMoblie" => $redisVal->cusMoblie,    //游客手机
                    "source" => $redisVal->source,    //电子合同的来源
                    "saleName" => $redisVal->saleName,    //销售名称
                    "contractCpy" => $redisVal->contractCpy, //3,//提供电子合同的企业:1金棕榈,2领签,3全国统一旅游电子合同
                    "verifyStatus" => $redisVal->verifyStatus,    //0
                    "contractNo" => $redisVal->contractNo,    //电子合同编号
                    "saleId" => $redisVal->saleId, //0,//销售id
                    "signTime" => $redisVal->signTime,    //null,//签署时间
                    "addTime" => $redisVal->addTime,    //null,//签署时间
                    "totalPrice" => $redisVal->totalPrice,    //1,//总价
                    "userIds" => $redisVal->userIds,    //签署合同游客的id
                    "identifyCode" => $redisVal->identifyCode,    //游客证件号码
                    "verifyDate" => $redisVal->verifyDate,    //null,//
                    "ordId" => $redisVal->ordId,    //209864,//订单id
                    "cusName" => $redisVal->cusName,    //游客姓名
                    "qrCode" => $redisVal->qrCode,    //是签署电子合同的二维码
                    "initiatorId" => $redisVal->initiatorId,    //null,
                    "files" => $redisVal->files,    //null,
                    "erpId" => $redisVal->erpId,    //895,
                    "status" => $redisVal->status,    //1//合同状态1签署中2已签署3已取消
                    "statusShow" => $redisVal->statusShow,
                    "userNames" => $redisVal->userNames,
                    "others" => $redisVal->others,
                    "transactorName" => $redisVal->transactorName,
                    "transactorPhone" => $redisVal->transactorPhone,
                    "planId" => $redisOrderVal->planId,
                ];
            }
        } else {
            return getSuccess($apiWebRes->msg);
        }
        $data = [];
        $data['code'] = 0;
        $data['msg'] = '查询成功';
        $data['data'] = $dbData;
        $data['count'] = $apiWebRes->data->total;
        return $data;
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
        //
        $inp = $request->all();
        if (!$inp['select']) {
            return getSuccess('请至少选择一名游客');
        }
        $fuser = getInjoin($inp['select'])[0];
        $db = getDbData('Line_Plan_Ord_User', ['id' => $fuser, 'erpid' => 895], 'cnName,ctInfo', 1)[0];
        $inp['loginName'] = _admOpenId();
        $inp['transactorName'] = _admName();
        $inp['transactorPhone'] = _admOpenId();
        $inp['nextStep'] = 0;
        $inp['userIds'] = $inp['select'];
        $inp['fuser'] = $fuser;
        $inp['traveler'] = $db->cnName;
        $inp['travelmobile'] = $db->ctInfo;
        if ($inp) {
            $apiWebRes = callWebApi($inp, site()['callWebApi'] . 'api/electronicContract/commit');//调用接口
            if ($apiWebRes->code == 200) {
                // opLog('electronic_contract', [['type' => '添加', 'this_id' => $apiWebRes->data->id, 'content' => json_encode($inp)]]);//记录日志
                return getSuccess(1);
            } else {
                return getSuccess($apiWebRes->msg);
            }
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
        //读取缓存
        $dbData = [];
        $redisVal = json_decode(Redis::get('line_plan_ordInfo:' . $id));//读取缓存
        $linePlanOrdUserApisData = [];
        foreach ($redisVal->linePlanOrdUserApis as $vv) {
            if ($vv->cnName && $vv->isLock == 1 && $vv->contractState == 0) {
                $linePlanOrdUserApisData[] = [
                    'name' => $vv->cnName,
                    'value' => $vv->id,
                    'isAdult' => $vv->isAdult,
                ];
            }
        }
        $dbData = [
            "id" => $redisVal->id,//订单号
            "amount" => $redisVal->amount,//订单总额
            "ordBak" => $redisVal->ordBak,//订单备注
            "aduNum" => $redisVal->aduNum,//成人数
            "aduNum2" => $redisVal->aduNum2,//老人数
            "aduNum1" => $redisVal->aduNum1,//小青年
            "chdNum" => $redisVal->chdNum,//儿童数占床
            "chdNum1" => $redisVal->chdNum1,//儿童数不占床
            "ctName" => $redisVal->ctName,//联系人
            "ctInfo" => $redisVal->ctInfo,//联系电话
            "linePlanOrdUserApis" => json_encode($linePlanOrdUserApisData),//名单列表
            "adultPrice" => $redisVal->adultPrice,
            "childPrice" => $redisVal->childPrice,
            "minPlanNum" => $redisVal->minPlanNum,
            "addTime" => $redisVal->addTime,
            "backDate" => $redisVal->backDate,
        ];
        //
        $data = [];
        $data['id'] = '';  // "209864",//订单id
        $data['orderId'] = $dbData['id'];  // "209864",//订单id
        $data['aduAmount'] = $dbData['adultPrice'];   // "1.00",//成人价
        $data['childAmount'] = $dbData['childPrice'];   // "1.00",//小童价
        $data['loginName'] = _admOpenId();   // "123",//微站登陆账号的名称
        $data['amountAll'] = $dbData['amount'];   // "1.00",//总价
        $data['userIds'] = '';   // "517612,517613",//订单中游客的id，多个用逗号隔开
        $data['fuser'] = '';  // "517612",//游客代表id，就是其中一个游客的id
        $data['traveler'] = '';  // "玄烨",//游客代表姓名
        $data['travelmobile'] = '';   // "13242405532",//游客代表电话
        $data['transactorName'] = _admName();  // "123",//经办人姓名，这里取登陆账号的姓名
        $data['transactorPhone'] = _admOpenId();   // "13679375666",//经办人电话，这里去登录人的电话
        $data['other'] = '';  // "",//其他约定
        $data['payType'] = 1;  // "1",//支付类别：1是现金，其他的我也不知道还设有啥类型
        $data['payTime'] = '';   // "2020-06-05",//支付时间
        $data['nextStep'] = 0;  // "0"//0添加1发起在线签署
        $data['orderAddTime'] = $dbData['addTime'];
        $data['backDate'] = $dbData['backDate'];
        $data['minPlanNum'] = $dbData['minPlanNum'];
        $data['linePlanOrdUserApis'] = $dbData['linePlanOrdUserApis'];
        //
        $data['adultNum'] = 0;
        $data['childNum'] = 0;
        $data['aduAmount'] = $redisVal->linePlanApi->sadultPrice ?? 0;
        $data['childAmount'] = $redisVal->linePlanApi->schildPrice ?? 0;
        $data['amountAll'] = 0;  // "1.00",//总价
        //
        $data['sadultPrice'] = $redisVal->linePlanApi->sadultPrice ?? 0;
        $data['schildPrice'] = $redisVal->linePlanApi->schildPrice ?? 0;
        $data['adultPrice'] = $redisVal->linePlanApi->adultPrice ?? 0;
        $data['childPrice'] = $redisVal->linePlanApi->childPrice ?? 0;
        return view('sys.pages.order.eContarctEdit', ['db' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $where = [];
        $where['id'] = $id;
        //
        $apiWebRes = callWebApi($where, site()['callWebApi'] . 'api/electronicContract/selectContractDetail');//调用接口
        if ($apiWebRes->code == 200) {
            $dataVal = $apiWebRes->data->electronicContractDetail;
            $plan = $apiWebRes->data->plan;
            $supplementaryClause = $dataVal->other;
            $payTime = $dataVal->payTime;
            //读取缓存
            $db = getDbData('electronic_contract', ['erpid' => 895, 'id' => $id], 'ordId', 1)[0];
            $dbData = [];
            $redisVal = json_decode(Redis::get('line_plan_ordInfo:' . $db->ordId));//读取缓存
            $linePlanOrdUserApisData = [];
            foreach ($redisVal->linePlanOrdUserApis as $vv) {
                if ($vv->cnName && $vv->isLock == 1 && $vv->contractState == 0) {
                    $linePlanOrdUserApisData[] = [
                        'name' => $vv->cnName,
                        'value' => $vv->id,
                        'isAdult' => $vv->isAdult,
                    ];
                }
            }
            $dbData = [
                "linePlanOrdUserApis" => json_encode($linePlanOrdUserApisData),//名单列表
                "minPlanNum" => $redisVal->minPlanNum,
                "addTime" => $redisVal->addTime,
                "backDate" => $redisVal->backDate,
            ];
            //
            $data = [];
            $data['id'] = $id;
            $data['orderId'] = $db->ordId;  // "209864",//订单id
            $data['loginName'] = _admOpenId();   // "123",//微站登陆账号的名称
            $data['userIds'] = $dataVal->choosedUserIds;   // "517612,517613",//订单中游客的id，多个用逗号隔开
            $data['fuser'] = $dataVal->travelerId;  // "517612",//游客代表id，就是其中一个游客的id
            $data['traveler'] = $dataVal->traveler;  // "玄烨",//游客代表姓名
            $data['travelmobile'] = $dataVal->travelmobile;   // "13242405532",//游客代表电话
            $data['transactorName'] = $dataVal->transactorName;   // "123",//经办人姓名，这里取登陆账号的姓名
            $data['transactorPhone'] = $dataVal->transactorPhone;   // "13679375666",//经办人电话，这里去登录人的电话
            $data['other'] = $supplementaryClause;  // "",//其他约定
            $data['payType'] = $dataVal->payType;   // "1",//支付类别：1是现金，其他的我也不知道还设有啥类型
            $data['payTime'] = $payTime;   // "2020-06-05",//支付时间
            $data['nextStep'] = 0;  // "0"//0添加1发起在线签署
            $data['orderAddTime'] = $dbData['addTime'];
            $data['backDate'] = $dbData['backDate'];
            $data['minPlanNum'] = $dbData['minPlanNum'];
            $data['linePlanOrdUserApis'] = $dbData['linePlanOrdUserApis'];
            //
            $data['adultNum'] = $dataVal->adultNum ?? 0;
            $data['childNum'] = $dataVal->childNum ?? 0;
            $data['aduAmount'] = $dataVal->aduAmount ?? 0;   // "1.00",//成人价
            $data['childAmount'] = $dataVal->childAmount ?? 0;   // "1.00",//小童价
            $data['amountAll'] = $dataVal->amountAll ?? 0;  // "1.00",//总价
            //
            $data['sadultPrice'] = $plan->sadultPrice ?? 0;
            $data['schildPrice'] = $plan->schildPrice ?? 0;
            $data['adultPrice'] = $plan->adultPrice ?? 0;
            $data['childPrice'] = $plan->childPrice ?? 0;
            return view('sys.pages.order.eContarctEdit', ['db' => $data]);
        } else {
            return getSuccess($apiWebRes->msg);
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
        if (!$inp['select']) {
            return getSuccess('请至少选择一名游客');
        }
        $fuser = getInjoin($inp['select'])[0];
        $db = getDbData('Line_Plan_Ord_User', ['id' => $fuser, 'erpid' => 895], 'cnName,ctInfo', 1)[0];
        $inp['id'] = $id;
        $inp['loginName'] = _admOpenId();
        $inp['transactorName'] = _admName();
        $inp['transactorPhone'] = _admOpenId();
        $inp['nextStep'] = 0;
        $inp['userIds'] = $inp['select'];
        $inp['fuser'] = $fuser;
        $inp['traveler'] = $db->cnName;
        $inp['travelmobile'] = $db->ctInfo;
        if ($inp) {
            $apiWebRes = callWebApi($inp, site()['callWebApi'] . 'api/electronicContract/commit');//调用接口
            if ($apiWebRes->code == 200) {
                //opLog('electronic_contract', [['type' => '修改', 'this_id' => $id, 'content' => json_encode($inp)]]);//记录日志
                return getSuccess(1);
            }
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

    public function sign(Request $request)
    {
        //
        $inp = $request->all();
        $apiWebRes = callWebApi($inp, site()['callWebApi'] . 'api/electronicContract/signContract');//调用接口
        if ($apiWebRes->code == 200) {
            //opLog('electronic_contract', [['type' => '发起签署', 'this_id' => $inp['id'], 'content' => json_encode($inp)]]);//记录日志
            return getSuccess(1);
        } else {
            return getSuccess($apiWebRes->msg);
        }
    }

    public function signSms(Request $request)
    {
        //
        $inp = $request->all();
        $apiWebRes = callWebApi($inp, site()['callWebApi'] . 'api/electronicContract/sendSignMsg');//调用接口
        if ($apiWebRes->code == 200) {
            // opLog('electronic_contract', [['type' => '发送签署短信', 'this_id' => $inp['id'], 'content' => json_encode($inp)]]);//记录日志
            return getSuccess(1);
        } else {
            return getSuccess($apiWebRes->msg);
        }
    }

    public function signCancel(Request $request)
    {
        //
        $inp = $request->all();
        $apiWebRes = callWebApi($inp, site()['callWebApi'] . 'api/electronicContract/cancelContract');//调用接口
        if ($apiWebRes->code == 200) {
            // opLog('electronic_contract', [['type' => '作废合同', 'this_id' => $inp['id'], 'content' => json_encode($inp)]]);//记录日志
            return getSuccess(1);
        } else {
            return getSuccess($apiWebRes->msg);
        }
    }

    public function signDelete(Request $request)
    {
        //
        $inp = $request->all();
        $apiWebRes = callWebApi($inp, site()['callWebApi'] . 'api/electronicContract/deleteContract');//调用接口
        if ($apiWebRes->code == 200) {
            //opLog('electronic_contract', [['type' => '删除合同', 'this_id' => $inp['id'], 'content' => json_encode($inp)]]);//记录日志
            return getSuccess(1);
        } else {
            return getSuccess($apiWebRes->msg);
        }
    }
}
