<?php

namespace App\Http\Controllers\Sys\Pages\Order;

use App\Http\Controllers\Controller;
use App\Model\Pages\XQERPV3\LinePlan;
use App\Model\Pages\XQERPV3\LinePlanOrdUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('.sys.pages.order.ordList');
    }


    public function read(Request $request)
    {
        $inp = $request->all();

        $where = [];
        $where['pageNum'] = $inp['page'];
        $where['pageSize'] = $inp['limit'];
        $where['status'] = $inp['status'] ?? 0;
        $where['classify'] = 0;
        if (isset($inp['key'])) {
            $where['key'] = $inp['key'];
        }
        if (isset($inp['dateType'])) {
            if ($inp['dateType'] == 'addTime') {
                if (isset($inp['start_time']) && isset($inp['end_time'])) {
                    $where['addTimeStart'] = $inp['start_time'];
                    $where['addTimeEnd'] = $inp['end_time'];
                }
            } else if ($inp['dateType'] == 'planTime') {
                if (isset($inp['start_time']) && isset($inp['end_time'])) {
                    $where['planDateStart'] = $inp['start_time'];
                    $where['planDateEnd'] = $inp['end_time'];
                }
            }
        }
        $apiWebRes = callWebApi($where, site()['callWebApi'] . 'api/order/selectOrders');//调用接口
        if ($apiWebRes->code == 200) {
            //生成redis缓存
            $redisArr = [];
            foreach ($apiWebRes->data->list as $k => $v) {
                $redisArr['line_plan_ord:' . $v->id] = json_encode($v);//redis不存在,获取数据库
            }
            Redis::mset($redisArr);//提交缓存
            //读取缓存
            $dbData = [];
            foreach ($redisArr as $k => $v) {
                $this_id = json_decode($v)->id;//当前id
                $redisVal = json_decode(Redis::get('line_plan_ord:' . $this_id));//读取缓存
                //
                $amount = $redisVal->amount;
                if ($amount == 0) {
                    $_pingAmount = $redisVal->pingAmount;
                    $noPingAmount = $amount - $redisVal->pingAmount;
                    $_noPingAmount = 0;
                } else {
                    $_pingAmount = ($redisVal->pingAmount / $amount) * 100;
                    $noPingAmount = $amount - $redisVal->pingAmount;
                    $_noPingAmount = $noPingAmount / $amount * 100;
                }
                $aduNum = $redisVal->aduNum;//成人数
                $aduNum1 = $redisVal->aduNum1;//小青年
                $aduNum2 = $redisVal->aduNum2;//老人数
                $chdNum = $redisVal->chdNum;//儿童数占床
                $chdNum1 = $redisVal->chdNum1;//儿童数不占床
                //
                //团期信息
                $linPlanDB = LinePlan::where(['erpid' => 895, 'isDel' => 0, 'id' => $redisVal->planId])->select('adultPrice', 'adultPrice1', 'adultPrice2', 'childPrice', 'childPrice1', 'sadultPrice', 'sadultPrice1', 'sadultPrice2', 'schildPrice', 'schildPrice1')->get()[0];
                $adultPrice = $linPlanDB->adultPrice;//成人同行价
                $adultPrice1 = $linPlanDB->adultPrice1;//小青年同行价
                $adultPrice2 = $linPlanDB->adultPrice2;//老人同行价
                $childPrice = $linPlanDB->childPrice;//小童占床同行价
                $childPrice1 = $linPlanDB->childPrice1;//小童不占床同行价
                //
                $sadultPrice = $linPlanDB->sadultPrice;//成人市场价
                $sadultPrice1 = $linPlanDB->sadultPrice1;//小青年市场价
                $sadultPrice2 = $linPlanDB->sadultPrice2;//老人市场价
                $schildPrice = $linPlanDB->schildPrice;//小童占床市场价
                $schildPrice1 = $linPlanDB->schildPrice1;//小童不占床市场价

                $spayablePingAmount = $aduNum * $sadultPrice + $aduNum1 * $sadultPrice1 + $aduNum2 * $sadultPrice2 + $chdNum * $schildPrice + $chdNum1 * $schildPrice1;
                //
                $endTime = $redisVal->endTime;
                $dbData[] = [
                    "id" => $redisVal->id,//订单号
                    "isOk" => $redisVal->isOk,//状态
                    "statusShow" => getStatusShow($redisVal->statusShow),//状态
                    "ordTypeName" => $redisVal->ordTypeName,//订单类型
                    "lineId" => $redisVal->lineId,//线路id
                    "lineTitle" => $redisVal->lineTitle,//线路名称
                    "planId" => $redisVal->planId,//团id
                    "planDate" => formatDate('', '', $redisVal->planDate),//出团日期
                    "backDate" => formatDate('', '', $redisVal->backDate),//回团日期
                    "amount" => $redisVal->amount,//订单总额
                    "pingAmount_show" => "<div class=\"layui-progress\" style=\"top: 8px\"><div class=\"layui-progress-bar\" lay-percent=\"$redisVal->pingAmount%\" style=\"width: $_pingAmount%;\"></div></div>" . $redisVal->pingAmount,
                    "noPingAmount_show" => "<div class=\"layui-progress\" style=\"top: 8px\"><div class=\"layui-progress-bar layui-bg-red\" lay-percent=\"$noPingAmount\" style=\"width: $_noPingAmount%;\"></div></div>" . $noPingAmount,
                    "pingAmount" => $redisVal->pingAmount,//收款总额
                    "noPingAmount" => $noPingAmount,//未收总额
                    "ordBak" => $redisVal->ordBak,//订单备注
                    "perNum" => $redisVal->perNum,//总人数
                    "aduNum" => $aduNum,//成人数
                    "aduNum1" => $aduNum1,//小青年
                    "aduNum2" => $aduNum2,//老人数
                    "chdNum" => $chdNum,//儿童数占床
                    "chdNum1" => $chdNum1,//儿童数不占床
                    "ctName" => $redisVal->ctName,//联系人
                    "ctInfo" => $redisVal->ctInfo,//联系电话
//                    "linePlanOrdBillApis" => $redisVal->linePlanOrdBillApis,//账单列表
//                    "linePlanOrdUserApis" => $redisVal->linePlanOrdUserApis,//名单列表
                    "priceTitle" => $redisVal->priceTitle,//套餐名称
                    "pubFromcityName" => $redisVal->pubFromcityName,//联运城市
                    "fromCityName" => $redisVal->fromCityName,//出发城市
                    "cpyId" => $redisVal->cpyId,//供应商
                    "supCpyName" => getSupName($redisVal->supCpyName),//供应商
                    "supCpyMobile" => $redisVal->supCpyMobile,//供应商联系电话
                    "saleId" => $redisVal->saleId,//销售id
                    "saleName" => $redisVal->saleName,//销售名称
                    "addTime" => $redisVal->addTime,//下单时间
                    "endTime" => $endTime > 0 ? "<span class=\"red\">" . $endTime . "</span>" : "已过期",//过期时间
                    "payablePingAmount_show" => $redisVal->amount,//应付总额
                    "spayablePingAmount_show" => $spayablePingAmount,//市场价应付总额
                    "profit" => round($spayablePingAmount - $redisVal->amount, 2),//利润
                    "admIsVip" => _admIsVip(),
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
        $where = [];
        $where['orderId'] = $id;
        $apiWebRes = callWebApi($where, site()['callWebApi'] . 'api/order/selectOrderDetail');//调用接口
        if ($apiWebRes->code == 200) {
            //生成redis缓存
            $redisArr = [];
            $redisArr['line_plan_ordInfo:' . $apiWebRes->data->id] = json_encode($apiWebRes->data);//redis不存在,获取数据库
            Redis::mset($redisArr);//提交缓存
            //读取缓存
            $dbData = [];
            foreach ($redisArr as $k => $v) {
                $this_id = json_decode($v)->id;//当前id
                $redisVal = json_decode(Redis::get('line_plan_ordInfo:' . $this_id));//读取缓存
                $invoicing_time = formatDate('timeCut', getTime(2), formatDate('', '', $redisVal->planDate)) / 86400;
                $linePlanOrdUserApisData = [];
                foreach ($redisVal->linePlanOrdUserApis as $vv) {
                    $linePlanOrdUserApisData[] = [
                        "id" => $vv->id,
                        "isLock" => $vv->isLock,
                        "isLock_name" => getExamine($vv->isLock),
                        "cnName" => $vv->cnName,
                        "enName1" => $vv->enName1,
                        "enName2" => $vv->enName2,
                        "nation" => $vv->nation,
                        "sex" => $vv->sex,
                        "perType" => $vv->perType,
                        "birth" => $vv->birth,
                        "pob" => $vv->pob,
                        "idType" => $vv->idType,
                        "idType_name" => getIdType($vv->idType),
                        "idName" => $vv->idName,
                        "passport" => $vv->passport,
                        "doi" => $vv->doi,
                        "doe" => $vv->doe,
                        "poi" => $vv->poi,
                        "idCard" => $vv->idCard,
                        "ctInfo" => $vv->ctInfo,
                        "address" => $vv->address,
                        "contractState" => $vv->contractState,
                        "remark" => $vv->remark,
                        "pobCode" => $vv->pobCode,
                        "poiCode" => $vv->poiCode,
                        "img" => $vv->img,
                    ];
                }
                $noPingAmount = $redisVal->amount - $redisVal->pingAmount;
                $endTime = $redisVal->endTime;
                $dbData[] = [
                    "id" => $redisVal->id,//订单号
                    "isOk" => $redisVal->isOk,//状态
                    "statusShow" => getStatusShow($redisVal->statusShow),//状态
                    "ordTypeName" => $redisVal->ordTypeName,//订单类型
                    "lineId" => $redisVal->lineId,//线路id
                    "lineTitle" => $redisVal->lineTitle,//线路名称
                    "planId" => $redisVal->planId,//团id
                    "planDate" => formatDate('', '', $redisVal->planDate),//出团日期
                    "backDate" => formatDate('', '', $redisVal->backDate),//回团日期
                    "amount" => $redisVal->amount,//订单总额
                    "pingAmount" => $redisVal->pingAmount,//收款总额
                    "noPingAmount" => $noPingAmount,
                    "ordBak" => $redisVal->ordBak,//订单备注
                    "perNum" => $redisVal->perNum,//总人数
                    "aduNum" => $redisVal->aduNum,//成人数
                    "aduNum2" => $redisVal->aduNum2,//老人数
                    "aduNum1" => $redisVal->aduNum1,//小青年
                    "chdNum" => $redisVal->chdNum,//儿童数占床
                    "chdNum1" => $redisVal->chdNum1,//儿童数不占床
                    "ctName" => $redisVal->ctName,//联系人
                    "ctInfo" => $redisVal->ctInfo,//联系电话
                    "linePlanOrdBillApis" => json_encode($redisVal->linePlanOrdBillApis),//账单列表
                    "linePlanOrdUserApis" => json_encode($linePlanOrdUserApisData),//名单列表
                    "priceTitle" => $redisVal->priceTitle,//套餐名称
                    "pubFromcityName" => $redisVal->pubFromcityName,//联运城市
                    "fromCityName" => $redisVal->fromCityName,//出发城市
                    "cpyId" => $redisVal->cpyId,//供应商
                    "supCpyName" => getSupName($redisVal->supCpyName),//供应商
                    "supCpyMobile" => $redisVal->supCpyMobile,//供应商联系电话
                    "saleId" => $redisVal->saleId,//销售id
                    "saleName" => $redisVal->saleName,//销售名称
                    "addTime" => $redisVal->addTime,//下单时间
                    "endTime" => $endTime > 0 ? "<span class=\"red\">支付终止时间: " . $endTime . "</span>" : "已过期",//过期时间
                    "childPrice" => $redisVal->childPrice,
                    "childPrice1" => $redisVal->childPrice1,
                    "adultPrice" => $redisVal->adultPrice,
                    "adultPrice1" => $redisVal->adultPrice1,
                    "adultPrice2" => $redisVal->adultPrice2,
                    "minPlanNum" => $redisVal->minPlanNum,
                    "invoicing_time" => $invoicing_time,
                    "classify" => $redisVal->classify,
                ];
            }
        } else {
            return getSuccess($apiWebRes->msg);
        }
        return view('.sys.pages.order.orderInfo', ['db' => $dbData[0]]);
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

    public function cancel(Request $request)
    {
        //
        $inp = $request->all();
        $where['id'] = $inp['id'];
        $apiWebRes = callWebApi($where, site()['callWebApi'] . 'api/order/cancelOrd');//调用接口
        if ($apiWebRes->code == 200) {
            //opLog('line_plan_ord', [['type' => '取消订单', 'this_id' => $inp['id'], 'content' => json_encode($inp)]]);//记录日志
            return getSuccess(1);
        } else {
            return getSuccess($apiWebRes->msg);
        }
    }


    public function pay($id, $amount)
    {
        $orderId = $id;//获取订单号
        $where['id'] = $orderId;
        $apiWebRes = callWebApi($where, site()['callWebApi'] . 'api/order/selectPayConfig');//调用接口
        if ($apiWebRes->code == 200) {
            //通过订单号获取当前订单总金额,已付金额和未付金额
            $redisVal = json_decode(Redis::get('line_plan_ord:' . $orderId));//读取缓存
            $pingAmount = $redisVal->pingAmount;//已付金额
            $noPingAmount = $redisVal->amount - $pingAmount;//未付金额
            $dingPrice = $apiWebRes->data->dingPrice || 0;//定金
            $classify = $redisVal->classify;//产品分类
            //
            $payNum = $apiWebRes->data->payNum + 1;//支付次数
            $pay1 = $apiWebRes->data->pay1;//第一次支付
            $pay2 = $apiWebRes->data->pay2;//第二次支付
            $pay3 = $apiWebRes->data->pay3;//第三次支付
            if ($payNum == 1) {
                if ($pay1 == 0) {
                    if ($dingPrice == 0) {//第一次支付时,如果订单为0则根据全款支付
                        $amount = $noPingAmount;
                    } else {
                        $amount = $redisVal->perNum * $dingPrice;  //人数*定金
                    }
                } else {
                    $amount = $noPingAmount * ($pay1 * 0.01);
                }
            } else if ($payNum == 2) {
                $amount = $redisVal->amount * ($pay2 * 0.01) - $pingAmount;
            } else if ($payNum == 3) {
                if ($pay3 == 0) {
                    $amount = $noPingAmount;
                } else {
                    $amount = $noPingAmount * 0.10;
                }
            } else {
                $amount = $noPingAmount * 0.10;
            }
            //如果支付的金额为0,则根据未付金额的10%进行支付
            $amount == 0 ? $amount = $noPingAmount * 0.10 : $amount = $amount;
            if ($classify == 1) {//商品类型的产品一次性付清
                $amount = $noPingAmount;
            }
            //
            $wherePay['linePlanOrdId'] = $orderId;
            $wherePay['amount'] = $amount;
            $apiWebPayRes = callWebApi($wherePay, site()['callWebApi'] . 'api/pay/getPaycode');//调用接口
            $data = [];
            $data['qrcodeKey'] = "";
            if ($apiWebPayRes->code == 200) {
                //$data['payConfig'] = $apiWebRes->data;
                $data['code'] = $apiWebPayRes->code;
                $data['msg'] = $apiWebPayRes->msg;
                $data['amount'] = $amount;
                $data['appStoreName'] = $apiWebPayRes->data->appStoreName;
                $data['orderId'] = $orderId;
                $data['qrcodeKey'] = $apiWebPayRes->data->qrcodeKey;
                $data['abstract'] = '订单总额:' . $redisVal->amount . '、已付金额:' . $pingAmount . '<br>剩余付款:' . $noPingAmount . '、本次付款:' . $amount;
                //opLog('line_plan_ord', [['type' => '支付', 'this_id' => $orderId, 'content' => json_encode($data)]]);//记录日志
            } else {
                $data['code'] = $apiWebPayRes->code;
                $data['msg'] = $apiWebPayRes->msg;
            }
            return view('.sys.pages.order.orderPay', ['db' => $data]);
        } else {
            return getSuccess($apiWebRes->msg);
        }

    }

    public function tableEdit(Request $request)
    {
        $inp = $request->all();
        $res = setTableEdit($inp['tableName'], $inp['id'], $inp['field'], $inp['value'], 1);
        if ($res > 0) {
            return getSuccess(1);
        } else {
            return getSuccess($res);
        }
    }

    //获取名单详情
    public function orderUserInfoEdit($id)
    {
        $data = [];
        $data['id'] = $id;
        return view('.sys.pages.order.orderUserInfoEdit', ['db' => $data]);
    }

    public function orderUserInfoEditUp(Request $request)
    {
        $inp = $request->all();
        $db = LinePlanOrdUser::find($inp['id']);
        $db['cnName'] = $inp['cnName'];
        $db['enName1'] = $inp['enName1'];
        $db['enName2'] = $inp['enName2'];
        $db['nation'] = $inp['nation'];
        $db['sex'] = $inp['sex'];
        $db['perType'] = $inp['perType'];
        $db['birth'] = $inp['birth'];
        $db['pob'] = $inp['pob'];
        $db['idType'] = $inp['idType'];
        $db['passport'] = $inp['passport'];
        $db['doi'] = $inp['doi'];
        $db['doe'] = $inp['doe'];
        $db['poi'] = $inp['poi'];
        $db['idCard'] = $inp['idCard'];
        $db['ctInfo'] = $inp['ctInfo'];
        $db['address'] = $inp['address'];
        $db['remark'] = $inp['remark'];
        if ($db->save()) {
            opLog('line_plan_ord_user', [['type' => '修改', 'this_id' => $inp['id'], 'content' => json_encode($inp)]]);//记录日志
            return getSuccess(1);
        } else {
            return getSuccess(2);
        }
    }

    public function ord1List()
    {
        return view('.sys.pages.order.ord1List');
    }


    public function order1Read(Request $request)
    {
        $inp = $request->all();

        $where = [];
        $where['pageNum'] = $inp['page'];
        $where['pageSize'] = $inp['limit'];
        $where['status'] = $inp['status'] ?? 0;
        $where['classify'] = 1;
        if (isset($inp['key'])) {
            $where['key'] = $inp['key'];
        }
        if (isset($inp['dateType'])) {
            if ($inp['dateType'] == 'addTime') {
                if (isset($inp['start_time']) && isset($inp['end_time'])) {
                    $where['addTimeStart'] = $inp['start_time'];
                    $where['addTimeEnd'] = $inp['end_time'];
                }
            }
        }
        $apiWebRes = callWebApi($where, site()['callWebApi'] . 'api/order/selectOrders');//调用接口
        if ($apiWebRes->code == 200) {
            //生成redis缓存
            $redisArr = [];
            foreach ($apiWebRes->data->list as $k => $v) {
                $redisArr['line_plan_ord:' . $v->id] = json_encode($v);//redis不存在,获取数据库
            }
            Redis::mset($redisArr);//提交缓存
            //读取缓存
            $dbData = [];
            foreach ($redisArr as $k => $v) {
                $this_id = json_decode($v)->id;//当前id
                $redisVal = json_decode(Redis::get('line_plan_ord:' . $this_id));//读取缓存
                //
                $amount = $redisVal->amount;
                if ($amount == 0) {
                    $_pingAmount = $redisVal->pingAmount;
                    $noPingAmount = $amount - $redisVal->pingAmount;
                    $_noPingAmount = 0;
                } else {
                    $_pingAmount = ($redisVal->pingAmount / $amount) * 100;
                    $noPingAmount = $amount - $redisVal->pingAmount;
                    $_noPingAmount = $noPingAmount / $amount * 100;
                }
                $aduNum = $redisVal->aduNum;//成人数
                $aduNum1 = $redisVal->aduNum1;//小青年
                $aduNum2 = $redisVal->aduNum2;//老人数
                $chdNum = $redisVal->chdNum;//儿童数占床
                $chdNum1 = $redisVal->chdNum1;//儿童数不占床
                //
                //团期信息
                $linPlanDB = LinePlan::where(['erpid' => 895, 'isDel' => 0, 'id' => $redisVal->planId])->select('adultPrice', 'adultPrice1', 'adultPrice2', 'childPrice', 'childPrice1', 'sadultPrice', 'sadultPrice1', 'sadultPrice2', 'schildPrice', 'schildPrice1')->get()[0];
                $adultPrice = $linPlanDB->adultPrice;//成人同行价
                $adultPrice1 = $linPlanDB->adultPrice1;//小青年同行价
                $adultPrice2 = $linPlanDB->adultPrice2;//老人同行价
                $childPrice = $linPlanDB->childPrice;//小童占床同行价
                $childPrice1 = $linPlanDB->childPrice1;//小童不占床同行价
                //
                $sadultPrice = $linPlanDB->sadultPrice;//成人市场价
                $sadultPrice1 = $linPlanDB->sadultPrice1;//小青年市场价
                $sadultPrice2 = $linPlanDB->sadultPrice2;//老人市场价
                $schildPrice = $linPlanDB->schildPrice;//小童占床市场价
                $schildPrice1 = $linPlanDB->schildPrice1;//小童不占床市场价

                $spayablePingAmount = $aduNum * $sadultPrice + $aduNum1 * $sadultPrice1 + $aduNum2 * $sadultPrice2 + $chdNum * $schildPrice + $chdNum1 * $schildPrice1;
                //
                $endTime = $redisVal->endTime;
                $dbData[] = [
                    "id" => $redisVal->id,//订单号
                    "isOk" => $redisVal->isOk,//状态
                    "statusShow" => getStatusShow($redisVal->statusShow),//状态
                    "ordTypeName" => $redisVal->ordTypeName,//订单类型
                    "lineId" => $redisVal->lineId,//线路id
                    "lineTitle" => $redisVal->lineTitle,//线路名称
                    "planId" => $redisVal->planId,//团id
                    "planDate" => formatDate('', '', $redisVal->planDate),//出团日期
                    "backDate" => formatDate('', '', $redisVal->backDate),//回团日期
                    "amount" => $redisVal->amount,//订单总额
                    "pingAmount_show" => "<div class=\"layui-progress\" style=\"top: 8px\"><div class=\"layui-progress-bar\" lay-percent=\"$redisVal->pingAmount%\" style=\"width: $_pingAmount%;\"></div></div>" . $redisVal->pingAmount,
                    "noPingAmount_show" => "<div class=\"layui-progress\" style=\"top: 8px\"><div class=\"layui-progress-bar layui-bg-red\" lay-percent=\"$noPingAmount\" style=\"width: $_noPingAmount%;\"></div></div>" . $noPingAmount,
                    "pingAmount" => $redisVal->pingAmount,//收款总额
                    "noPingAmount" => $noPingAmount,//未收总额
                    "ordBak" => $redisVal->ordBak,//订单备注
                    "perNum" => $redisVal->perNum,//总人数
                    "aduNum" => $aduNum,//成人数
                    "aduNum1" => $aduNum1,//小青年
                    "aduNum2" => $aduNum2,//老人数
                    "chdNum" => $chdNum,//儿童数占床
                    "chdNum1" => $chdNum1,//儿童数不占床
                    "ctName" => $redisVal->ctName,//联系人
                    "ctInfo" => $redisVal->ctInfo,//联系电话
//                    "linePlanOrdBillApis" => $redisVal->linePlanOrdBillApis,//账单列表
//                    "linePlanOrdUserApis" => $redisVal->linePlanOrdUserApis,//名单列表
                    "priceTitle" => $redisVal->priceTitle === '默认套餐' ? '常规' : $redisVal->priceTitle,//套餐名称
                    "cpyId" => $redisVal->cpyId,//供应商
                    "supCpyName" => getSupName($redisVal->supCpyName),//供应商
                    "supCpyMobile" => $redisVal->supCpyMobile,//供应商联系电话
                    "saleId" => $redisVal->saleId,//销售id
                    "saleName" => $redisVal->saleName,//销售名称
                    "addTime" => $redisVal->addTime,//下单时间
                    "endTime" => $endTime > 0 ? "<span class=\"red\">" . $endTime . "</span>" : "已过期",//过期时间
                    "payablePingAmount_show" => $redisVal->amount,//应付总额
                    "spayablePingAmount_show" => $spayablePingAmount,//市场价应付总额
                    "profit" => round($spayablePingAmount - $redisVal->amount, 2),//利润
                    "admIsVip" => _admIsVip(),
                    "classify" => $redisVal->classify,
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


}
