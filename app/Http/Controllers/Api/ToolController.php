<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ToolController extends Controller
{
    //获取php的hash值
    public function getHashPhP(Request $request)
    {
        $key = request('key');
        $data = [];
        $data['code'] = 200;
        $data['msg'] = '查询成功';
        $data['data'] = Hash::make($key);
        return response()->json($data);
    }

    //获取NewId
    public function getNewID()
    {
        $data = [];
        $data['code'] = 200;
        $data['msg'] = '查询成功';
        $data['data'] = getNewId();
        return response()->json($data);
    }

    //获取验证码
    public function getCaptcha(Request $request)
    {
        $mobile = request('mobile');
        $DB = DB::connection('sqlsrv')->table('erp_msg')->select('msg_content')
            ->where(['erpid' => 895, 'msg_type' => 'MOBILE', 'bus_type' => 'SYSTEM'])
            ->where('create_date', '>=', date("Y-m-d H:i:s", strtotime("-5 minute")))
            ->where('msg_content', 'like', '%' . $mobile . '%')
            ->limit(1)
            ->orderBy('msg_id', 'desc')
            ->get();
        $data = [];
        if ($DB == '[]') {
            $data['code'] = 0;
            $data['msg'] = '验证码已失效';
            $data['data'] = "";
        } else {
            $DB = json_encode($DB);
            $DB = explode('\uff1a', $DB)[1] ?? '';
            $data['code'] = 200;
            $data['msg'] = '查询成功';
            $data['data'] = substr($DB, 0, 4);
        }
        //
        return response()->json($data);
    }


    //获取token
    public function getToken(Request $request)
    {
        $key = request('key');//验证通过后获取数据包
        //业务逻辑开始
        //
        return setToken($key);
        //
        //业务逻辑结束
    }

}
