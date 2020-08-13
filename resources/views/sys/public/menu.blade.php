<div class="layui-side">
    <div class="layui-side-scroll">
        <ul class="layui-nav layui-nav-tree" lay-filter="admin-side-nav" lay-shrink="_all">
            @foreach(getMenu() as $s=>$v)
                <li class="layui-nav-item">
                    <a {{strlen($v->href) === 1?'':'lay-href='.$v->href}}><i
                            class="{{$v->font_family}} {{$v->icon}}"></i>&emsp;<cite>{!! $v->title !!}</cite></a>
                    @isset($v->children)
                        <dl class="layui-nav-child">
                            @foreach($v->children as $ss=>$vv)
                                <dd>
                                    <a {{strlen($vv->href) === 1?'':'lay-href='.$vv->href}}><i
                                            class="{{$vv->font_family}} {{$vv->icon}}"></i>&emsp;<cite>{!! $vv->title !!}</cite></a>
                                    @isset($vv->children)
                                        <dl class="layui-nav-child">
                                            @foreach($vv->children as $sss=>$vvv)
                                                <dd>
                                                    <a {{strlen($vvv->href) === 1?'':'lay-href='.$vvv->href}}><i
                                                            class="{{$vvv->font_family}} {{$vvv->icon}}"></i>&emsp;<cite>{!! $vvv->title !!}</cite></a>
                                                    @isset($vvv->children)
                                                        <dl class="layui-nav-child">
                                                            @foreach($vvv->children as $ssss=>$vvvv)
                                                                <dd>
                                                                    <a {{strlen($vvvv->href) === 1?'':'lay-href='.$vvvv->href}}><i
                                                                            class="{{$vvvv->font_family}} {{$vvvv->icon}}"></i>&emsp;<cite>{!! $vvvv->title !!}</cite></a>
                                                                    @isset($vvvv->children)
                                                                        <dl class="layui-nav-child">
                                                                            @foreach($vvvv->children as $sssss=>$vvvvv)
                                                                                <dd>
                                                                                    <a {{strlen($vvvvv->href) === 1?'':'lay-href='.$vvvvv->href}}><i
                                                                                            class="{{$vvvvv->font_family}} {{$vvvvv->icon}}"></i>&emsp;<cite>{!! $vvvvv->title !!}</cite></a>
                                                                                </dd>
                                                                            @endforeach
                                                                        </dl>
                                                                    @endisset
                                                                </dd>
                                                            @endforeach
                                                        </dl>
                                                    @endisset
                                                </dd>
                                            @endforeach
                                        </dl>
                                    @endisset
                                </dd>
                            @endforeach
                        </dl>
                    @endisset
                </li>
            @endforeach
        </ul>
    </div>
</div>
