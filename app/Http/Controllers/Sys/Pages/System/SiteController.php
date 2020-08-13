<?php

namespace App\Http\Controllers\Sys\Pages\System;

use App\Http\Controllers\Controller;
use App\Model\Pages\System\SiteConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class SiteController extends Controller
{
    //

    //站点读取(网站+微站)
    function siteConfig()
    {
        $db = SiteConfig::find(1);
        return view('.sys.pages.system.siteConfig', ['db' => $db]);
    }

    //微站保存
    function microSiteSave(Request $request)
    {
        $inp = $request->all();
        SiteConfig::where('id', 1)
            ->update([
                'title' => $inp['title'],
                'slogan' => $inp['slogan'],
                'script' => $inp['script'],
                'wechat_icon' => $inp['wechat_icon'],
                'wechat_describe' => $inp['wechat_describe'],
                'up_code' => _admCode(),
                'up_time' => getTime(1)
            ]);
        $db = SiteConfig::find(1)->get();
        //生成redis缓存
        $redisArr['pub_site_config:1'] = json_encode($db);
        Redis::mset($redisArr);//提交缓存
        opLog('pub_logs', [['type' => '微站配置', 'this_id' => 1, 'content' => json_encode($inp)]]);//记录日志
        return getSuccess(1);
    }


    //通用保存
    function alikeSiteSave(Request $request)
    {
        $inp = $request->all();
        SiteConfig::where('id', 1)
            ->update([
                'display_price' => $inp['display_price'] * 1,
                'remaining_position' => $inp['remaining_position'],
                'default_picture' => $inp['default_picture'],
                'tj_script' => $inp['tj_script'],
                'up_code' => _admCode(),
                'up_time' => getTime(1),
                'ico' => $inp['ico'],
                'siteICP' => $inp['siteICP'],
                'keywords' => $inp['keywords'],
                'description' => $inp['description'],
            ]);
        $db = SiteConfig::find(1)->get();
        //生成redis缓存
        $redisArr['pub_site_config:1'] = json_encode($db);
        Redis::mset($redisArr);//提交缓存
        opLog('pub_logs', [['type' => '通用配置', 'this_id' => 1, 'content' => json_encode($inp)]]);//记录日志
        return getSuccess(1);
    }


    //客服信息保存
    function serInfoSiteSave(Request $request)
    {
        $inp = $request->all();
        SiteConfig::where('id', 1)
            ->update([
                'service_info' => $inp['service_info'],
                'up_code' => _admCode(),
                'up_time' => getTime(1)
            ]);
        $db = SiteConfig::find(1)->get();
        //生成redis缓存
        $redisArr['pub_site_config:1'] = json_encode($db);
        Redis::mset($redisArr);//提交缓存
        opLog('pub_logs', [['type' => '客服信息', 'this_id' => 1, 'content' => json_encode($inp)]]);//记录日志
        return getSuccess(1);
    }


}
