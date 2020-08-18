<?php

use App\Model\Pages\Admin\AdmRole;
use App\Model\Pages\Admin\AdmUser;
use App\Model\Pages\Admin\AdmUserInfo;
use App\Model\Pages\System\PubProvincesCities;
use App\Model\Pages\System\Route;
use App\Model\Pages\XQERPV3\ErpAutoId;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

//=================================== 底层方法类 ===================================
//声明
function base()
{
    $data = [];
    $data['moneyRatio'] = '15';//销售提成 默认值
    $data['cacheTime'] = 1 * 60 * 720; //系统中所有与缓存相关时间 720小时
    $data['redisTime'] = 1 * 60 * 30; //系统中所有与缓存相关时间 30分钟
    return $data;
}

//请求内部接口
function callApi($data, $apiUrl)
{
    $key = getRandstr(10);
    $res = [];
    $res['key'] = $key;
    $res['token'] = setToken($key);//生成token
    $res['data'] = $data;//数据包
    fileLog(['type' => 3, 'info' => '请求内部接口->发送数据包=>', 'data' => $data]);
    $json_res = apiPost($res, $apiUrl);//调用接口
    $json_res = json_decode($json_res);//转对象
    fileLog(['type' => 3, 'info' => '请求内部接口->接收返回结果=>', 'data' => $json_res]);
    return $json_res;
}

//生成token,不通过时直接打断
function setToken($key)
{
    $timestamp = strtotime(date('Y-m-d H:i'));//当前时间戳
    return md5($key . $timestamp . 'Kfj321');//md5
}


//验证token,不通过时直接打断
function checkToken($key, $token)
{
    $timestamp = strtotime(date('Y-m-d H:i'));//当前时间戳
    $localToken = md5($key . $timestamp . 'Kfj321');//md5
    $data = [];
    if ($localToken !== $token) {
        $data['code'] = 0;
        $data['msg'] = 'token验证失败';
        $data['data'] = '';
    } else {
        $data['code'] = 200;
    }
    return response()->json($data);
}

//返回接口数据
function backApiData($msg, $data)
{
    $res = [];
    $res['code'] = 200;
    $res['msg'] = $msg;
    $res['data'] = $data;
    return $res;
}

//接口post请求
function apiPost($body, $apiStr)
{
    $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
    $client = new \GuzzleHttp\Client(['verify' => false, 'base_uri' => $http_type . $_SERVER['SERVER_NAME']]);
    $res = $client->request('POST', $apiStr, [
        'json' => $body,
        'headers' => [
            'Content-type' => 'application/json',
            "Accept" => "application/json"
        ]
    ]);
    return $res->getBody()->getContents();
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
//
//
//
//
//
//
//
//=================================== 自定义方法类 ===================================
//获取时间
function getTime($str)
{
    switch ($str) {
        case 1:
            $back = new DateTime('now');
            break;
        case 2:
            $back = date('Y-m-d');
            break;
        case 3:
            $back = date('Y-m-d H:i:s');
            break;
        default:
            $back = time();
            break;
    }
    return $back;
}

//时间加减格式化
function formatDate($str, $value, $data)
{
    switch ($str) {
        case "add":
            $mm = $value * 60;//PHP的时间是按秒算的
            $back = date("Y-m-d H:i:s", strtotime($data) + $mm);
            break;
        case "cut":
            $mm = $value * 60;//PHP的时间是按秒算的
            $back = date("Y-m-d H:i:s", strtotime($data) - $mm);
            break;
        case "timeAdd":
            $back = strtotime($value) + strtotime($data);
            break;
        case "timeCut":
            $back = strtotime($value) - strtotime($data);
            break;
        default:
            $back = date('Y-m-d', strtotime($data));
            break;
    }
    return $back;
}

//时间相减计算得出新时间
function Sec2Time($time)
{
    if (is_numeric($time)) {
        $value = array(
            "years" => 0, "days" => 0, "hours" => 0,
            "minutes" => 0, "seconds" => 0,
        );
        if ($time >= 31556926) {
            $value["years"] = floor($time / 31556926);
            $time = ($time % 31556926);
        }
        if ($time >= 86400) {
            $value["days"] = floor($time / 86400);
            $time = ($time % 86400);
        }
        if ($time >= 3600) {
            $value["hours"] = floor($time / 3600);
            $time = ($time % 3600);
        }
        if ($time >= 60) {
            $value["minutes"] = floor($time / 60);
            $time = ($time % 60);
        }
        $value["seconds"] = floor($time);

        $t = $value["years"] . "年 " . $value["days"] . "天 " . " " . $value["hours"] . "小时 " . $value["minutes"] . "分 " . $value["seconds"] . "秒";
        $_years = $value["years"] . '年';
        $_days = $value["days"] . '天 ';
        $_hours = $value["hours"] . '小时 ';
        $_mins = $value["minutes"] . '分钟 ';
        $_secs = $value["seconds"] . '秒';
        $_time = '';
        if ($value["seconds"] * 1 > 0) {
            $_time = $_secs;
        }
        if ($value["minutes"] * 1 > 0) {
            $_time = $_mins . $_secs;
        }
        if ($value["hours"] * 1 > 0) {
            $_time = $_hours . $_mins . $_secs;
        }
        if ($value["days"] * 1 > 0) {
            $_time = $_days . $_hours . $_mins . $_secs;
        }
        if ($value["years"] * 1 > 0) {
            $_time = $_years . $_days . $_hours . $_mins . $_secs;
        }
        Return $_time;
    } else {
        return (bool)FALSE;
    }
}

//输入以逗号分隔的字符串,生成带单引号的数组, 将字符串转为数组
function getInjoin($str)
{
    $array = explode(',', $str);
    return array_filter($array);
}


//产生随机数
function getRandstr($len)
{
    $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
    $randStr = str_shuffle($str);//打乱字符串
    $rands = substr($randStr, 0, $len);//substr(string,start,length);返回字符串的一部分
    return $rands;
}

//对象转数组
function objectToArray($object)
{
    //先编码成json字符串，再解码成数组
    $L = json_encode($object);
    return json_decode($L, true);
}

//数组转对象
function arrayToObject($object)
{
    //先编码成json字符串，再解码成对象
    $O = json_encode($object);
    return json_decode($O);
}

//unicode转为中文
function unicode_decode($name)
{
    $json = '{"str":"' . $name . '"}';
    $arr = json_decode($json, true);
    if (empty($arr)) return '';
    return $arr['str'];
}

//创建唯一Id
function getNewId($type = 5, $length = 8, $time = 0)
{
    $str = $time == 0 ? '' : date('YmdHis', time());
    switch ($type) {
        case 0:
            for ((int)$i = 0; $i <= $length; $i++) {
                if (mb_strlen($str) == $length) {
                    $str = $str;
                } else {
                    $str .= rand(0, 9);
                }
            }
            break;
        case 1:
            for ((int)$i = 0; $i <= $length; $i++) {
                if (mb_strlen($str) == $length) {
                    $str = $str;
                } else {
                    $rand = "qwertyuioplkjhgfdsazxcvbnm";
                    $str .= $rand{mt_rand(0, 26)};
                }
            }
            break;
        case 2:
            for ((int)$i = 0; $i <= $length; $i++) {
                if (mb_strlen($str) == $length) {
                    $str = $str;
                } else {
                    $rand = "QWERTYUIOPLKJHGFDSAZXCVBNM";
                    $str .= $rand{mt_rand(0, 26)};
                }
            }
            break;
        case 3:
            for ((int)$i = 0; $i <= $length; $i++) {
                if (mb_strlen($str) == $length) {
                    $str = $str;
                } else {
                    $rand = "123456789qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM";
                    $str .= $rand{mt_rand(0, 35)};
                }
            }
            break;
        case 4:
            for ((int)$i = 0; $i <= $length; $i++) {
                if (mb_strlen($str) == $length) {
                    $str = $str;
                } else {
                    $rand = "!@#$%^&*()_+=-~`";
                    $str .= $rand{mt_rand(0, 17)};
                }
            }
            break;
        case 5:
            for ((int)$i = 0; $i <= $length; $i++) {
                if (mb_strlen($str) == $length) {
                    $str = $str;
                } else {
                    $rand = "AB12CDabcdE34FGHefghiIJ56jklmnoKLMNOP78pqrstuQRS90TUvwxyzVWXYZ!@#$%^&*()_+=-~`";
                    $str .= $rand{mt_rand(0, 52)};
                }
            }
            break;
    }
    return $str;
}

//判断并生成redis
function isExistRedis($tableName, $val, $source)
{
    $redisVal = json_decode(Redis::get($tableName . ':' . $val))[0];
    if (is_null($redisVal)) {
        if ($source == 0) {
            $db = DB::table($tableName)
                ->where('id', $val)
                ->get();
        } elseif ($source == 1) {
            $db = DB::connection('sqlsrv')->table($tableName)
                ->where('erpid', 895)
                ->where('id', $val)
                ->get();
        }
        //生成redis缓存
        $redisArr[$tableName . ':' . $val] = json_encode($db);
        Redis::mset($redisArr);//提交缓存
        $redisVal = json_decode(Redis::get($tableName . ':' . $val))[0];
    }
    return $redisVal;
}

//操作返回
function getSuccess($cc)
{
    $cc ? '1' : $cc;
    $back = "";
    switch ($cc) {
        case 1:
            $back = ['code' => 0, 'success' => true, 'msg' => '操作成功!'];
            break;
        case 2:
            $back = ['code' => 1, 'success' => false, 'msg' => '操作失败!'];
            break;
        default:
            $back = ['code' => 1, 'success' => false, 'msg' => $cc];
            break;
    }
    return $back;
}

//获取路由的类型
function getRouteType($str)
{
    switch ($str) {
        case 0:
            return '<fount class="layui-btn layui-btn layui-btn-xs">页面</fount>';
            break;
        case 4:
            return '<fount class="layui-btn layui-btn-normal layui-btn-xs">按钮</fount>';
            break;
        case 8:
            return '<fount class="layui-btn layui-btn-warm layui-btn-xs">数据</fount>';
            break;
        default:
            break;
    }
}

//性别   0女 1男
function getSex($str)
{
    if ($str == 1) {
        return '<span class="layui-btn layui-btn-normal layui-btn-xs">男</span>';
    } else {
        return '<span class="layui-btn layui-btn-warm layui-btn-xs">女</span>';
    }
}

//状态   0正常 1锁定
function getIsLock($str)
{
    if ($str == 0) {
        return '<span class="layui-btn layui-btn-normal layui-btn-xs">启用</span>';
    } else {
        return '<span class="layui-btn layui-btn-disabled layui-btn-xs">停用</span>';
    }
}

//通用是否   0否 1是
function getYes($str)
{
    if ($str == 1) {
        return '<span class="layui-btn layui-btn-normal layui-btn-xs">是</span>';
    } else {
        return '<span class="layui-btn layui-btn-warm layui-btn-xs">否</span>';
    }
}

//用户登录状态   sign 登录   logout 注销
function getUserLoginType($str)
{
    if ($str == "sign") {
        return '<span class="layui-btn layui-btn-normal layui-btn-xs">登录</span>';
    } else if ($str == "logout") {
        return '<span class="layui-btn layui-btn-warm layui-btn-xs">注销</span>';
    } else {
        return '<span class="layui-btn layui-btn-disabled layui-btn-xs">未知</span>';
    }
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
//
//
//
//
//=================================== 数据库操作类 ===================================
//通过code获取用户名
function getAdmName($code)
{
    if ($code) {
        if (!Redis::exists('getAdmName:' . $code)) {
            $db = AdmUser::where('code', $code)->select('name')->first();
            Redis::set('getAdmName:' . $code, json_encode($db['name']));
            Redis::expire('getAdmName:' . $code, base()['redisTime']);
            $redisData = json_decode(Redis::get("getAdmName:" . $code));
        } else {
            $redisData = json_decode(Redis::get("getAdmName:" . $code));
        }
        return $redisData;
    }
}

//获取角色权限名称
function getRoleName($id)
{
    if ($id) {
        if (!Redis::exists('getRoleName:' . $id)) {
            $db = AdmRole::where('id', $id)->select('title')->first();
            Redis::set('getRoleName:' . $id, json_encode($db['title']));
            Redis::expire('getRoleName:' . $id, base()['redisTime']);
            $redisData = json_decode(Redis::get("getRoleName:" . $id));
        } else {
            $redisData = json_decode(Redis::get("getRoleName:" . $id));
        }
        return '<span class="layui-btn layui-btn-primary layui-btn-xs">' . $redisData . '</span>';
    }
}

//获取用户所设置的角色
function getUserRole($v)
{
    if ($v == 0) {
        $where = [];
    } else {
        $where = ['is_lock' => 0];
    }
    $db = AdmRole::where(['is_del' => 0])
        ->where($where)
        ->where('add_code', _admCode())//数据权限控制
        ->select('id', 'code', 'title', 'remarks')
        ->get();
    return $db;
}

//通过code获取layer_path 获取省市区地址
function getPubProCit($code)
{
    if ($code) {
        if (!Redis::exists('getPubProCit:' . $code)) {
            $db = PubProvincesCities::where('id', $code)->select('layer_path', 'id', 'title')->first();
            $db_path = PubProvincesCities::whereIn('id', getInjoin($db->layer_path))->select('title')->get();
            $data = [];
            $data['code'] = $db['layer_path'] . ',' . $db['id'];
            $data['title'] = $db_path[0]->title . $db_path[1]->title . $db->title;
            Redis::set('getPubProCit:' . $code, json_encode($data));
            Redis::expire('getPubProCit:' . $code, base()['redisTime']);
            $redisData = json_decode(Redis::get("getPubProCit:" . $code));

        } else {
            $redisData = json_decode(Redis::get("getPubProCit:" . $code));
        }
        return $redisData;
    }
}

//通过无限极获取数据
function getRouteData($s)
{
    $s ?? 0;
    if ($s == 0) {//获取所有未被删除的路由
        $select = '*';
        $where = ['is_sys' => 0];
    }
    if ($s == 1) {//获取所有未被删除且为页面的路由 用于作为菜单使用
        $select = '*';
        $where = ['is_type' => 0];
    }
    if ($s == 2) {//获取所有路由树(页面+数据+按钮)
        $select = getInjoin('id,father_id,title,spread');
        $where =
            function ($query) {
                $query->whereIn('id', explode(',', Redis::get('admPower:' . _admCode())))//数据权限控制
                ->where('is_sys', 0);
            };
    }
    if ($s == 3) {//获取所有路由树(页面+数据+按钮) -> 用于权限使用,所有仅需要id和父id
        $select = getInjoin('id,father_id');
        $where = [];
    }
    if ($s == 4) {//获取所有路由树(页面+数据+按钮)  用于注册时使用, 仅给注册用户管理员权限(去除开发权限)
        $select = getInjoin('id,father_id,title,spread');
        $where =
            function ($query) {
                $query->where('is_sys', 0)
                    ->where('father_id', '>', 0)
                    ->where('is_type', '>', 0);
            };
    }
    $data = Route::where(['is_del' => 0])
        ->select($select)
        ->where($where)
        ->orderBy('father_id', 'asc')
        ->orderBy('is_type', 'desc')
        ->orderBy('by_sort', 'desc')
        ->get()
        ->toArray();
    return $data;
}

//getRouteData 的关联方法,用于生成树
function createTree($data)
{
    $items = [];
    foreach ($data as $value) {
        $items[$value->id] = $value;
    }
    $tree = [];
    foreach ($items as $k => $v) {
        if (isset($items[$v->father_id])) {
            $items[$v->father_id]->children[] = &$items[$k];
        } else {
            $tree[] = &$items[$k];
        }
    }
    return $tree;
}

//获取带有权限控制的左侧导航
function getMenu()
{
    if (Redis::exists('admMenu:' . _admCode())) {
        $redisData = json_decode(Redis::get('admMenu:' . _admCode()));
    } else {
        _admPower();
        $routeIds = objectToArray(Redis::get('admPower:' . _admCode()));
        $data = Route::where(['is_del' => 0])
            ->where(['is_type' => 0])
            ->whereIn('id', getInjoin($routeIds))
            ->orderBy('father_id', 'asc')
            ->orderBy('is_type', 'desc')
            ->orderBy('by_sort', 'desc')
            ->get()
            ->toArray();
        //
        Redis::set('admMenu:' . _admCode(), json_encode($data));
        $redisData = json_decode(Redis::get('admMenu:' . _admCode()));
    }
    return createTree($redisData);
}

//获取路由管理页面当中的数据 - > 仅提供给路由管理页面使用
function getRouteMabage()
{
    return createTree(arrayToObject(getRouteData(0)));
}

//读取所有数据与按钮的路由id,用于角色权限保存时,仅保存有数据与按钮的id  仅提供给getRouteDataValue方法使用
function getRouteTypeData()
{
    $data = Route::where(['is_del' => 0])
        ->wherein('is_type', ['4', '8'])
        ->select('id')
        ->get();
    foreach ($data as $k => $v) {
        $list[] = $v['id'];
    }
    return $list;
}

//在写入角色表时,仅保存类型为 数据和按钮的数据
function getRouteDataValue($str, $val)
{
//往角色关联表写入数据
    $list = [];
    foreach ($str as $k => $v) {
        if (strpos($k, 'layuiTreeCheck_') !== false) {
            if ($v > 0) {
                if (in_array($v, getRouteTypeData())) {
                    $list[] = array('role_id' => $val, 'route_id' => $v, 'add_time' => getTime(1));
                }
            }
        }
    }
    return $list;
}

//对象转数组
function objectToArrays($d)
{
    if (is_object($d)) {
        // Gets the properties of the given object
        // with get_object_vars function
        $d = get_object_vars($d);
    }

    if (is_array($d)) {
        /*
        * Return array converted to object
        * Using __FUNCTION__ (Magic constant)
        * for recursive call
        */
        return array_map(__FUNCTION__, $d);
    } else {
        // Return array
        return $d;
    }
}

//通过数据库获取id,然后使用id在redis中获取已存在的缓存
function getRedisData($table, $whereArr, $fieldArr)
{
    $where =
        function ($query) use ($whereArr) {
            if (!empty($whereArr)) {
                $query->where($whereArr);
            }
        };
    $db = DB::table($table)
        ->select('id')
        ->where($where)
        ->where(['is_del' => 0, 'is_lock' => 0])
        ->orderBy('by_sort', 'desc')
        ->orderBy('add_time', 'asc')
        ->get()
        ->toArray();
    //读取缓存
    $data = [];
    foreach ($db as $k => $v) {
        $this_id = $v->id;//当前id
        $redisVal = json_decode(Redis::get($table . ':' . $this_id));
        $list = '';
        for ($i = 0; $i < count(getInjoin($fieldArr)); $i++) {
            $z = getInjoin($fieldArr)[$i];
            $list = $list . ',' . $redisVal->$z;
        }
        $arr = getInjoin($fieldArr);
        $brr = getInjoin($list);
        $crr = array_combine($arr, $brr);
        $data[] = $crr;
    }
    return $data;
}


//通过某些特征, 在数据库中获取对应的数据
function getDbData($table, $whereArr, $fieldArr, $source)
{
    $where =
        function ($query) use ($whereArr) {
            if (!empty($whereArr)) {
                $query->where($whereArr);
            }
        };
    //
    $list = '';
    for ($i = 0; $i < count(getInjoin($fieldArr)); $i++) {
        $z = getInjoin($fieldArr)[$i];
        $list = $list . ',' . $z;
    }
    //
    if ($source == 0) {
        $data = DB::table($table)
            ->select(getInjoin($list))
            ->where($where)
            ->where(['is_del' => 0, 'is_lock' => 0])
            ->orderBy('by_sort', 'desc')
            ->orderBy('add_time', 'asc')
            ->get()
            ->toArray();
    } elseif ($source == 0.5) {
        $data = DB::table($table)
            ->select(getInjoin($list))
            ->where($where)
            ->get()
            ->toArray();
    } elseif ($source == 1) {
        $data = DB::connection('sqlsrv')->table($table)
            ->select(getInjoin($list))
            ->where($where)
            ->get()
            ->toArray();
    }
    return $data;
}

//缓存全国省市地址
function getCitysData()
{
    $PubProvincesCitiesRedisData = json_decode(Redis::get("PubProvincesCities"));
    if (empty($PubProvincesCitiesRedisData)) {
        $pubProCit = PubProvincesCities::select('id', 'id as value', 'title as label', 'father_id')->get();
        Redis::set('PubProvincesCities', json_encode(createTree(json_decode($pubProCit))));
        Redis::expire('PubProvincesCities', base()['redisTime']);
        $PubProvincesCitiesRedisData = json_decode(Redis::get("PubProvincesCities"));
    } else {
        $PubProvincesCitiesRedisData = json_decode(Redis::get("PubProvincesCities"));
    }
    return json_encode($PubProvincesCitiesRedisData);
}

function getIsExist($table, $str, $val, $isDel)
{
    $isDel == '' ? 0 : 1;
    $where =
        function ($query) use ($isDel) {
            if ($isDel == 0) {
                $query->where('is_del', 0);
            }
        };
    $data = DB::table($table)
        ->where($where)
        ->where($str, $val)
        ->count();
    return $data;
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
//
//
//
//
//=================================== 通用操作方法类 ===================================
//物理删除redis缓存
function setDelRedis($table, $val)
{
    if (Redis::exists($table . $val)) {
        Redis::del($table . $val);//销毁redis
    }
}

//物理删除
function setDestroy($table, $val)
{
    $data = DB::table($table)
        ->whereIn('id', getInjoin($val))
        ->delete();
    //日志记录
    $logList = [];
    $vv = [];
    if ($data > 0) {
        foreach (getInjoin($val) as $k => $v) {
            $vv['type'] = '逻辑删除';
            $vv['this_id'] = $v;
            $content = [
                "content" => '当前项目已被逻辑删除'
            ];
            $vv['content'] = json_encode($content);
            $logList[] = $vv;
        }
        opLog($table, $logList);
    }
    return $data;
}

//逻辑删除(伪删除)getRouteData
function setDel($table, $val)
{
    $data = DB::table($table)
        ->where('is_del', 0)
        ->whereIn('id', getInjoin($val))
        ->update(['is_del' => 1, 'del_code' => _admCode(), 'del_time' => getTime(1)]);
    setDelRedis($table, $val);
    //日志记录
    $logList = [];
    $vv = [];
    if ($data > 0) {
        foreach (getInjoin($val) as $k => $v) {
            $vv['type'] = '删除';
            $vv['this_id'] = $v;
            $content = [
                "content" => '当前项目已被删除'
            ];
            $vv['content'] = json_encode($content);
            $logList[] = $vv;
        }
        opLog($table, $logList);
    }
    return $data;
}

//锁定
function setLock($table, $val)
{
    $data = DB::table($table)
        ->where('is_lock', 0)
        ->whereIn('id', getInjoin($val))
        ->update(['is_lock' => 1, 'up_code' => _admCode(), 'up_time' => getTime(1)]);
    //日志记录
    $logList = [];
    $vv = [];
    if ($data > 0) {
        foreach (getInjoin($val) as $k => $v) {
            $vv['type'] = '停用';
            $vv['this_id'] = $v;
            $content = [
                "content" => '当前项目已被停用'
            ];
            $vv['content'] = json_encode($content);
            $logList[] = $vv;
        }
        opLog($table, $logList);
    }
    return $data;
}

//解锁
function setNoLock($table, $val)
{
    $data = DB::table($table)
        ->where('is_lock', 1)
        ->whereIn('id', getInjoin($val))
        ->update(['is_lock' => 0, 'up_code' => _admCode(), 'up_time' => getTime(1)]);
    //日志记录
    $logList = [];
    $vv = [];
    if ($data > 0) {
        foreach (getInjoin($val) as $k => $v) {
            $vv['type'] = '启用';
            $vv['this_id'] = $v;
            $content = [
                "content" => '当前项目已被启用'
            ];
            $vv['content'] = json_encode($content);
            $logList[] = $vv;
        }
        opLog($table, $logList);
    }
    return $data;
}

//快捷编辑
function setTableEdit($table, $id, $field, $val, $source)
{
    $fieldName = '';
    if ($field == 'by_sort' || $field == 'bySort') {
        if ($val > 1000) {
            return '排序最大值请控制在1000以内';
        }
        $fieldName = '排序';
    } elseif ($field == 'satisfaction') {
        if ($val > 100 || $val < 0) {
            return '满意度的区间值请控制在 0-100';
        }
        $fieldName = '满意度';
    } elseif ($field == 'ordBak') {
        $fieldName = '订单备注';
    }
    if ($source == 0) {
        $data = DB::table($table)
            ->whereIn('id', getInjoin($id))
            ->update([$field => $val, 'up_code' => _admCode(), 'up_time' => getTime(1)]);
    } elseif ($source == 1) {
        $data = DB::connection('sqlsrv')->table($table)
            ->where('erpid', 895)
            ->whereIn('id', getInjoin($id))
            ->update([$field => $val]);
    }
    //
    //日志记录
    $logList = [];
    $vv = [];
    if ($data > 0) {
        foreach (getInjoin($id) as $k => $v) {
            $vv['type'] = '快捷编辑';
            $vv['this_id'] = $v;
            $content = [
                "content" => '当前项目 [' . $fieldName . '] 已被修改为' . $val
            ];
            $vv['content'] = json_encode($content);
            $logList[] = $vv;
        }
        opLog($table, $logList);
    }
    //
    return $data;
}

//通用方法找父亲
function zFatherId($id, $db)
{
    $list = [];
    foreach ($db as $k => $v) {
        if (in_array($v['id'], [$id])) {
            $list[] = $v['id'];
            if ($v['father_id'] > 0) {
                //3.循环遍历
                $list[] = zFatherId($v['father_id'], $db);
            }
        }
    }
    $listA = json_encode(array_reverse($list));
    $listB = str_replace("[", "", $listA);
    $listC = str_replace("]", "", $listB);
    $listD = str_replace("\\", "", $listC);
    $listE = str_replace("\"", "", $listD);
    return $listE;
}

//通用方法找儿子.孙子...
function zSonId($id, $db)
{
    $list = [];
    foreach ($db as $k => $v) {
        if (in_array($v['father_id'], [$id])) {
            //3.循环遍历
            $list[] = $v['id'];
            $list[] = zSonId($v['id'], $db);
        }
    }
    $listA = json_encode(array_reverse($list));
    $listB = str_replace("[", "", $listA);
    $listC = str_replace("]", "", $listB);
    $listD = str_replace("\\", "", $listC);
    $listE = str_replace("\"", "", $listD);
    $listF = str_replace(",,", ",", $listE);
    return $listF;
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
//
//
//
//
//=================================== 自定义用户私有方法类 ===================================
//获取当前用户Id
function _admId()
{
    return \Cookie::get('admId');
}

//获取当前用户open_id
function _admOpenId()
{
    return \Cookie::get('admOpenId');
}

//获取当前用户code
function _admCode()
{
    return \Cookie::get('admCode');
}

//获取当前用户名称
function _admName()
{
    return \Cookie::get('admName');
}

//获取当前私有账号所拥有的路由权限id
function _admPower()
{
    if (Redis::exists('admPower:' . _admCode())) {
        return Redis::get('admPower:' . _admCode());
    } else {
        $roles = AdmUser::find(_admId());
        $roles = $roles->admUserRole;
        $arr = [];
        //获取到当前用户拥有的按钮权限id
        foreach ($roles as $v) {
            $routes = $v->route;
            foreach ($routes as $route) {
                $arr[] = $route->id;
            }
        }
        //获取到所有路由的id与父id 用于与当前用户id进行比较和遍历
        $routeAll = getRouteData(3);
        //1.首先遍历系统中所有的路由id
        foreach ($routeAll as $k => $v) {
            //2.从所有权限中遍历 是否存在当前的用户权限, 如果存在则记录当前权限id,并且继续找父级
            if (in_array($v['id'], $arr)) {
                $arr[] = $v['id'];
                if ($v['father_id'] > 0) {
                    //3.循环遍历
                    $arr[] = father($v['father_id'], $routeAll);
                }
            }
        }
        $arr = array_unique(mergeArr($arr)); //将所有递归的数组合并后进行去重
        $arrs = collect();//使用数组保存当前用户所拥有的权限id
        foreach ($arr as $k => $v) {
            $arrs->push($v);
        }
        Redis::set('admPower:' . _admCode(), substr($arrs, 1, -1));
        return Redis::get('admPower:' . _admCode());
    }
}

//与_admPower配合使用, 用于找到当前权限的所有父亲与顶级;
function father($f_id, $routeAll)
{
    //获取到传过来的父id后,与所有id进行匹配
    $list = [];
    foreach ($routeAll as $k => $v) {
        //2.从所有权限中遍历 是否存在当前的用户权限, 如果存在则记录当前权限id,并且继续找父级
        if (in_array($v['id'], [$f_id])) {
            $list[] = $v['id'];
            if ($v['father_id'] > 0) {
                //3.循环遍历
                $list[] = father($v['father_id'], $routeAll);
            }
        }
    }
    return $list;
}

//将某个带有层级的数组合并为一个数组集合
function mergeArr($arr)
{
    $result = array();
    foreach ($arr as $key => $val) {
        if (is_array($val)) {
            $result = array_merge($result, mergeArr($val));
        } else {
            $result[] = $val;
        }
    }
    return $result;
}

//授权控制,通过数组来查找
function hasPower($routeId)
{
    //在用户权限组如果找到了对应权限 则返回真,否则返回假
    return in_array($routeId, explode(',', Redis::get('admPower:' . _admCode()))) ? true : false;
}

//重新生成登录账号相关的cache
function _admCache($admCode)
{
    //redis
    setDelRedis('admMenu:', $admCode);//销毁菜单(数组+字符串)
    setDelRedis('admPower:', $admCode);//菜单及按钮权限值(字符串)
    setDelRedis('getAdmName:', $admCode);//销毁与当前账号相关的获取方法
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
//
//
//
//
//=================================== 自定义日志方法类 ===================================
//文件日志记录
function fileLog($data)
{
    $logList = [];
    $logList[] = $data['info'];
    $logList[] = $data['data'];
    $info = $logList;
    switch ($data['type']) {
        case '0':
            Log::debug($info);
            break;
        case '1':
            Log::info($info);
            break;
        case '2':
            Log::notice($info);
            break;
        case '3':
            Log::warning($info);
            break;
        case '4':
            Log::error($info);
            break;
        case '5':
            Log::critical($info);
            break;
        case '6':
            Log::alert($info);
            break;
        case '7':
            Log::emergency($info);
            break;
        default:
            break;
    }
}

//
//
//
//通用操作日志
function opLog($tableNae, $data)
{
//    $logList = [];
//    foreach ($data as $k => $v) {
//        $v['add_code'] = _admCode() ? _admCode() : $v['add_code'] ?? '0000000000';
//        $v['add_time'] = getTime(1);
//        $logList[] = $v;
//    }
//    return logSystem($tableNae, $logList);
}

//日志读取内容转换
function contentStr($todo, $obj)
{
    $todo = $todo . ',content';
    $obj = str_replace("{", "", $obj);
    $obj = str_replace("}", "", $obj);
    $obj = str_replace("\"", "", $obj);
    $str = explode(',', $obj);
    $list = [];
    for ($index = 0; $index < count($str); $index++) {
        $strs = explode(':', $str[$index],);
        if (in_array($strs[0], getInjoin($todo)) || $todo == 'null') {
            $list[] = contentStrCn($todo, unicode_decode($str[$index]));
        }
    }
    return $list;
}

//日志内容拆分
function contentStrCn($todo, $str)
{
    if ($todo !== 'null') {
        $str = str_replace("orderId", "订单号", $str);
    }
    return $str;
}

//
//
//
//核心通用日志记录
function logSystem($tableName, $list)
{
    $LOG = DB::connection('logSystem')
        ->table($tableName)
        ->insert($list);
    if ($LOG) {
        return 1;
    } else {
        return 0;
    }
}

//
//
//
//核心通用通知消息
function noticeSystem($data, $to)
{
    $logList = [];
    foreach ($to as $k => $v) {
        $_email = _getAdmICQ($v)['email'];
        $_mobile = _getAdmICQ($v)['mobile'];
        if (($data['type'] == 1 && $_email <> '') || ($data['type'] == 2 && $_mobile <> '') || ($data['type'] == 3 && ($_email <> '' || $_mobile <> ''))) {
            $logList[] = [
                'fromEmail' => $data['content']['fromEmail'],
                'fromEmailPwd' => $data['content']['fromEmailPwd'],
                'stmp' => $data['content']['stmp'],
                'type' => $data['type'],
                'subject' => $data['content']['subject'],
                'content' => $data['content']['content'],
                'toEmail' => $_email ?? '',
                'toMobile' => $_mobile ?? '',
            ];
        }
    }
    if ($logList) {
        $apiRes = callWebApi(['notice' => $logList], site()['callWebApi'] . 'api/email/send');//调内部接口
        if ($apiRes->code == 200) {
            return 0;
        } else {
            return getSuccess($apiRes->msg);
        }
    } else {
        fileLog(['type' => 3, 'info' => '请求消息通知接口->收信方不存在,发送失败=>', 'data' => ['data' => $data, 'to' => $to]]);
    }
}
