<?php

namespace App\Http\Controllers\Sys\Pages\System;

use App\Http\Controllers\Controller;
use App\Model\Pages\System\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
//        $data = Menu::where(['is_del' => 0])
//            ->select('id', 'father_id','title', 'href', 'fontFamily', 'icon', 'spread', 'isCheck','is_type')
//            //->with(['children:id,father_id,title,href,fontFamily,icon,spread'])
//            ->get();
//        return $data;
        return view('.sys.pages.routes.route', ['data' => getRouteMabage()]);
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
        //获取到view返回参数,并进行判断是否已经存在一样的名称
        $inp = $request->all();
        if (getIsExist('pub_menu_route', 'title', $inp['route_title'], 0)) {
            return getSuccess('路由名称已存在');
        }
        $db = new Route();
        $db['title'] = $inp['route_title'];
        $db['font_family'] = 'layui-icon';
        $db['icon'] = '&#xe602;';
        $db['by_sort'] = 0;
        $db['father_id'] = 0;
        $db['is_sys'] = 0;
        if ($db->save()) {
            opLog('pub_menu_route', [['type' => '添加', 'this_id' => $db->id, 'content' => json_encode($inp)]]);//记录日志
            return getSuccess(1);
        } else {
            return getSuccess(2);
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
        $data = [];
        $data['father_id'] = $id;
        $data['is_type'] = 2;
        $data['font_family'] = 'layui-icon';
        $data['is_sys'] = '0';
        //
        return view('.sys.pages.routes.routeSon', $data);
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
        $db = Route::find($id);
        return view('.sys.pages.routes.edit', $db);
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
//        return $inp;
        $db = Route::find($id);
        $db['title'] = $inp['title'];
        $db['href'] = $inp['href'];
        $db['font_family'] = $inp['font_family'];
        $db['icon'] = $inp['icon'];
        $db['is_sys'] = $inp['is_sys'];
        $db['by_sort'] = $inp['by_sort'];
        if ($db->save()) {
            opLog('pub_menu_route', [['type' => '修改', 'this_id' => $id, 'content' => json_encode($inp)]]);//记录日志
            return getSuccess(1);
        } else {
            return getSuccess(2);
        }
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
        if (!$id) {
            return getSuccess(2);
        }
        $db = Route::find($id);
        $db['is_del'] = 1;
        $db->save();
        opLog('pub_menu_route', [['type' => '删除', 'this_id' => $id, 'content' => '删除当前项目']]);//记录日志
        return getSuccess(1);
    }


    public function storeSon(Request $request)
    {
        //获取到view返回参数,并进行判断是否已经存在一样的名称
        $inp = $request->all();
//        if (getIsExist('menu', 'title', $inp['title'])) {
//            return getSuccess('路由名称已存在');
//        }
//        return $inp;
        if ($inp['id'] == 0) {
            $db = new Route();
        } else {
            $db = Route::find($inp['id']);
        }
        $db['title'] = $inp['title'];
        $db['href'] = $inp['href'] ?? '/';
        $db['is_type'] = $inp['is_type'];
        $db['font_family'] = $inp['font_family'];
        $db['icon'] = $inp['icon'];
        $db['by_sort'] = $inp['by_sort'];
        $db['father_id'] = $inp['father_id'];
        $db['is_sys'] = $inp['is_sys'] * 1;
        if ($db->save()) {
            opLog('pub_menu_route', [['type' => '子项', 'this_id' => $db->id, 'content' => json_encode($inp)]]);//记录日志
            return getSuccess(1);
        } else {
            return getSuccess(2);
        }
    }

    public function routeSonEdit($id)
    {
        $db = Route::find($id);
        return view('.sys.pages.routes.routeSon', $db);
    }


    public function buttonPackage($id)
    {
        DB::table('pub_menu_route')->insertOrIgnore([
            ['father_id' => $id, 'title' => '数据读取', 'href' => '/', 'font_family' => 'layui-icon', 'icon' => 'layui-icon-right', 'is_type' => '8', 'by_sort' => '0'],
            ['father_id' => $id, 'title' => '添加', 'href' => '/', 'font_family' => 'layui-icon', 'icon' => 'layui-icon-right', 'is_type' => '4', 'by_sort' => '4'],
            ['father_id' => $id, 'title' => '修改', 'href' => '/', 'font_family' => 'layui-icon', 'icon' => 'layui-icon-right', 'is_type' => '4', 'by_sort' => '3'],
            ['father_id' => $id, 'title' => '启用/停用', 'href' => '/', 'font_family' => 'layui-icon', 'icon' => 'layui-icon-right', 'is_type' => '4', 'by_sort' => '2'],
            ['father_id' => $id, 'title' => '删除', 'href' => '/', 'font_family' => 'layui-icon', 'icon' => 'layui-icon-right', 'is_type' => '4', 'by_sort' => '1']
        ]);
        opLog('pub_menu_route', [['type' => '按钮套餐', 'this_id' => $id, 'content' => '按钮套餐已生成']]);//记录日志
        return getSuccess(1);
    }

}
