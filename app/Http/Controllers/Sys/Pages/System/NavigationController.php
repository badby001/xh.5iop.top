<?php

namespace App\Http\Controllers\Sys\Pages\System;

use App\Http\Controllers\Controller;
use App\Model\Pages\System\Navigation;
use App\Model\Pages\System\NavigationLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class NavigationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('.sys.pages.system.navigation');
    }


    public function read(Request $request)
    {
        $inp = $request->all();
        $db = Navigation::select('id', 'title', 'father_id', 'img_url', 'layer_path', 'is_lock', 'by_sort', 'type', 'classify')
            ->where('is_del', 0)
            ->orderBy('is_lock', 'asc')
            ->orderBy('by_sort', 'desc')
            ->orderBy('add_time', 'asc')
            ->get();
        //生成redis缓存
        $redisArr = [];
        foreach ($db as $k => $v) {
            $redisArr['pub_navigation:' . $v->id] = json_encode($v);//redis不存在,获取数据库
        }
        Redis::mset($redisArr);//提交缓存
        //读取缓存
        $dbData = [];
        foreach ($redisArr as $k => $v) {
            $this_id = json_decode($v)->id;//当前id
            $redisVal = json_decode(Redis::get('pub_navigation:' . $this_id));
            $dbData[] = [
                'id' => $redisVal->id,
                'father_id' => $redisVal->father_id,
                'img_url' => $redisVal->img_url,
                'layer_path' => $redisVal->layer_path,
                'label' => $redisVal->title,
                'type' => $redisVal->type,
                'classify' => $redisVal->classify,
            ];
        }
        $data = [];
        $children = [];
        $children['label'] = '导航根目录';
        $children['id'] = 0;
        $children['spread'] = true;
        $children['children'] = createTree(arrayToObject($dbData));;
        $data['code'] = 0;
        $data['msg'] = '查询成功';
        $data['data'] = [$children];
        return $data;

    }


    //拖动
    public function target(Request $request)
    {
        $inp = $request->all();
        //首先获取到当前要拖动的节点
        $current_id = $inp['current']['id'];
        //获取到目标节点id
        $target_id = $inp['target']['id'];
        //获取到目标节点id的layer_path
        $target_db = Navigation::select('layer_path')
            ->where(['id' => $target_id, 'is_lock' => 0, 'is_del' => 0])
            ->get();
        $layer_path = $target_db[0]['layer_path'] ?? 0;
        //
        //
        //进行位置移动
        $db = Navigation::find($current_id);
        $db['father_id'] = $target_id;
        if ($layer_path > 0) {
            $db['layer_path'] = $layer_path . ',' . $target_id;
        } else {
            $db['layer_path'] = $target_id;
        }
        $db->save();
        //
        $navigation_db = Navigation::select('id', 'father_id')->where(['is_lock' => 0, 'is_del' => 0])->get();//拿到所有的导航信息进行找父级所使用
        //根据当前要拖动的节点获取所有相关的儿子节点
        $db = Navigation::select('id', 'father_id', 'title')
            ->where(['layer_path' => $current_id, 'is_lock' => 0, 'is_del' => 0])
            ->orWhere('layer_path', 'like', $current_id . ',%')
            ->orWhere('layer_path', 'like', '%,' . $current_id)
            ->orWhere('layer_path', 'like', '%,' . $current_id . ',%')
            ->get();
        //return $db;
        //父级移动后,移动子集
        foreach ($db as $k => $v) {
            //通过当前father_id进行遍历组合 layer_path
            $layer_paths = zFatherId($v['id'], $navigation_db);
            $layer_paths = getInjoin($layer_paths);
            $layer_paths_list = '';
            for ($index = 0; $index < count($layer_paths) - 1; $index++) {
                $layer_paths_list = $layer_paths_list . ',' . $layer_paths[$index];
            }
            DB::table('pub_navigation')->where('id', $v['id'])->update(['layer_path' => substr($layer_paths_list, 1)]);
        }
        opLog('pub_navigation', [['type' => '拖动', 'this_id' => $current_id, 'content' => json_encode($inp)]]);//记录日志
        return getSuccess(1);
    }

    //排序
    public function sort(Request $request)
    {
        $inp = $request->all();
        $i = count($inp['sort']);
        foreach ($inp['sort'] as $k => $v) {
            Navigation::where('id', $v['id'])->update(['by_sort' => $i]);
            $i--;
        }
        return getSuccess(1);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $inp = $request->all();
        $this_father_id = $inp['this_father_id'] ?? $inp['father_id'];
        if ($this_father_id == 0) {
            $father_id = 0;
        } else {
            $father_id = $inp['father_id'];
        }
        //根据父id获取到树路径
        $fdb = Navigation::where('id', $father_id)->select('layer_path', 'classify')->first();
        $layer_path = $fdb['layer_path'];
        $db = new Navigation();
        $db['title'] = $inp['title'];
        $db['layer_path'] = $layer_path == 0 ? '' . $father_id : $layer_path . ',' . $father_id;
        $db['img_url'] = 'https://cdn.op110.com.cn/lib/imgs/weidian/i_menu_10.png';
        $db['father_id'] = $father_id;
        $db['classify'] = $fdb['classify'] ?? 0;
        $db['add_code'] = _admCode();
        $db['add_time'] = getTime(1);
        if ($db->save()) {
            //生成redis缓存
            $redisArr['pub_navigation:' . $db->id] = json_encode($db);
            Redis::mset($redisArr);//提交缓存
            $res = [];
            $res['id'] = $db['id'];
            $res['title'] = $inp['title'];
            $res['code'] = 0;
            $res['msg'] = '操作成功!';
            //
            opLog('pub_navigation', [['type' => '添加', 'this_id' => $db['id'], 'content' => json_encode($inp)]]);//记录日志
            return $res;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $db = Navigation::where('id', $id)->first();
        //寻找导航下方是否还存在子项,如果存在,则获取所有子项id
        $navId = '';
        $navDb = Navigation::where(['is_lock' => 0, 'is_del' => 0])->select('id', 'layer_path')->get();
        foreach ($navDb as $k => $v) {
            if (in_array($id, getInjoin($v['layer_path']))) {
                $navId = $navId . ',' . $v['id'];
            }
        }
        $inp['navId'] = $id . $navId;
        $db['navCount'] =NavigationLine::whereIn('navigation_id', getInjoin($inp['navId']))->count();
        return View('.sys.pages.system.navigationEdit', ['db' => $db]);
    }

    public function nodeEdit(Request $request, $id)
    {
        //
        $inp = $request->all();
        if ($id == 0) {
            return getSuccess('当前导航名称不允许被修改哦');
        }
        $db = Navigation::find($id);
        $db['title'] = $inp['title'];
        $db['up_code'] = _admCode();
        $db['up_time'] = getTime(1);
        $db->save();
        //生成redis缓存
        $redisArr['pub_navigation:' . $db->id] = json_encode($db);
        Redis::mset($redisArr);//提交缓存
        opLog('pub_navigation', [['type' => '修改', 'this_id' => $id, 'content' => json_encode($inp)]]);//记录日志
        return getSuccess(1);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $inp = $request->all();
        $db = Navigation::find($id);
        //
        $db['title'] = $inp['title'];
        $db['img_url'] = $inp['img_url'];
        $db['is_lock'] = $inp['is_lock'] == "0" ? 0 : 1;
        $db['by_sort'] = $inp['by_sort'];
        if (isset($inp['classify'])) {
            $db['classify'] = $inp['classify'];
        }
        $db['up_code'] = _admCode();
        $db['up_time'] = getTime(1);
        $db->save();
        //生成redis缓存
        $redisArr['pub_navigation:' . $db->id] = json_encode($db);
        Redis::mset($redisArr);//提交缓存
        opLog('pub_navigation', [['type' => '修改', 'this_id' => $id, 'content' => json_encode($inp)]]);//记录日志
        return getSuccess(1);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        if (getIsExist('pub_navigation', 'father_id', $id, 0) > 0) {
            return getSuccess('当前导航存在下级,无法进行删除操作');
        }
        if (getIsExist('pub_navigation_line', 'navigation_id', $id, 1) > 0) {
            return getSuccess('当前导航存在绑定的产品, 无法进行删除操作');
        }
        setDel('pub_navigation', $id);
        return getSuccess(1);
    }
}
