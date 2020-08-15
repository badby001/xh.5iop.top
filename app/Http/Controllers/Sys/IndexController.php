<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\Model\Pages\Admin\AdmUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;


class IndexController extends Controller
{

    //首页 / 登录页
    function index()
    {
        if (_admCode()) {
            return view('.sys.index');
        } else {
            return view('.sys.login');
        }
    }

    //登录接口
    function login(Request $request)
    {
        $inp = $request->all();

        $db_data = AdmUser::where([
            'open_id' => $inp['data']['open_id'],
            'is_del' => 0
        ])
            ->select('id', 'code', 'name', 'open_id', 'pass_word', 'is_lock')
            ->first();
        if (!$db_data) {
            return getSuccess('账号不存在, 再仔细想想?');
        }
        if ($db_data['is_lock'] == 1) {
            return [
                'success' => false,
                'msg' => '当前账号已被锁定, 请联系系统管理员解锁',
                'open_id' => $inp['data']['open_id'],
                'pass_word' => $inp['data']['pass_word']
            ];
        }
        $is_Pwd = json_encode(Hash::check($inp['data']['pass_word'], $db_data['pass_word']));
        if ($is_Pwd == 'true') {
            $time = base()['cacheTime'];//缓存时间
            $name = $db_data['name'];
            \Cookie::queue('admId', $db_data['id'], $time);//id
            \Cookie::queue('admOpenId', $inp['data']['open_id'], $time);//open_id
            \Cookie::queue('admName', $name ? $name : $db_data['code'], $time);//姓名
            \Cookie::queue('admCode', $db_data['code'], $time);//编号
            \Cookie::queue('admPwd', md5($inp['data']['pass_word']), $time);//登录密码
            _admCache($db_data['code']);//销毁redis缓存
            //
            //
            //opLog('adm_user_login', [['open_type' => 'mobile', 'open_id' => $inp['data']['open_id'], 'type' => 'sign', 'name' => $name, 'add_code' => $db_data['code'], 'ip' => site()['ip'], 'browser' => site()['browser']]]);//记录日志
            //
            Redis::set('admOnline:' . $db_data['code'], getTime(0));//记录在线账号
            Redis::expire('admOnline:' . $db_data['code'], base()['redisTime']);//设置在线时间,用于做用户统计
            //
            $res = [
                'success' => true
            ];
        } else {
            $res = [
                'success' => false,
                'msg' => '用户名或密码错误, 仔细想想吧',
                'open_id' => $inp['data']['open_id'],
                'pass_word' => $inp['data']['pass_word']
            ];
        }
        return $res;
    }

    //退出接口
    function logout()
    {
        //
        _admCache(_admCode());//销毁缓存
        \Cookie::queue('admId', null, -1);
        \Cookie::queue('admOpenId', null, -1);
        \Cookie::queue('admName', null, -1);
        \Cookie::queue('admCode', null, -1);
        \Cookie::queue('admPwd', null, -1);
        return redirect('/sys/login');
    }


    function admUserLogin(Request $request)
    {
        $inp = $request->all();
        //
        //
        $db = DB::connection('logSystem')->table('adm_user_login')
            ->orderBy('add_Time', 'desc')
            ->whereIn('add_code', getInjoin(_admPubCodes()))//数据权限控制
            ->paginate($inp['limit'])
            ->all();
        $dbData = [];
        foreach ($db as $k => $v) {
            $dbData[] = [
                'open_type' => $v->open_type,
                'name' => getAdmName($v->add_code),
                'type' => getUserLoginType($v->type),
                'ip' => $v->ip,
                'browser' => $v->browser,
                'add_time' => $v->add_time,
            ];
        }
        //
        //总记录
        $total = DB::connection('logSystem')->table('adm_user_login')
            ->whereIn('add_code', getInjoin(_admPubCodes()))//数据权限控制
            ->count();
        $data = [];
        $data['code'] = 0;
        $data['msg'] = '查询成功';
        $data['data'] = $dbData;
        $data['count'] = $total;
        return $data;
    }


    function upload(Request $request)
    {
        $file = $request->file('file');
        if ($file->isValid()) {
            $old_name = $file->getClientOriginalName();//获取原文件名称
            $ext = strtolower($file->getClientOriginalExtension());//获取原文件格式
            $type = $file->getClientMimeType();//MimeType
            $realpath = $file->getRealPath();//临时绝对路径
            $fileName = $old_name . '-' . uniqid() . '.' . $ext;//新文件名
            $fileExt = '';
            //
            $imageExtStr = "gif|jpg|jpeg|png|bmp";
            $flashExtStr = "swf|flv";
            $mediaExtStr = "swf|flv|mp3wav|wma|wmv|mid|avi|mpg|asf|rm|rmvb";
            $fileExtStr = "doc|docx|xls|xlsx|ppt|txt|zip|rar|gz|bz2";
            if (strpos($imageExtStr, $ext)) {
                $fileExt = 'image';
            } elseif (strpos($flashExtStr, $ext)) {
                $fileExt = 'flash';
            } elseif (strpos($mediaExtStr, $ext)) {
                $fileExt = 'media';
            } elseif (strpos($fileExtStr, $ext)) {
                $fileExt = 'file';
            } else {
                $fileExt = '';
            }

            //本地服务器方法
            $path = $file->move(public_path() . '/uploads/' . _admCode() . '/' . $fileExt . '/' . date('Y-m-d') . '/', $fileName);
            $file = '/uploads/' . _admCode() . '/' . $fileExt . '/' . date('Y-m-d') . '/' . $fileName;
            if ($path) {
                return [
                    'code' => 0,
                    'success' => true,
                    'file' => $file
                ];
            } else {
                return getSuccess(2);
            }
            return getSuccess(1);
        } else {
            return getSuccess(2);
        }
    }


    function demo()
    {
        //noticeSystem(['type' => 1, 'content' => getNoticeCode(['type'=>100,'data'=>['0','1']])], [['email' => 'jiajun_520@126.com', 'mobile' => '13040870704'],['email' => '666@96op.com', 'mobile' => '13040870704']]);

        return noticeSystem(['type' => 1, 'content' => getNoticeCode(['type' => 100, 'data' => ['13040870704']])], ['4Dokr77I', '4Dokr77I']);

    }
}
