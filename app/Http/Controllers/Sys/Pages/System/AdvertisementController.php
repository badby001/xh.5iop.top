<?php

namespace App\Http\Controllers\Sys\Pages\System;

use App\Http\Controllers\Controller;
use App\Model\Pages\System\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class AdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('.sys.pages.advertisement.ad');
    }


    public function read(Request $request)
    {

        $inp = $request->all();
        $where =
            function ($query) use ($inp) {
                if (isset($inp['is_lock'])) {
                    $query->where('is_lock', $inp['is_lock'] == "n" ? 1 : 0);
                }
                if (isset($inp['key'])) {
                    $query->where('title', 'like', '%' . $inp['key'] . '%');
                }
                if (isset($inp['dateType'])) {
                    if ($inp['dateType'] == 'addTime') {
                        if (isset($inp['start_time']) && isset($inp['end_time'])) {
                            $query->whereBetween('add_time', [$inp['start_time'], $inp['end_time']]);
                        } else if (isset($inp['start_time'])) {
                            $query->where('add_time', '>=', $inp['start_time']);
                        } else if (isset($inp['end_time'])) {
                            $query->where('add_time', '<=', $inp['end_time']);
                        }
                    }
                }
            };

        $db = Advertisement::select('id', 'code', 'type', 'title', 'img_url', 'url', 'describe', 'by_sort', 'is_lock', 'add_code', 'add_time', 'up_code', 'up_time')
            ->where('is_del', 0)
            ->where($where)
            ->orderBy('is_lock', 'asc')
            ->orderBy('by_sort', 'desc')
            ->orderBy('add_time', 'asc')
            ->paginate($inp['limit'])
            ->all();
        //生成redis缓存
        $redisArr = [];
        foreach ($db as $k => $v) {
            $redisArr['pub_advertisement:' . $v->id] = json_encode($v);//redis不存在,获取数据库
        }
        Redis::mset($redisArr);//提交缓存
        //读取缓存
        $dbData = [];
        foreach ($redisArr as $k => $v) {
            $this_id = json_decode($v)->id;//当前id
            $redisVal = json_decode(Redis::get('pub_advertisement:' . $this_id));
            $dbData[] = [
                'id' => $redisVal->id,
                'code' => $redisVal->code,
                'type_name' => getAdType($redisVal->type),
                'title' => $redisVal->title,
                'img_url_show' => '<image src=' . $redisVal->img_url . '/>',
                'img_url' => $redisVal->img_url,
                'url' => $redisVal->url,
                'by_sort' => $redisVal->by_sort,
                'describe' => $redisVal->describe,
                'is_lock_name' => getIsLock($redisVal->is_lock),//状态
                'is_lock' => $redisVal->is_lock,//状态
                'add_name' => getAdmName($redisVal->add_code),
                'add_time' => $redisVal->add_time,
                'up_name' => getAdmName($redisVal->up_code),
                'up_time' => $redisVal->up_time,
            ];
        }
        //
        //总记录
        $total = Advertisement::select(1)
            ->where('is_del', 0)
            ->where($where)
            ->count();
        $data = [];
        $data['code'] = 0;
        $data['msg'] = '查询成功';
        $data['data'] = $dbData;
        $data['count'] = $total;
        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $data = [];
        $data['id'] = '';
        $data['type'] = '';
        $data['img_url'] = '';
        return view('.sys.pages.advertisement.adEdit', ['db' => $data]);
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

        $db = new Advertisement();
        $db['code'] = getNewId();
        $db['type'] = $inp['type'];
        $db['title'] = $inp['title'];
        $db['img_url'] = $inp['img_url'];
        $db['url'] = $inp['url'];
        $db['by_sort'] = $inp['by_sort'];
        $db['describe'] = $inp['describe'];
        $db['add_code'] = _admCode();
        $db['add_time'] = getTime(1);
        if ($db->save()) {
            //生成redis缓存
            $redisArr['pub_advertisement:' . $db->id] = json_encode($db);
            Redis::mset($redisArr);//提交缓存
            opLog('pub_advertisement', [['type' => '添加', 'this_id' => $db->id, 'content' => json_encode($inp)]]);//记录日志
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
        $db = Advertisement::find($id);
        $data['id'] = $id;
        $data['type'] = $db['type'];
        $data['img_url'] = $db['img_url'];
        return view('.sys.pages.advertisement.adEdit', ['db' => $data]);
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

        $db = Advertisement::find($id);
        $db['type'] = $inp['type'];
        $db['title'] = $inp['title'];
        $db['img_url'] = $inp['img_url'];
        $db['url'] = $inp['url'];
        $db['by_sort'] = $inp['by_sort'];
        $db['describe'] = $inp['describe'];
        $db['up_code'] = _admCode();
        $db['up_time'] = getTime(1);
        if ($db->save()) {
            //生成redis缓存
            $redisArr['pub_advertisement:' . $db->id] = json_encode($db);
            Redis::mset($redisArr);//提交缓存
            opLog('pub_advertisement', [['type' => '修改', 'this_id' => $id, 'content' => json_encode($inp)]]);//记录日志
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
    }

    public function del(Request $request)
    {
        //
        $inp = $request->all();
        setDel('pub_advertisement', $inp['id']);
        return getSuccess(1);
    }

    public function start(Request $request)
    {
        $inp = $request->all();
        setNoLock('pub_advertisement', $inp['id']);
        return getSuccess(1);
    }

    public function stop(Request $request)
    {
        $inp = $request->all();
        setLock('pub_advertisement', $inp['id']);
        return getSuccess(1);
    }

    public function tableEdit(Request $request)
    {
        $inp = $request->all();
        $res = setTableEdit('pub_advertisement', $inp['id'], $inp['field'], $inp['value'], 0);
        if ($res > 0) {
            return getSuccess(1);
        } else {
            return getSuccess($res);
        }
    }
}
