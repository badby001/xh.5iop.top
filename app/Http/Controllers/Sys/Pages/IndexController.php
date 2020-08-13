<?php

namespace App\Http\Controllers\Sys\Pages;

use App\Http\Controllers\Controller;
use App\Model\Pages\Admin\AdmUser;
use App\Model\Pages\Admin\AdmUserInfo;
use App\Model\Pages\XQERPV3\LinePlanOrd;
use App\Model\Pages\XQERPV3\UserBase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;


class IndexController extends Controller
{
    //
    //控制台
    function console()
    {

        $where = [];
        $where['erpid'] = 895;
        $where['userId'] = _admPubUserId();
        $this_amount = 0;
        $this_orders = 0;
        $LinePlanOrdDb = LinePlanOrd::where($where)->whereDate('addTime', getTime(2))->select('Amount')->get();
        foreach ($LinePlanOrdDb as $k => $v) {
            $this_amount += $v->Amount * 1;
            $this_orders += 1;
        }
        $this_toGoOrder = LinePlanOrd::where($where)->whereDate('planDate', getTime(2))->select(1)->count();//获取当日待出行订单数量
        //
        $data = [];
        $data['this_amount'] = $this_amount;//当日订单总额
        $data['this_orders'] = $this_orders;//当日订单数
        $data['this_toGoOrder'] = $this_toGoOrder;//待出行订单数
        $data['this_members'] = 0;//会员总数
        return view('.sys.pages.console', ['db' => $data]);
    }

    //个人信息页面
    function admInfo()
    {
        //个人用户信息
        $db = AdmUserInfo::where('adm_code', _admCode())
            ->first();
        $db['attestation_state_show'] = getAttestationState($db['attestation_state']);
        $db['attestation_area_code_value'] = $db['attestation_area_code'] ?? '';
        $db['attestation_area_title'] = getPubProCit($db['attestation_area_code'])->title ?? '';
        $db['attestation_area_code'] = getPubProCit($db['attestation_area_code'])->code ?? '';
        return view('.sys.pages.admin.admInfo', ['db' => $db]);
    }

    //个人信息更新
    function admInfoUp(Request $request)
    {
        //执行更新
        $inp = $request->all();
        $info = $db = AdmUserInfo::where('adm_code', _admCode())
            ->update(
                [
                    'name' => $inp['data']['name'],
                    'sex' => $inp['data']['sex'] == 'false' ? 0 : 1,
                    'birth_date' => $inp['data']['birth_date'],
                    'user_head' => $inp['data']['user_head'] ?? '../images/face.jpg',
                    'email' => $inp['data']['email'],
                ]
            );
        $bool = AdmUser::where(['code' => _admCode(), 'open_type' => 'mobile'])->update(['up_code' => _admCode(), 'up_time' => getTime(1)]);
        if ($info || $bool) {
            $time = 1 * 60 * 12;//缓存时间
            \Cookie::queue('admName', $inp['data']['name'], $time);
            \Cookie::queue('admHead', $inp['data']['user_head'], $time);//用户头像
            //同步更新小强数据库
            UserBase::where('pubUserId', _admPubUserId())
                ->update([
                    'trueName' => $inp['data']['name'],
                    'sex' => $inp['data']['sex'] == 'false' ? 1 : 0,
                    'birth' => $inp['data']['birth_date']
                ]);
            //
            opLog('adm_user', [['type' => '基本资料', 'this_id' => _admId(), 'content' => json_encode($inp)]]);//记录日志
            return getSuccess(1);
        } else {
            return getSuccess(2);
        }
    }

    //同行认证更新
    function admAttestationUp(Request $request)
    {
        //执行更新
        $inp = $request->all();
        $info = $db = AdmUserInfo::where('adm_code', _admCode())
            ->update(
                [
                    'attestation_state' => 1,
                    'attestation_tourist_agency' => $inp['data']['attestation_tourist_agency'],
                    'attestation_area' => getPubProCit($inp['data']['attestation_area_code'])->title,
                    'attestation_area_code' => $inp['data']['attestation_area_code'],
                    'attestation_address' => $inp['data']['attestation_address'],
                    'attestation_business_license_img' => $inp['data']['attestation_business_license_img'],
                ]
            );
        $bool = AdmUser::where(['code' => _admCode(), 'open_type' => 'mobile'])->update(['up_code' => _admCode(), 'up_time' => getTime(1)]);
        if ($info || $bool) {
            opLog('adm_user', [['type' => '同行认证', 'this_id' => _admId(), 'content' => json_encode($inp)]]);//记录日志
            return getSuccess(1);
        } else {
            return getSuccess(2);
        }
    }

    //个人密码页面
    function admPwd()
    {
        //个人密码
        return view('.sys.pages.admin.admPwd');
    }

    //个人密码更新
    function admPwdUp(Request $request)
    {
        //执行更新
        $inp = $request->all();
        //查找用户
        $db = AdmUser::where(['code' => _admCode(), 'open_type' => 'mobile'])
            ->select(['pass_word'])
            ->first();
        $is_pwd = json_encode(Hash::check($inp['data']['old_pwd'], $db['pass_word']));
        if ($is_pwd == 'false') {
            return getSuccess('旧密码错误，请重新输入！');
        }
        $data = AdmUser::where(['code' => _admCode(), 'open_type' => 'mobile'])
            ->update(['pass_word' => Hash::make($inp['data']['pass_word'])]);
        if ($data) {
            //
            opLog('adm_user', [['type' => '安全设置', 'this_id' => _admId(), 'content' => json_encode($inp)]]);//记录日志
            return getSuccess(1);
        } else {
            return getSuccess(2);
        }
    }

    //菜单
    function menu()
    {
        //无限极菜单
        return getMenu();
    }

    //日志读取
    function logInfo($tableName, $tableId, $tableStr)
    {
        $arr = [];
        $arr['tableName'] = $tableName;
        $arr['tableId'] = $tableId;
        $arr['tableStr'] = $tableStr;
        return view('.sys.system.logInfo', $arr);
    }

    //日志列表
    function logInfoRead(Request $request)
    {
        $inp = $request->all();
        $where =
            function ($query) use ($inp) {
                $query->whereIn('add_code', getInjoin(_admCodes()));//数据权限控制
                if ($inp['tableId'] !== 'undefined') {
                    $query->where('this_id', $inp['tableId']);
                }
                if (isset($inp['id'])) {
                    $query->where('this_id', $inp['id']);
                }
                if (isset($inp['type'])) {
                    $query->where('type', $inp['type']);
                }
                if (isset($inp['start_time']) && isset($inp['end_time'])) {
                    $query->whereBetween('add_time', [$inp['start_time'], $inp['end_time']]);
                } else if (isset($inp['start_time'])) {
                    $query->where('add_time', '>=', $inp['start_time']);
                } else if (isset($inp['end_time'])) {
                    $query->where('add_time', '<=', $inp['end_time']);
                } else if (isset($inp['key'])) {
                    $query->where('content', 'like', '%' . $inp['key'] . '%');
                }
            };
        $db = DB::connection('logSystem')->table($inp['tableName'])
            ->where($where)
            ->orderBy('add_time', 'asc')
            ->paginate($inp['limit'])
            ->all();
        //生成redis缓存
        $redisArr = [];
        foreach ($db as $k => $v) {
            $redisArr[$inp['tableName'] . '_log:' . $v->id] = json_encode($v);//redis不存在,获取数据库
        }
        Redis::mset($redisArr);//提交缓存
        ////读取缓存
        $dbData = [];
        foreach ($redisArr as $k => $v) {
            $this_id = json_decode($v)->id;//当前id
            $redisVal = json_decode(Redis::get($inp['tableName'] . '_log:' . $this_id));//读取缓存
            $content = contentStr($inp['tableStr'], $redisVal->content);//操作内容
            $dbData[] = [
                'id' => $redisVal->id,//id
                'type' => $redisVal->type,
                'this_id' => $redisVal->this_id,
                'content' => $content,
                'add_name' => getAdmName($redisVal->add_code),//创建者
                'add_time' => $redisVal->add_time,//创建时间
            ];
        }
        //
        //总记录
        $total = DB::connection('logSystem')->table($inp['tableName'])
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

    //框架消息通知
    function message()
    {
        return view('.sys.system.message');
    }

    //框架便签
    function note()
    {
        return view('.sys.system.note');
    }

    //框架主题
    function theme()
    {
        return view('.sys.system.theme');
    }

    //框架锁定桌面
    function screen()
    {
        return view('.sys.system.screen');
    }
}
