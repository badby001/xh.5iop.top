<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Pages\Admin\AdmGroup;
use App\Model\Pages\Admin\AdmRole;
use App\Model\Pages\Admin\AdmUser;
use App\Model\Pages\Admin\AdmUserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    //set
    //完善用户info表和各个权限表
    public function setAdmInfo(Request $request)
    {
        $data = request('data');//验证通过后获取数据包
        $userId = $data['userId'] ?? 0;//0注册,非0为生成
        //业务逻辑开始
        //
        fileLog(['type' => 2, 'info' => '接收新用户注册资料', 'data' => $data]);
        //判断用户名是否存在,不存在则提示
        if (getIsExist('adm_user', 'id', $data['id'], 0) == 0) {
            return getSuccess('手机号码不存在, 请先注册!');
        }
        //用户注册后自动生成关联信息表
        $name = $data['name'] ?? $data['code'];
        $pub_user_id = getErpNewId('user_base');
        $info = new AdmUserInfo();
        $info['adm_code'] = $data['code'];
        $info['name'] = $name;
        $info['pub_user_id'] = $pub_user_id;
        $info['money_ratio'] = 0;//提成
        if ($userId !== 0) {
            $info['attestation_state'] = 2;//通过生成来的,默认为同行身份已经认证
        }
        $info->save();
        //
        //为其创建管理员权限
        $role = new AdmRole();
        $role['code'] = $data['code'];
        $role['title'] = '管理员';
        $role['remarks'] = '系统默认管理员账号';
        $role['add_code'] = $data['code'];
        $role['add_time'] = getTime(1);
        $role->save();
        //
        //获取到role编号 与权限编号进行组合 存入权限表
        $list = [];
        foreach (getRouteData(4) as $k => $v) {
            $list[] = array('role_id' => $role['id'], 'route_id' => $v['id'], 'add_time' => getTime(1));
        }
        DB::table('adm_role_route')->insert($list);//写入角色和权限的关系
        //
        //将用户与权限进行关联
        DB::table('adm_user_role')->insert(array('adm_id' => $data['id'], 'role_id' => $role['id'], 'add_time' => getTime(1)));
        //
        //在关系表中创建当前注册账号未主账号
        DB::table('adm_group')->insert(array('group_number' => $data['id'], 'adm_code' => $data['code'], 'is_adm' => 1));
        //
        if ($userId == 0) {
            fileLog(['type' => 2, 'info' => '新用户数据写入小强联系人', 'data' => $info]);
            //需要往小强的user_bsse表写入联系人信息
            //首先查询系统是否存在webyk的公司信息,如果存在则使用,不存在则创建后使用->直接获取了,从数据库获得数据
            $cpyInfo = DB::connection('sqlsrv')->table('User_Cpy')->where(['ERPID' => 895, 'code' => 'webyk', 'isDel' => 0])->select('ID','simName')->get()[0];
            $admMobile = AdmUser::where(['open_type' => 'mobile', 'code' => $data['code'], 'id' => $data['id'], 'is_del' => 0])->select('open_id')->first()['open_id'];
            DB::connection('sqlsrv')->table('User_Base')
                ->insert(array(
                    'ERPID' => '895',
                    'code' => '1',
                    'ID' => $pub_user_id,
                    'PubUserID' => $pub_user_id,
                    'CpyID' => $cpyInfo->ID,
                    'trueName' => $name,
                    'Mobile' => $admMobile,
                    'cpyName' => $cpyInfo->simName,
                    'remark' => '从小助手系统注册账号时生成',
                    'type' => 1,
                ));
        } else {
            fileLog(['type' => 2, 'info' => '用户数据更新至小强联系人', 'data' => 'userid: ' . $userId . ', pub_user_id: ' . $pub_user_id]);
            DB::connection('sqlsrv')->table('User_Base')
                ->where(['erpid' => 895, 'id' => $userId])
                ->update([
                    'PubUserID' => $pub_user_id,
                    'code' => 1,
                ]);
        }
        //业务逻辑结束
        return response()->json(backApiData('用户信息创建成功', $data['code']));
    }
    //
    //
    //

    //写日志接口
    public function setLog(Request $request)
    {
        $data = request('data');//验证通过后获取数据包
        //业务逻辑开始
        //
        $tableName = $data['tableName'];//表名
        $datas = $data['data'];//数据数组
        //
        opLog($tableName, $datas);
        //
        //业务逻辑结束
        //
        return response()->json(backApiData('日志写入成功', []));
    }
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
    //获取当前登录账号的数据权限
    public function getAdmGroup(Request $request)
    {
        $data = request('data');//验证通过后获取数据包
        //业务逻辑开始
        //
        $admGroup = $data['admGroup'];//分组编号  -1  0  ID
        $admCodes = $data['admCodes'];//所管理的人员
        $admPubCodes = $data['admCodes'];//当前组所有人员
        //
        $isGroupDb = AdmGroup::where('adm_code', $data['admCodes'])->select('group_number', 'is_adm')->first();
        if ($isGroupDb) {
            if ($isGroupDb['is_adm'] == 1) {
                //判断当前账号是否为主账号,如果是则管理当前分组子账号, 否则仅管理自己
                $groupDb = AdmGroup::where('group_number', $isGroupDb['group_number'])->select('adm_code')->get()->toArray();
                $groupCode = '';
                foreach ($groupDb as $k => $v) {
                    $groupCode = $groupCode . $v['adm_code'] . ',';
                }
                $admGroup = $isGroupDb['group_number'];
                $admCodes = $groupCode;
                //
            }
            //
            //
            //获取到当前账号组内其他账号信息,以便共享看到其他人数据
            $groupPubDb = AdmGroup::where('group_number', $isGroupDb['group_number'])->select('adm_code')->get()->toArray();
            $groupCodes = '';
            foreach ($groupPubDb as $k => $v) {
                $groupCodes = $groupCodes . $v['adm_code'] . ',';
            }
            $admPubCodes = $groupCodes;
            //
        }
        //
        //业务逻辑结束
        //
        return response()->json(backApiData('数据权限查询成功', ['admGroup' => $admGroup, 'admCodes' => $admCodes, 'admPubCodes' => $admPubCodes, 'isAdm' => $isGroupDb['is_adm']]));
    }

}
