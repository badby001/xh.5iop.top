<!DOCTYPE html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>{!! site()['siteWebName'] !!}</title>
    <meta name="viewport"
          content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi"/>
    <meta name="keywords" content="{!! site()['keywords'] !!}">
    <meta name="description" content="{!! site()['description'] !!}">
    <link rel="stylesheet" href="/css/oksub.css">
    <script type="text/javascript" src="../../../js/jquery-1.9.0.min.js"></script>
    @include('.sys.public.js')
</head>

<body>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                    @if(hasPower(215))
                        <form class="layui-form layui-col-space5" method="post">
                            <div class="layui-input-inline layui-show-xs-block">
                                <input class="layui-input" placeholder="一级菜单路由名称" name="route_title"
                                       lay-verify="required|route_title"></div>
                            <div class="layui-input-inline layui-show-xs-block">
                                <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon"></i>增加路由
                                </button>
                            </div>
                            {{csrf_field()}}
                        </form>
                    @endif
                    <hr>
                </div>
                <div class="layui-card-body ">
                    <table class="layui-table layui-form">
                        <thead>
                        <tr>
                            <th width="460">路由名称</th>
                            <th width="50">类型</th>
                            <th width="50">排序</th>
                            <th width="320">操作</th>
                        </thead>
                        <tbody class="x-cate">
                        @foreach($data as $v)
                            <tr cate-id='{{$v->id}}' fid='{{$v->father_id}}'>
                                <td>
                                    @if(isset($v->children))
                                        <i class="layui-icon x-show" status='true'>&#xe623;</i>
                                    @else
                                        <i class="layui-icon">&#xe63f;</i>
                                    @endif
                                    {!! $v->title !!}
                                </td>
                                <td>{!! getRouteType($v->is_type) !!}</td>
                                <td>{!! $v->by_sort !!}</td>
                                <td class="td-manage">
                                    @if(hasPower(216))
                                        <button class="layui-btn layui-btn layui-btn-xs"
                                                onclick="xadmin.open('修改','{{url('/sys/pages/system/route/'.$v->id.'/edit')}}')">
                                            <i class="layui-icon">&#xe642;</i>修改
                                        </button>
                                    @endif
                                    @if(hasPower(215))
                                        <button class="layui-btn layui-btn-warm layui-btn-xs"
                                                onclick="xadmin.open('添加子项','{{url('/sys/pages/system/route/'.$v->id)}}')">
                                            <i class="layui-icon">&#xe642;</i>子项
                                        </button>
                                    @endif
                                    @if(isset($v->children)==false && hasPower(218))
                                        <button class="layui-btn-danger layui-btn layui-btn-xs"
                                                onclick="del(this,{!! $v->id !!})" href="javascript:;"><i
                                                class="layui-icon">&#xe640;</i>删除
                                        </button>
                                    @endif
                                    @if(hasPower(215))
                                        <button class="layui-btn layui-btn-primary layui-btn-xs"
                                                onclick="btn({!! $v->id !!})" href="javascript:;">按钮套餐
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @if(isset($v->children))
                                @foreach($v->children as $li)
                                    {!! children($li,1) !!}
                                @endforeach
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    layui.use(['form', "okLayer", "okLayer", "okUtils"], function () {
        let form = layui.form;
        let okLayer = layui.okLayer;
        let okUtils = layui.okUtils;
        //自定义验证规则
        form.verify({
            route_title: function (value) {
                if (value.length > 6) {
                    return '路由名称请控制在6个字符以内';
                }
            },
        });

        form.on("submit(sreach)", function (data) {
            okUtils.ajax("{{url('sys/pages/system/route')}}", "post", data.field, true).done(function (response) {
                okLayer.greenTickMsg(response.msg, function () {
                    location.reload();
                });
            }).fail(function (error) {
                console.log(error)
            });
            return false;
        });
    });

    /*用户-删除*/
    function del(obj, id) {
        let okLayer = layui.okLayer;
        let okUtils = layui.okUtils;
        okLayer.confirm("确定要删除吗？", function (index) {
            okUtils.ajax("{{url('sys/pages/system/route/')}}/" + id, "DELETE", {
                id: id,
                _token: '{{csrf_token()}}'
            }, true).done(function (response) {
                layer.msg(response.msg, {icon: 1, time: 1000});
                $(obj).parents("tr").remove();
            }).fail(function (error) {
                console.log(error)
            });
        })
    }

    /*按钮套餐*/
    function btn(id) {
        let okLayer = layui.okLayer;
        let okUtils = layui.okUtils;
        okLayer.confirm("确定要生成按钮套餐码？<br>(数据读取+添加+修改+启用/停用+删除)", function (index) {
            okUtils.ajax("{{url('sys/pages/system/buttonPackage/')}}/" + id, "get", {
                id: id,
                _token: '{{csrf_token()}}'
            }, true).done(function (response) {
                layer.msg(response.msg, {icon: 1, time: 1000});
            }).fail(function (error) {
                console.log(error)
            });
        })
    }

    // 分类展开收起的分类的逻辑
    //
    $(function () {
        $("tbody.x-cate tr[fid!='0']").hide();
        // 栏目多级显示效果
        $('.x-show').click(function () {
            if ($(this).attr('status') == 'true') {
                $(this).html('&#xe625;');
                $(this).attr('status', 'false');
                cateId = $(this).parents('tr').attr('cate-id');
                $("tbody tr[fid=" + cateId + "]").show();
            } else {
                cateIds = [];
                $(this).html('&#xe623;');
                $(this).attr('status', 'true');
                cateId = $(this).parents('tr').attr('cate-id');
                getCateId(cateId);
                for (var i in cateIds) {
                    $("tbody tr[cate-id=" + cateIds[i] + "]").hide().find('.x-show').html('&#xe623;').attr('status', 'true');
                }
            }
        })
    })
    var cateIds = [];

    function getCateId(cateId) {
        $("tbody tr[fid=" + cateId + "]").each(function (index, el) {
            id = $(el).attr('cate-id');
            cateIds.push(id);
            getCateId(id);
        });
    }
    ;!function (win) {
        "use strict";
        var doc = document
            , Xadmin = function () {
            this.v = '2.2'; //版本号
        }
        /**
         * [open 打开弹出层]
         * @param  {[type]}  title [弹出层标题]
         * @param  {[type]}  url   [弹出层地址]
         * @param  {[type]}  w     [宽]
         * @param  {[type]}  h     [高]
         * @param  {Boolean} full  [全屏]
         * @return {[type]}        [description]
         */
        Xadmin.prototype.open = function (title, url, w, h, full) {
            if (title == null || title == '') {
                var title = false;
            }
            if (url == null || url == '') {
                var url = "404.html";
            }
            if (w == null || w == '') {
                var w = ($(window).width() * 0.9);
            }
            if (h == null || h == '') {
                var h = ($(window).height() - 50);
            }
            var index = layer.open({
                type: 2,
                area: [w + 'px', h + 'px'],
                fix: false, //不固定
                maxmin: true,
                shadeClose: true,
                shade: 0.4,
                title: title,
                content: url
            });
            if (full) {
                layer.full(index);
            }
        }
        win.xadmin = new Xadmin();
    }(window);
</script>

</body>
</html>
<?php
function children($li, $i)
{
    echo '<tr cate-id=' . $li->id . ' fid=' . $li->father_id . '>';
    echo '<td>';
    for ($k = 1; $k <= $i; $k++) {
        echo '&nbsp;&nbsp;&nbsp;&nbsp;';
    }
    if (isset($li->children)) {
        echo ' <i class="layui-icon x-show" status="true">&#xe623;</i>' . $li->title;
    } else {
        echo '|-- ' . $li->title . " (routeId->  " . $li->id . ")";
    }
    echo '</td>';
    echo '<td>' . getRouteType($li->is_type) . '</td>';
    echo '<td>';
    echo '|';
    for ($k = 1; $k <= $i; $k++) {
        echo '--';
    }
    echo $li->by_sort;
    echo ' </td>';
    echo '<td class="td-manage">';
    if (hasPower(216)) {
        echo '<button class="layui-btn layui-btn layui-btn-xs" onclick="xadmin.open(\'修改\',\'routeSon/' . $li->id . '/edit\')"><i class="layui-icon">&#xe642;</i>修改</button>';
    }
    if (hasPower(215)) {
        echo $li->is_type == 0 ? '<button class="layui-btn layui-btn-warm layui-btn-xs" onclick="xadmin.open(\'子项\',\'/sys/pages/system/route/' . $li->id . '/\')"><i class="layui-icon">&#xe642;</i>子项</button>' : '';
    }
    if (isset($li->children) == false && hasPower(218)) {
        echo '<button class="layui-btn-danger layui-btn layui-btn-xs" onclick="del(this,' . $li->id . ')" href="javascript:;"><i class="layui-icon">&#xe640;</i>删除</button>';
    }
    if ($li->is_type == 0 && hasPower(215)) {
        echo '<button class="layui-btn layui-btn-primary layui-btn-xs" onclick="btn(' . $li->id . ')" href="javascript:;">按钮套餐</button>';
    }
    echo '</td>';
    echo '</tr>';
    if (isset($li->children)) {
        $j = $i + 1;
        foreach ($li->children as $li2) {
            children($li2, $j);
        }
    }
}
?>
