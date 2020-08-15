<?php

//后台动态参数配置
function site()
{
    //基础信息
    $data = [];
    $data['doMain'] = URL::previous();
    $data['ip'] = $_SERVER['REMOTE_ADDR'];
    $data['browser'] = $_SERVER['HTTP_USER_AGENT'];
    $data['callWebApi'] = env('APP_CallWebApi');
    $data['title'] = '不满意办件督办系统';
    $data['siteWebName'] ='西湖街道 - 不满意办件督办系统';
    $data['ico'] = '';
    $data['keywords'] = ' ';
    $data['description'] = ' ';
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
