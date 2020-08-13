<?php

namespace App\Http\Controllers\Sys\Pages\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class InvoiceController extends Controller
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


    //列表
    public function read(Request $request)
    {
        $inp = $request->all();

        $where = [];
        $where['pageNum'] = $inp['page'];
        $where['pageSize'] = $inp['limit'];
        $where['ordId'] = $inp['orderId'];
        if (isset($inp['start_time'])) {
            $where['dateStart'] = $inp['start_time'];
        }
        if (isset($inp['end_time'])) {
            $where['dateEnd'] = $inp['end_time'];
        }
        if (isset($inp['key'])) {
            $where['key'] = $inp['key'];
        }
        $apiWebRes = callWebApi($where, site()['callWebApi'] . 'api/baseInvoice/selectInvoices');//调用接口
        if ($apiWebRes->code == 200) {
            //生成redis缓存
            $redisArr = [];
            foreach ($apiWebRes->data->list as $k => $v) {
                $redisArr['base_invoice:' . $v->id] = json_encode($v);//redis不存在,获取数据库
            }
            Redis::mset($redisArr);//提交缓存
            //读取缓存
            $dbData = [];
            foreach ($redisArr as $k => $v) {
                $this_id = json_decode($v)->id;//当前id
                $redisVal = json_decode(Redis::get('base_invoice:' . $this_id));//读取缓存
                $dbData[] = [
                    "id" => $redisVal->id,// 21904,//发票id
                    "ordId" => $redisVal->ordId,// "209864",//订单id
                    "cpyId" => $redisVal->cpyId,// 109836,//公司id
                    "cpyName" => $redisVal->cpyName,// "网站游客【系统】",//公司名称
                    "kindType" => $redisVal->kindType,// 1,//发票种类，0纸质，1电子发票
                    "invoice" => $redisVal->invoice ?? '旅游服务费',// "发票内容",//发票内容
                    "invoiceType" => $redisVal->invoiceType,// 1,//0输入金额，1选择订单
                    "invoiceDesc" => $redisVal->invoiceDesc,// "领用"//领用，借用
                    "accCard" => $redisVal->accCard,// "121343434343",//银行账号
                    "accBank" => $redisVal->accBank,// "光大银行",//开户行
                    "addr" => $redisVal->addr,// "地址",//开票地址
                    "phone" => $redisVal->phone,// "1212121",//电话
                    "amount" => $redisVal->amount,// 4500,//开票金额
                    "taxpayerIdentificationNumber" => $redisVal->taxpayerIdentificationNumber,// null,//纳税人识别号
                    "isOkShow" => $redisVal->isOkShow,// -2,//0提交财务，-2仅保存
                    "isOk" => $redisVal->isOk,// -2,//0提交财务，-2仅保存
                    "files" => $redisVal->files,// null,//附件
                    "simDesc" => $redisVal->simDesc,// "我是备注",//备注
                    "addTime" => $redisVal->addTime,// "2020-06-16",//添加时间
                    "kindType" => '电子发票',
                    "electronicInvoiceFiles" => $redisVal->electronicInvoiceFiles ?? 'null',//电子发票附件
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
        $inp = $request->all();
        $redisVal = json_decode(Redis::get('line_plan_ordInfo:' . $inp['orderId']));//读取缓存
        $inp['cpyId'] = $redisVal->cpyId;
        $inp['isOk'] = 0;
        $data = [];
        $data['amount'] = $inp['amount'];
        $data['ordID'] = $inp['orderId'];
        $inp['invo'] = json_encode([$data]);
        $inp['files'] = '';
        if ($inp) {
//            return $inp;
            $apiWebRes = callWebApi($inp, site()['callWebApi'] . 'api/baseInvoice/add');//调用接口
            if ($apiWebRes->code == 200) {
                //生成redis缓存
                $redisArr['base_invoice:' . $apiWebRes->data->invoiceId] = json_encode($inp);
                Redis::mset($redisArr);//提交缓存
                //opLog('base_invoice', [['type' => '添加', 'this_id' => $apiWebRes->data->invoiceId, 'content' => json_encode($inp)]]);//记录日志
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
        //
        $redisVal = json_decode(Redis::get('line_plan_ordInfo:' . $id));//读取缓存
        $pingAmount = $redisVal->pingAmount;//已付款金额
        $dbData = [];
        $dbData['id'] = '';
        $dbData['orderId'] = $id;
        $dbData['pingAmount'] = $pingAmount;
        $dbData['cpyName'] = _admName();
        $dbData['invoice'] = '旅游服务费';
        $dbData['taxpayerIdentificationNumber'] = '';
        return view('sys.pages.order.invoiceEdit', ['db' => $dbData]);
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
        $redisVal = json_decode(Redis::get('base_invoice:' . $id));//读取缓存
        $dbData = [
            "id" => $redisVal->id,// 21904,//发票id
            "orderId" => $redisVal->ordId,// "209864",//订单id
            "cpyId" => $redisVal->cpyId,// 109836,//公司id
            "cpyName" => $redisVal->cpyName,// "网站游客【系统】",//公司名称
            "kindType" => $redisVal->kindType,// 1,//发票种类，0纸质，1电子发票
            "invoice" => $redisVal->invoice ?? '旅游服务费',// "发票内容",//发票内容
            "invoiceType" => $redisVal->invoiceType,// 1,//0输入金额，1选择订单
            "invoiceDesc" => $redisVal->invoiceDesc,// "领用"//领用，借用
            "accCard" => $redisVal->accCard,// "121343434343",//银行账号
            "accBank" => $redisVal->accBank,// "光大银行",//开户行
            "addr" => $redisVal->addr,// "地址",//开票地址
            "phone" => $redisVal->phone,// "1212121",//电话
            "pingAmount" => $redisVal->amount,// 4500,//开票金额
            "taxpayerIdentificationNumber" => $redisVal->taxpayerIdentificationNumber,// null,//纳税人识别号
            "isOkShow" => $redisVal->isOkShow,// -2,//0提交财务，-2仅保存
            "isOk" => $redisVal->isOk,// -2,//0提交财务，-2仅保存
            "files" => $redisVal->files,// null,//附件
            "simDesc" => $redisVal->simDesc,// "我是备注",//备注
            "addTime" => $redisVal->addTime,// "2020-06-16",//添加时间
            "kindType" => '电子发票',
        ];
        // return $dbData;
        return view('sys.pages.order.invoiceEdit', ['db' => $dbData]);

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
        $redisVal = json_decode(Redis::get('base_invoice:' . $id));//读取缓存
        $inp['cpyId'] = $redisVal->cpyId;
        $inp['isOk'] = 0;
        $inp['id'] = $id;
        $data = [];
        $data['amount'] = $inp['amount'];
        $data['ordID'] = $redisVal->ordId;
        $inp['invo'] = json_encode([$data]);
        $inp['files'] = '';
        if ($inp) {
            $apiWebRes = callWebApi($inp, site()['callWebApi'] . 'api/baseInvoice/update');//调用接口
            if ($apiWebRes->code == 200) {
                //生成redis缓存
                $redisArr['base_invoice:' . $id] = json_encode($inp);
                Redis::mset($redisArr);//提交缓存
                //opLog('base_invoice', [['type' => '修改', 'this_id' => $id, 'content' => json_encode($inp)]]);//记录日志
                return getSuccess(1);
            } else {
                return getSuccess($apiWebRes->msg);
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
}
