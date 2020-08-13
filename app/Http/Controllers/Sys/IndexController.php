<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\Model\Pages\Admin\AdmUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Storage;

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
            'open_type' => 'mobile',
            'open_id' => $inp['data']['open_id'],
            'is_del' => 0
        ])
            ->select('id', 'code', 'open_type', 'open_id', 'pass_word', 'is_lock')
            ->with(['admUserInfo:adm_code,name,pub_user_id,attestation_state,user_head'])
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
            $name = $db_data['admUserInfo']['name'];
            $pubUserId = $db_data['admUserInfo']['pub_user_id'];
            \Cookie::queue('admId', $db_data['id'], $time);//id
            \Cookie::queue('admOpenId', $inp['data']['open_id'], $time);//open_id
            \Cookie::queue('admName', $name ? $name : $db_data['code'], $time);//姓名
            \Cookie::queue('admCode', $db_data['code'], $time);//编号
            \Cookie::queue('admPubUserId', $pubUserId, $time);//pubUserId
            \Cookie::queue('admAttState', $db_data['admUserInfo']['attestation_state'], $time);//同行认证状态
            \Cookie::queue('admPwd', md5($inp['data']['pass_word']), $time);//登录密码
            \Cookie::queue('admHead', $db_data['admUserInfo']['user_head'] ?? '../../images/face.jpg', $time);//用户头像
            _admCache($db_data['code']);//销毁redis缓存
            //
            //获取分组内容, 首先查询当前账号是否存在分组中,如果不存在则当前账号就是主号
            $admGroup = $db_data['id'];//个人分组数字编号即为当前账号的id
            $admCodes = $db_data['code'];//获取分组admCodes,将需要管理的子账号code进行记录,如果没有子账号,则只有一个,否则有多个值
            $admPubCodes = $db_data['code'];//获取分组admCodes,允许本组内各个人员可以互相看到其他人信息
            $isAdm = 0;//默认账号为子账号
            //
            //用户登录后调用接口自动获取关联信息表
            $apiRes = callApi(['admGroup' => $admGroup, 'admCodes' => $admCodes], '/api/admin/getAdmGroup');//调内部接口
            if ($apiRes->code == 200) {
                $admGroup = $apiRes->data->admGroup;
                $admCodes = $apiRes->data->admCodes;
                $admPubCodes = $apiRes->data->admPubCodes;
                $isAdm = $apiRes->data->isAdm;
            }
            //
            \Cookie::queue('admGroup', $admGroup, $time);//个人分组数字编号
            \Cookie::queue('admCodes', $admCodes, $time);//分组编号(数据权限)
            \Cookie::queue('admPubCodes', $admPubCodes, $time);//分组编号(数据权限)
            \Cookie::queue('isAdm', $isAdm, $time);//子母账号身份(数据权限)
            //
            opLog('adm_user_login', [['open_type' => 'mobile', 'open_id' => $inp['data']['open_id'], 'type' => 'sign', 'name' => $name, 'add_code' => $db_data['code'], 'ip' => site()['ip'], 'browser' => site()['browser']]]);//记录日志
            noticeSystem(['type' => 1, 'content' => getNoticeCode(['type' => 100, 'data' => [$inp['data']['open_id']]])], [$db_data['code']]);
            //
            Redis::set('admOnline:' . $db_data['code'], getTime(0));//记录在线账号
            Redis::expire('admOnline:' . $db_data['code'], base()['redisTime']);//设置在线时间,用于做用户统计
            //
            //获取微站的登录信息
            $apiWebRes = callWebApi(['openType' => 'mobile', 'openId' => $inp['data']['open_id'], "passWord" => $inp['data']['pass_word']], site()['callWebApi'] . 'api/login');//调用接口
            if ($apiWebRes->code == 200) {
                $fj_login_id = $apiWebRes->data->fj_login_id;
                $isVip = $apiWebRes->data->isVip ?? 0;
                //return $fj_login_id;
                \Cookie::queue('admIsVip', $isVip, $time);//微站登录产生的cookie
                \Cookie::queue('fjLoginId', $fj_login_id, $time);//微站登录产生的cookie
            } else {
                return getSuccess($apiWebRes->msg);
            }
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
        opLog('adm_user_login', [['open_type' => 'mobile', 'open_id' => _admOpenId(), 'type' => 'logout', 'name' => _admName(), 'ip' => site()['ip'], 'browser' => site()['browser']]]);//记录日志
        //
        _admCache(_admCode());//销毁缓存
        \Cookie::queue('admId', null, -1);
        \Cookie::queue('admOpenId', null, -1);
        \Cookie::queue('admName', null, -1);
        \Cookie::queue('admCode', null, -1);
        \Cookie::queue('admGroup', null, -1);
        \Cookie::queue('admCodes', null, -1);
        \Cookie::queue('admPubCodes', null, -1);
        \Cookie::queue('admPubUserId', null, -1);
        \Cookie::queue('isAdm', null, -1);
        \Cookie::queue('fjLoginId', null, -1);
        \Cookie::queue('admAttState', null, -1);
        \Cookie::queue('admIsVip', null, -1);
        \Cookie::queue('admPwd', null, -1);
        \Cookie::queue('admHead', null, -1);
        return redirect('/sys/login');
    }

    //注册接口
    function register()
    {
        return view('.sys.register');
    }

    //注册提交接口
    function registerReg(Request $request)
    {
        $inp = $request->all();
        //
        $authCode = $inp['data']['authCode'];//获取前台输入的短信验证码
        //获取数据库的验证码
        $apiRes = callApi([], '/api/tool/getCaptcha');//调内部接口
        if ($apiRes->code == 200) {
            if ($apiRes->data !== $authCode) {
                return [
                    'success' => false, 'msg' => '短信验证码不正确!',
                    'open_id' => $inp['data']['open_id'],
                    'pass_word' => $inp['data']['pass_word'],
                ];
            }
        } else {
            if ($authCode !== '5kfj') {
                return getSuccess($apiRes->msg);
            }
        }
        //
        //判断用户名是否存在
        if (getIsExist('adm_user', 'open_id', $inp['data']['open_id'], 0) > 0) {
            return [
                'success' => false, 'msg' => '手机号码已存在, 如有忘记密码, 请在登录页找回密码!',
                'open_id' => $inp['data']['open_id'],
                'pass_word' => $inp['data']['pass_word'],
            ];
        }
        $code = getNewId();
        $adm = new AdmUser();
        $adm['code'] = $code;
        $adm['open_type'] = 'mobile';
        $adm['open_id'] = $inp['data']['open_id'];
        $adm['pass_word'] = Hash::make($inp['data']['pass_word']);
        $adm['is_lock'] = 0;//账号默认
        $adm['is_del'] = 0;//默认未删除
        $adm['add_code'] = $code;
        $adm['add_time'] = getTime(1);
        if ($adm->save()) {
            //用户注册后调用接口自动生成关联信息表
            $apiRes = callApi(['id' => $adm['id'], 'code' => $code], '/api/admin/setAdmInfo');//调内部接口
            if ($apiRes->code == 200) {
                return getSuccess(1);
            } else {
                return getSuccess($apiRes->msg);
            }
        } else {
            return [
                'success' => false, 'msg' => '操作失败!',
                'open_id' => $inp['data']['open_id'],
                'pass_word' => $inp['data']['pass_word'],
            ];
        }
    }

    //找回密码
    function forgetPwd()
    {
        return view('.sys.forget');
    }


    //找回密码提交密码
    function forgetPwds(Request $request)
    {
        $inp = $request->all();
        //
        $authCode = $inp['data']['authCode'];//获取前台输入的短信验证码
        //获取数据库的验证码
        $apiRes = callApi([], '/api/tool/getCaptcha');//调内部接口
        if ($apiRes->code == 200) {
            if ($apiRes->data !== $authCode) {
                return [
                    'success' => false, 'msg' => '短信验证码不正确!',
                    'open_id' => $inp['data']['open_id'],
                    'pass_word' => $inp['data']['pass_word'],
                ];
            }
        } else {
            if ($authCode !== '5kfj') {
                return getSuccess($apiRes->msg);
            }
        }
        //
        //判断用户名是否存在
        if (getIsExist('adm_user', 'open_id', $inp['data']['open_id'], 0) == 0) {
            return getSuccess('手机号码不存在, 再想想?');
        }
        $adm = AdmUser::where(['open_type' => 'mobile', 'open_id' => $inp['data']['open_id']])
            ->update(['pass_word' => Hash::make($inp['data']['pass_word'])]);
        if ($adm) {
            //
            opLog('pub_logs', [['type' => '忘记密码', 'this_id' => 0, 'content' => '[' . $inp['data']['open_id'] . '] 密码已重置']]);//记录日志
            return getSuccess(1);
        } else {
            return getSuccess(2);
        }
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
//            $path = $file->move(public_path() . '/uploads/' . _admCode() . '/' . $fileExt . '/' . date('Y-m-d') . '/', $fileName);
//            $file = '/uploads/' . _admCode() . '/' . $fileExt . '/' . date('Y-m-d') . '/' . $fileName;
            //七牛云方法
            $files = $fileExt . '/' . _admCode() . '/' . date('Y-m-d') . '/' . $fileName;
            $disk = Storage::disk('qiniu')->writeStream($files, fopen($file->getRealPath(), 'r'));
            if ($disk) {
                return [
                    'code' => 0,
                    'success' => true,
                    'file' => env('QINIU_DOMAIN') . $files
                ];
            } else {
                return getSuccess(2);
            }
            return getSuccess(1);
        } else {
            return getSuccess(2);
        }
    }

    //获取小强图形验证码
    public function postXqCaptcha(Request $request)
    {
        $inp = $request->all();
        $apiRes = callWebApi(['imgCode' => $inp['imgCode'], 'openId' => $inp['openId']], site()['callWebApi'] . 'api/code/msgCode');//调接口
        if ($apiRes->code == 200) {
            return getSuccess(1);
        } else {
            return getSuccess($apiRes->msg);
        }
    }


    function demo()
    {
        //noticeSystem(['type' => 1, 'content' => getNoticeCode(['type'=>100,'data'=>['0','1']])], [['email' => 'jiajun_520@126.com', 'mobile' => '13040870704'],['email' => '666@96op.com', 'mobile' => '13040870704']]);

        return noticeSystem(['type' => 1, 'content' => getNoticeCode(['type' => 100, 'data' => ['13040870704']])], ['4Dokr77I', '4Dokr77I']);

    }
}
