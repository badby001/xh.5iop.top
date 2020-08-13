<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="sogou_site_verification" content="gI1bINaJcL"/>
    <meta name="360-site-verification" content="37ae9186443cc6e270d8a52943cd3c5a"/>
    <meta name="baidu_union_verify" content="99203948fbfbb64534dbe0f030cbe817">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="keywords" content="{!! site()['keywords'] !!}">
    <meta name="description" content="{!! site()['description'] !!}">
    <title>{!! site()['homeTitle'] !!}</title>
    <link href="{!! site()['ico'] !!}" rel="icon" type="image/x-icon">
    <link href="homePages/css/base.css" rel="stylesheet" type="text/css"/>
    <link href="homePages/css/home.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<section class="aui-flexView">
    <header class="aui-navBar aui-navBar-fixed">
        <div class="aui-center">
            <div class="aui-search-box">
                <i class="icon icon-search"></i>
                <input type="text" placeholder="搜索商品或店铺" value="" name="">
            </div>
        </div>
        <a href="javascript:;" class="aui-navBar-item">
            <i class="icon icon-news"></i>
        </a>
    </header>
    <section class="aui-scrollView">
        <div class="m-slider" data-ydui-slider>
            <div class="slider-wrapper">
                @foreach($advertisement as $k=>$v)
                    <div class="slider-item">
                        <a href="{!! $v['url']=='http://'?'javascript:;':$v['url'] !!}">
                            <img src="{!! $v['img_url'] !!}" alt="{!! $v['title'] !!}">
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="slider-pagination"></div>
        </div>
        <div class="aui-palace aui-palace-one">
            @foreach($navigation as $k=>$v)
                @if($k<=9)
                    <a href="javascript:;" class="aui-palace-grid">
                        <div class="aui-palace-grid-icon">
                            <img src="{!! $v['img_url'] !!}" alt="">
                        </div>
                        <div class="aui-palace-grid-text">
                            <h2>{!! $v['title'] !!}</h2>
                        </div>
                    </a>
                @endif
            @endforeach
        </div>
        <div class="divHeight"></div>
        <div class="aui-flex aui-flex-ad">
            <div class="aui-flex-box">
                <img src="homePages/img/ad-001.png" alt="">
            </div>
            <div class="aui-flex-box">
                <img src="homePages/img/ad-002.png" alt="">
            </div>
        </div>
        <div class="divHeight"></div>
        <div class="aui-day-hot">
            <div class="aui-flex">
                <div class="aui-flex-box">
                    <h2>今日必抢</h2>
                </div>
            </div>
            <div class="aui-hot-box">
                <div class="aui-hot-list r">
                    <div class="aui-hot-list-text">
                        <h1>限时抢购</h1>
                        <p>大牌限时购</p>
                        <button>立即抢购</button>
                    </div>
                    <div class="aui-hot-list-img">
                        <img src="homePages/img/pd-001.png" alt="">
                        <h2>￥199 <em>￥399</em></h2>
                    </div>
                </div>
                <div class="aui-hot-list ">
                    <div class="aui-hot-list-text">
                        <h1>0元拼团</h1>
                        <p>好物等你拼</p>
                        <button style="background:#e743fe">立即拼团</button>
                    </div>
                    <div class="aui-hot-list-img">
                        <img src="homePages/img/pd-002.png" alt="">
                        <h2>￥199 <em>￥399</em></h2>
                    </div>
                </div>
                <div class="aui-hot-list r">
                    <div class="aui-hot-list-text">
                        <h1>限时抢购</h1>
                        <p>好物等你拼</p>
                        <button style="background:#ff9136">新品上市</button>
                    </div>
                    <div class="aui-hot-list-img">
                        <img src="homePages/img/pd-003.png" alt="">
                        <h2>￥199 <em>￥399</em></h2>
                    </div>
                </div>
                <div class="aui-hot-list">
                    <div class="aui-hot-list-text">
                        <h1>限时抢购</h1>
                        <p>好物等你拼</p>
                        <button style="background:#23f0c7">年轻畅享</button>
                    </div>
                    <div class="aui-hot-list-img">
                        <img src="homePages/img/pd-004.png" alt="">
                        <h2>￥199 <em>￥399</em></h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="divHeight"></div>
        <div class="aui-flex aui-flex-title">
            <div class="aui-flex-box">
                <h2>好物精选</h2>
            </div>
        </div>
        <div class="aui-wares-list">
            <div class="aui-hot-list-img">
                <img src="homePages/img/pd-005.jpg" alt="">
                <h1>美菱(MeiLing)电火锅家用 多功能电炒锅 电热炒煮蒸煎涮炖焖一体锅 麦饭石色不粘锅 学生宿舍小电锅MTA-5-30</h1>
                <!-- AUI素材网 网址：http://www.a-ui.cn/ -->
                <h2>￥199 <em>￥399</em></h2>
            </div>
            <div class="aui-hot-list-img">
                <img src="homePages/img/pd-006.jpg" alt="">
                <h1>美菱(MeiLing)电火锅家用 多功能电炒锅 电热炒煮蒸煎涮炖焖一体锅 麦饭石色不粘锅 学生宿舍小电锅MTA-5-30</h1>
                <!-- AUI素材网 网址：http://www.a-ui.cn/ -->
                <h2>￥199 <em>￥399</em></h2>
            </div>
            <div class="aui-hot-list-img">
                <img src="homePages/img/pd-007.jpg" alt="">
                <h1>美菱(MeiLing)电火锅家用 多功能电炒锅 电热炒煮蒸煎涮炖焖一体锅 麦饭石色不粘锅 学生宿舍小电锅MTA-5-30</h1>
                <!-- AUI素材网 网址：http://www.a-ui.cn/ -->
                <h2>￥199 <em>￥399</em></h2>
            </div>
            <div class="aui-hot-list-img">
                <img src="homePages/img/pd-008.jpg" alt="">
                <h1>美菱(MeiLing)电火锅家用 多功能电炒锅 电热炒煮蒸煎涮炖焖一体锅 麦饭石色不粘锅 学生宿舍小电锅MTA-5-30</h1>
                <!-- AUI素材网 网址：http://www.a-ui.cn/ -->
                <h2>￥199 <em>￥399</em></h2>
            </div>
        </div>
        <div class="divHeight"></div>
        <div class="aui-flex aui-flex-title" style="background:none">
            <div class="aui-flex-box">
                <h2>为您推荐</h2>
            </div>
        </div>
        <div class="aui-recommend">
            <div class="aui-hot-list-img">
                <img src="homePages/img/pd-009.jpg" alt="">
                <h1>美菱(MeiLing)电火锅家用 多功能电炒锅 电热炒煮蒸煎涮炖焖一体锅 麦饭石色不粘锅 学生宿舍小电锅MTA-5-30</h1>
                <!-- AUI素材网 网址：http://www.a-ui.cn/ -->
                <h2>￥199 <em>￥399</em> <i class="icon icon-car"></i></h2>
            </div>
            <div class="aui-hot-list-img">
                <img src="homePages/img/pd-010.jpg" alt="">
                <h1>美菱(MeiLing)电火锅家用 多功能电炒锅 电热炒煮蒸煎涮炖焖一体锅 麦饭石色不粘锅 学生宿舍小电锅MTA-5-30</h1>
                <!-- AUI素材网 网址：http://www.a-ui.cn/ -->
                <h2>￥199 <em>￥399</em> <i class="icon icon-car"></i></h2>
            </div>
            <div class="aui-hot-list-img">
                <img src="homePages/img/pd-011.jpg" alt="">
                <h1>美菱(MeiLing)电火锅家用 多功能电炒锅 电热炒煮蒸煎涮炖焖一体锅 麦饭石色不粘锅 学生宿舍小电锅MTA-5-30</h1>
                <!-- AUI素材网 网址：http://www.a-ui.cn/ -->
                <h2>￥199 <em>￥399</em> <i class="icon icon-car"></i></h2>
            </div>
            <div class="aui-hot-list-img">
                <img src="homePages/img/pd-008.jpg" alt="">
                <h1>美菱(MeiLing)电火锅家用 多功能电炒锅 电热炒煮蒸煎涮炖焖一体锅 麦饭石色不粘锅 学生宿舍小电锅MTA-5-30</h1>
                <!-- AUI素材网 网址：http://www.a-ui.cn/ -->
                <h2>￥199 <em>￥399</em> <i class="icon icon-car"></i></h2><!-- AUI素材网 网址：http://www.a-ui.cn/ -->
            </div>
            <div class="aui-hot-list-img">
                <img src="homePages/img/pd-005.jpg" alt="">
                <h1>美菱(MeiLing)电火锅家用 多功能电炒锅 电热炒煮蒸煎涮炖焖一体锅 麦饭石色不粘锅 学生宿舍小电锅MTA-5-30</h1>
                <!-- AUI素材网 网址：http://www.a-ui.cn/ -->
                <h2>￥199 <em>￥399</em> <i class="icon icon-car"></i></h2>
            </div>
            <div class="aui-hot-list-img">
                <img src="homePages/img/pd-006.jpg" alt="">
                <h1>美菱(MeiLing)电火锅家用 多功能电炒锅 电热炒煮蒸煎涮炖焖一体锅 麦饭石色不粘锅 学生宿舍小电锅MTA-5-30</h1>
                <!-- AUI素材网 网址：http://www.a-ui.cn/ -->
                <h2>￥199 <em>￥399</em> <i class="icon icon-car"></i></h2>
            </div>
            <div class="aui-hot-list-img">
                <img src="homePages/img/pd-007.jpg" alt="">
                <h1>美菱(MeiLing)电火锅家用 多功能电炒锅 电热炒煮蒸煎涮炖焖一体锅 麦饭石色不粘锅 学生宿舍小电锅MTA-5-30</h1>
                <!-- AUI素材网 网址：http://www.a-ui.cn/ -->
                <h2>￥199 <em>￥399</em> <i class="icon icon-car"></i></h2>
            </div>
            <div class="aui-hot-list-img">
                <img src="homePages/img/pd-008.jpg" alt="">
                <h1>美菱(MeiLing)电火锅家用 多功能电炒锅 电热炒煮蒸煎涮炖焖一体锅 麦饭石色不粘锅 学生宿舍小电锅MTA-5-30</h1>
                <!-- AUI素材网 网址：http://www.a-ui.cn/ -->
                <h2>￥199 <em>￥399</em> <i class="icon icon-car"></i></h2><!-- AUI素材网 网址：http://www.a-ui.cn/ -->
            </div>
            <div class="aui-hot-list-img">
                <img src="homePages/img/pd-005.jpg" alt="">
                <h1>美菱(MeiLing)电火锅家用 多功能电炒锅 电热炒煮蒸煎涮炖焖一体锅 麦饭石色不粘锅 学生宿舍小电锅MTA-5-30</h1>
                <!-- AUI素材网 网址：http://www.a-ui.cn/ -->
                <h2>￥199 <em>￥399</em> <i class="icon icon-car"></i></h2>
            </div>
            <div class="aui-hot-list-img">
                <img src="homePages/img/pd-006.jpg" alt="">
                <h1>美菱(MeiLing)电火锅家用 多功能电炒锅 电热炒煮蒸煎涮炖焖一体锅 麦饭石色不粘锅 学生宿舍小电锅MTA-5-30</h1>
                <!-- AUI素材网 网址：http://www.a-ui.cn/ -->
                <h2>￥199 <em>￥399</em> <i class="icon icon-car"></i></h2>
            </div>
            <div class="aui-hot-list-img">
                <img src="homePages/img/pd-007.jpg" alt="">
                <h1>美菱(MeiLing)电火锅家用 多功能电炒锅 电热炒煮蒸煎涮炖焖一体锅 麦饭石色不粘锅 学生宿舍小电锅MTA-5-30</h1>
                <!-- AUI素材网 网址：http://www.a-ui.cn/ -->
                <h2>￥199 <em>￥399</em> <i class="icon icon-car"></i></h2>
            </div>
            <div class="aui-hot-list-img">
                <img src="homePages/img/pd-008.jpg" alt="">
                <h1>美菱(MeiLing)电火锅家用 多功能电炒锅 电热炒煮蒸煎涮炖焖一体锅 麦饭石色不粘锅 学生宿舍小电锅MTA-5-30</h1>
                <!-- AUI素材网 网址：http://www.a-ui.cn/ -->
                <h2>￥199 <em>￥399</em> <i class="icon icon-car"></i></h2><!-- AUI素材网 网址：http://www.a-ui.cn/ -->
            </div>
            <div class="aui-hot-list-img">
                <img src="homePages/img/pd-005.jpg" alt="">
                <h1>美菱(MeiLing)电火锅家用 多功能电炒锅 电热炒煮蒸煎涮炖焖一体锅 麦饭石色不粘锅 学生宿舍小电锅MTA-5-30</h1>
                <!-- AUI素材网 网址：http://www.a-ui.cn/ -->
                <h2>￥199 <em>￥399</em> <i class="icon icon-car"></i></h2>
            </div>
            <div class="aui-hot-list-img">
                <img src="homePages/img/pd-006.jpg" alt="">
                <h1>美菱(MeiLing)电火锅家用 多功能电炒锅 电热炒煮蒸煎涮炖焖一体锅 麦饭石色不粘锅 学生宿舍小电锅MTA-5-30</h1>
                <!-- AUI素材网 网址：http://www.a-ui.cn/ -->
                <h2>￥199 <em>￥399</em> <i class="icon icon-car"></i></h2>
            </div>
            <div class="aui-hot-list-img">
                <img src="homePages/img/pd-007.jpg" alt="">
                <h1>美菱(MeiLing)电火锅家用 多功能电炒锅 电热炒煮蒸煎涮炖焖一体锅 麦饭石色不粘锅 学生宿舍小电锅MTA-5-30</h1>
                <!-- AUI素材网 网址：http://www.a-ui.cn/ -->
                <h2>￥199 <em>￥399</em> <i class="icon icon-car"></i></h2><!-- AUI素材网 网址：http://www.a-ui.cn/ -->
            </div>
            <div class="aui-hot-list-img">
                <img src="homePages/img/pd-008.jpg" alt="">
                <h1>美菱(MeiLing)电火锅家用 多功能电炒锅 电热炒煮蒸煎涮炖焖一体锅 麦饭石色不粘锅 学生宿舍小电锅MTA-5-30</h1>
                <h2>￥199 <em>￥399</em> <i class="icon icon-car"></i></h2><!-- AUI素材网 网址：http://www.a-ui.cn/ -->
            </div>
            <div style="color:#a7a7a7;text-align:center; font-size:0.7rem">没有更多了</div>
        </div>

        <div style="height:66px;"></div>
    </section>
    <footer class="aui-footer aui-footer-fixed">
        <a href="javascript:;" class="aui-tabBar-item aui-tabBar-item-active">
                    <span class="aui-tabBar-item-icon">
                        <i class="icon icon-loan"></i>
                    </span>
            <span class="aui-tabBar-item-text">首页</span>
        </a>
        <a href="javascript:;" class="aui-tabBar-item ">
                    <span class="aui-tabBar-item-icon">
                        <i class="icon icon-credit"></i>
                    </span>
            <span class="aui-tabBar-item-text">分类</span>
        </a>
        <a href="javascript:;" class="aui-tabBar-item ">
                    <span class="aui-tabBar-item-icon">
                        <i class="icon icon-ions"></i>
                    </span>
            <span class="aui-tabBar-item-text">购物车</span>
        </a>
        <a href="javascript:;" class="aui-tabBar-item ">
                    <span class="aui-tabBar-item-icon">
                        <i class="icon icon-info"></i>
                    </span>
            <span class="aui-tabBar-item-text">我的</span>
        </a>
    </footer>
</section>
<script type="text/javascript" src="homePages/js/jquery.min.js"></script>
<script type="text/javascript" src="homePages/js/slider.js"></script>
</body>
</html>
