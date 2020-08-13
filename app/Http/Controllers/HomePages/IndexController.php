<?php

namespace App\Http\Controllers\HomePages;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    //前端首页
    public function index()
    {
        //获取广告
        $adData = getRedisData('pub_advertisement', [], 'title,img_url,url');
        //
        //导航菜单
        $navData = getRedisData('pub_navigation', ['father_id' => 0, 'is_del' => 0], 'title,img_url');
        return view('.homePages.index', ['pub_advertisement' => $adData, 'pub_navigation' => $navData]);
    }
}
