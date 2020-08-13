<?php
//require 'ueditor/php/qiniu/autoload.php';
use Qiniu\Auth;

//后台动态参数配置
function site()
{
    //基础信息
    $data = [];
    $data['doMain'] = URL::previous();
    $data['ip'] = $_SERVER['REMOTE_ADDR'];
    $data['browser'] = $_SERVER['HTTP_USER_AGENT'];
    $data['callWebApi'] = env('APP_CallWebApi');
    //获取微站配置 pub_site_config
    $redisVal = isExistRedis('pub_site_config', 1, 0);//判断并生成redis
    $data['title'] = $redisVal->title;
    $data['siteWebName'] = $redisVal->title . ' - ' . $redisVal->slogan;
    $data['homeTitle'] = $redisVal->title;
    $data['siteICP'] = $redisVal->siteICP;
    $data['ico'] = $redisVal->ico;
    $data['keywords'] = $redisVal->keywords;
    $data['description'] = $redisVal->description;
    $data['default_picture'] = $redisVal->default_picture;
    return $data;
}

//针对前端框架的初始值
function frame()
{
    $data = [];
    $data['limit'] = 12;//默认每页数量
    $data['limits'] = '12, 15, 30, 45, 60, 100, 200';//默认每页显示数量
    $data['message'] = '1.状态在停用下才可以被删除哦~\t\r 2.如果发现没有操作按钮,请先确定是否有相应权限~\t\r 3.点击状态即可查看相关日志信息哦~';
    return $data;
}

//七牛
function qiniu()
{
    $auth = new Auth(env('QINIU_ACCESS_KEY'), env('QINIU_SECRET_KEY'));
    $data = [];
    $data['QINIU_PUBT64'] = env('QINIU_PUBT64');
    $data['QINIU_DOMAIN'] = env('QINIU_DOMAIN');
    $data['QINIU_TOKEM'] = $auth->uploadToken(env('QINIU_BUCKET'));
    return $data;
}

