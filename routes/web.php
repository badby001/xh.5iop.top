<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//
//**********************************************************
//
//前台路由
//
//**********************************************************
//
//Route::get('/', 'HomePages\IndexController@index');//首页
use Illuminate\Support\Facades\Log;

Route::redirect('/', '/sys', 302);
/**
 *
 * 公共方法
 *
 */
Route::get('ajaxReadKey/{type}/{Id}', 'AjaxReadKeyController@show');
//
//**********************************************************
//
//后台路由
//
//**********************************************************
//
//免登录路由
Route::group(['prefix' => 'sys/', 'namespace' => 'Sys'], function () {
    Route::get('login', 'IndexController@index');//登录页
    Route::post('login', 'IndexController@login');//登录接口
    Route::get('register', 'IndexController@register');//注册页面
    Route::post('register', 'IndexController@registerReg');//注册接口
    Route::get('forgetPwd', 'IndexController@forgetPwd');//找回秒页面
    Route::post('forgetPwd', 'IndexController@forgetPwds');//找回密码接口
    Route::post('postXqCaptcha', 'IndexController@postXqCaptcha');//发送验证码到小强系统
});
//
//登录验证路由
Route::group(['prefix' => 'sys/', 'namespace' => 'Sys', 'middleware' => ['isLogin']], function () {
    Route::get('demo', 'IndexController@demo');//demo测试
    Route::get('', 'IndexController@index');//后台主页
    Route::get('logout', 'IndexController@logout');//退出系统
    Route::post('upload', 'IndexController@upload');//文件上传
    //
    Route::get('admUserLogin', 'IndexController@admUserLogin');//登录日志
    //
    Route::group(['prefix' => 'pages/', 'namespace' => 'Pages'], function () {
        //框架公共部分
        Route::get('admInfo', 'IndexController@admInfo');//基本资料
        Route::post('admInfo', 'IndexController@admInfoUp');//基本资料接口
        Route::post('admAttestation', 'IndexController@admAttestationUp');//同行认证
        Route::get('admPwd', 'IndexController@admPwd');//安全设置
        Route::post('admPwd', 'IndexController@admPwdUp');//安全设置接口
        Route::get('menu', 'IndexController@menu');//左navigationLineProductRead侧菜单
        Route::get('console', 'IndexController@console');//控制台
        Route::get('logInfo/{tableName}/{tableId}/{tableStr}', 'IndexController@logInfo');//公共日志查看
        Route::get('logInfoRead', 'IndexController@logInfoRead');//获取页面数据
        Route::get('message', 'IndexController@message');//获取消息通知
        Route::get('note', 'IndexController@note');//获取便签
        Route::get('theme', 'IndexController@theme');//获取主题
        Route::get('screen', 'IndexController@screen');//锁定桌面
        //
        //订单管理
        Route::group(['prefix' => 'order/', 'namespace' => 'Order'], function () {
            //订单列表
            Route::resource('ordList', 'OrderController');
            Route::get('orderRead', 'OrderController@read');//获取页面数据
            Route::post('orderTableEdit', 'OrderController@tableEdit');//表格编辑
            Route::post('orderCancel', 'OrderController@cancel');//取消订单
            Route::get('orderInfo', 'OrderController@read');//订单详情
            Route::get('orderPay/{id}/{amount}', 'OrderController@pay');//获取页面数据
            Route::get('orderUserInfo/{id}/edit', 'OrderController@orderUserInfoEdit');//获取名单详情
            Route::post('orderUserInfo', 'OrderController@orderUserInfoEditUp');//获取名单详情
            //订单列表(商品)
            Route::get('ord1List', 'OrderController@ord1List');//进入商品列表
            Route::get('order1Read', 'OrderController@order1Read');//获取商品页面数据
            //
            //合同汇总
            Route::resource('allContract', 'ContractController');
            Route::get('eContractAllRead', 'ContractController@allRead');//合同汇总页面
            Route::get('eContractRead', 'ContractController@read');//获取页面数据
            Route::post('eContractSign', 'ContractController@sign');//发送签署
            Route::post('eContractSignSms', 'ContractController@signSms');//发送签署短信
            Route::post('eContractSignCancel', 'ContractController@signCancel');//作废合同
            Route::post('eContractSignDelete', 'ContractController@signDelete');//删除合同
            //
            //发票申请
            Route::resource('invoice', 'InvoiceController');
            Route::get('invoiceRead', 'InvoiceController@read');//获取页面数据
        });
        //会员账号相关
        Route::group(['prefix' => 'member/', 'namespace' => 'Member'], function () {
            //我的会员
            Route::resource('myUser', 'MyUserController');
//            Route::get('orderRead', 'OrderController@read');//获取页面数据
//            Route::post('admUserStart', 'AdmUserController@start');//启用
//            Route::post('admUserStop', 'AdmUserController@stop');//禁止
//            Route::post('admUserDel', 'AdmUserController@del');//删除
            //
            //全部会员
            Route::resource('allUser', 'AllUserController');
//            Route::get('admUserRoleRead', 'AdmUserRoleController@read');//获取页面数据
//            Route::post('admUserRoleStart', 'AdmUserRoleController@start');//启用
//            Route::post('admUserRoleStop', 'AdmUserRoleController@stop');//禁止
//            Route::post('admUserRoleDel', 'AdmUserRoleController@del');//删除
            //我的推广员
            Route::resource('myPromoter', 'MyPromoterController');
//            Route::get('admUserRoleRead', 'AdmUserRoleController@read');//获取页面数据
//            Route::post('admUserRoleStart', 'AdmUserRoleController@start');//启用
//            Route::post('admUserRoleStop', 'AdmUserRoleController@stop');//禁止
//            Route::post('admUserRoleDel', 'AdmUserRoleController@del');//删除
        });
        //推广中心
        Route::group(['prefix' => 'extend/', 'namespace' => 'Extend'], function () {
            //推广中心
            Route::resource('extensionCentre', 'ExtensionController');
//            Route::get('orderRead', 'OrderController@read');//获取页面数据
//            Route::post('admUserStart', 'AdmUserController@start');//启用
//            Route::post('admUserStop', 'AdmUserController@stop');//禁止
//            Route::post('admUserDel', 'AdmUserController@del');//删除
        });
        //店铺管理
        Route::group(['prefix' => 'store/', 'namespace' => 'Store'], function () {
            //店铺管理
            Route::resource('storeManage', 'StoreController');
//            Route::get('orderRead', 'OrderController@read');//获取页面数据
//            Route::post('admUserStart', 'AdmUserController@start');//启用
//            Route::post('admUserStop', 'AdmUserController@stop');//禁止
//            Route::post('admUserDel', 'AdmUserController@del');//删除
        });
        //常用信息
        Route::group(['prefix' => 'oftenInformation/', 'namespace' => 'OftenInformation'], function () {
            //联系人
            Route::resource('contacts', 'ContactsController');
//            Route::get('orderRead', 'OrderController@read');//获取页面数据
//            Route::post('admUserStart', 'AdmUserController@start');//启用
//            Route::post('admUserStop', 'AdmUserController@stop');//禁止
//            Route::post('admUserDel', 'AdmUserController@del');//删除
            //出游人
            Route::resource('visitor', 'VisitorController');
//            Route::get('orderRead', 'OrderController@read');//获取页面数据
//            Route::post('admUserStart', 'AdmUserController@start');//启用
//            Route::post('admUserStop', 'AdmUserController@stop');//禁止
//            Route::post('admUserDel', 'AdmUserController@del');//删除
            //地址
            Route::resource('address', 'AddressController');
            Route::get('addressRead', 'AddressController@read');//获取页面数据
            Route::post('addressTableEdit', 'AddressController@tableEdit');//表格编辑
            Route::post('addressStart', 'AddressController@start');//启用
            Route::post('addressStop', 'AddressController@stop');//禁止
            Route::post('addressDel', 'AddressController@del');//删除
            //报销凭证
            Route::resource('invoice', 'InvoiceController');
//            Route::get('orderRead', 'OrderController@read');//获取页面数据
//            Route::post('admUserStart', 'AdmUserController@start');//启用
//            Route::post('admUserStop', 'AdmUserController@stop');//禁止
//            Route::post('admUserDel', 'AdmUserController@del');//删除
        });
        //日常办公
        Route::group(['prefix' => 'dailyOffice/', 'namespace' => 'DailyOffice'], function () {
            //待办事项
            Route::resource('todoList', 'OfficeController');
//            Route::get('orderRead', 'OrderController@read');//获取页面数据
//            Route::post('admUserStart', 'AdmUserController@start');//启用
//            Route::post('admUserStop', 'AdmUserController@stop');//禁止
//            Route::post('admUserDel', 'AdmUserController@del');//删除
        });
        //财务管理
        Route::group(['prefix' => 'financialAffairs/', 'namespace' => 'FinancialAffairs'], function () {
            //收款管理
            Route::resource('rec', 'RecController');
//            Route::get('orderRead', 'OrderController@read');//获取页面数据
//            Route::post('admUserStart', 'AdmUserController@start');//启用
//            Route::post('admUserStop', 'AdmUserController@stop');//禁止
//            Route::post('admUserDel', 'AdmUserController@del');//删除
            //请款管理
            Route::resource('req', 'ReqController');
//            Route::get('orderRead', 'OrderController@read');//获取页面数据
//            Route::post('admUserStart', 'AdmUserController@start');//启用
//            Route::post('admUserStop', 'AdmUserController@stop');//禁止
//            Route::post('admUserDel', 'AdmUserController@del');//删除
            //收支明细
            Route::resource('detailed', 'DetailedController');
//            Route::get('orderRead', 'OrderController@read');//获取页面数据
//            Route::post('admUserStart', 'AdmUserController@start');//启用
//            Route::post('admUserStop', 'AdmUserController@stop');//禁止
//            Route::post('admUserDel', 'AdmUserController@del');//删除
        });
        //数据统计
        Route::group(['prefix' => 'statistics/', 'namespace' => 'Statistics'], function () {
            //数据统计
            Route::resource('data', 'StatisticsController');
//            Route::get('orderRead', 'OrderController@read');//获取页面数据
//            Route::post('admUserStart', 'AdmUserController@start');//启用
//            Route::post('admUserStop', 'AdmUserController@stop');//禁止
//            Route::post('admUserDel', 'AdmUserController@del');//删除
        });
        //管理员账号相关
        Route::group(['prefix' => 'admin/', 'namespace' => 'Admin'], function () {
            //账户列表
            Route::resource('admUser', 'AdmUserController');
            Route::get('admUserRead', 'AdmUserController@read');//获取页面数据
            Route::post('admUserStart', 'AdmUserController@start');//启用
            Route::post('admUserStop', 'AdmUserController@stop');//禁止
            Route::post('admUserDel', 'AdmUserController@del');//删除
            //
            //角色列表
            Route::resource('admUserRole', 'AdmUserRoleController');
            Route::get('admUserRoleRead', 'AdmUserRoleController@read');//获取页面数据
            Route::post('admUserRoleTableEdit', 'AdmUserRoleController@tableEdit');//表格编辑
            Route::post('admUserRoleStart', 'AdmUserRoleController@start');//启用
            Route::post('admUserRoleStop', 'AdmUserRoleController@stop');//禁止
            Route::post('admUserRoleDel', 'AdmUserRoleController@del');//删除
            //角色授权页
            Route::get('admUserRoleReadEdit', 'AdmUserRoleController@edit');//编辑角色权限页面
        });
        //框架/系统运维相关
        Route::group(['prefix' => 'system/', 'namespace' => 'System'], function () {
            //同行认证
            Route::resource('attestation', 'AttestationController');
            Route::get('attestationRead', 'AttestationController@read');//获取页面数据
            //
            //用户管理
            Route::resource('user', 'UserController');
            Route::get('userRead', 'UserController@read');//获取页面数据
            Route::post('userStart', 'UserController@start');//启用
            Route::post('userStop', 'UserController@stop');//禁止
            Route::post('userDel', 'UserController@del');//删除
            Route::get('userPower', 'UserController@userPower');//获取页面数据
            Route::post('userPowerStore', 'UserController@userPowerStore');//获取页面数据
            //
            //导航树配置
            Route::resource('navigation', 'NavigationController');
            Route::get('navigationRead', 'NavigationController@read');//获取数据
            Route::post('navigationTarget', 'NavigationController@target');//根据前端发送的树进行拖动
            Route::post('navigationSort', 'NavigationController@sort');//根据前端发送的树进行排序
            Route::post('navigationNodeEdit/{id}', 'NavigationController@nodeEdit');//设置

            //导航列表配置
            Route::resource('navigationLine', 'NavigationLineController');
            Route::get('navigationLineBind/{classify}/{id}', 'NavigationLineController@bind');//绑定列表页面
            Route::get('navigationLineRead', 'NavigationLineController@read');//获取数据
            Route::get('navigationLineProductRead', 'NavigationLineController@productRead');//获取产品线路
            Route::post('navigationLineTableEdit', 'NavigationLineController@tableEdit');//表格编辑
            Route::post('navigationLineStart', 'NavigationLineController@start');//启用
            Route::post('navigationLineStop', 'NavigationLineController@stop');//禁止
            Route::post('navigationLineDel', 'NavigationLineController@del');//删除
            Route::post('navigationLineBrushRedis', 'NavigationLineController@brushRedis');//重建缓存
            //
            //微站配置
            Route::get('siteConfig', 'SiteController@siteConfig');//站点配置读取
            Route::post('microSiteSave', 'SiteController@microSiteSave');//微站保存
            Route::post('alikeSiteSave', 'SiteController@alikeSiteSave');//通用保存
            Route::post('serInfoSiteSave', 'SiteController@serInfoSiteSave');//客服信息保存
            //
            //广告配置
            Route::resource('advertisementAD', 'AdvertisementController');
            Route::get('adRead', 'AdvertisementController@read');//获取页面数据
            Route::post('adTableEdit', 'AdvertisementController@tableEdit');//表格编辑
            Route::post('adStart', 'AdvertisementController@start');//启用
            Route::post('adStop', 'AdvertisementController@stop');//禁止
            Route::post('adDel', 'AdvertisementController@del');//删除
            //
            //路由管理
            Route::resource('route', 'RouteController');
            Route::post('storeSon', 'RouteController@storeSon');//子项路由添加
            Route::get('routeSon/{id}/edit', 'RouteController@routeSonEdit');//路由编辑页
            Route::get('buttonPackage/{id}', 'RouteController@buttonPackage');//路由按钮套餐生成

            //小强系统相关
            Route::group(['prefix' => 'xiaoqiang/', 'namespace' => 'XiaoQiang'], function () {
                //
                //小强客户联系人管理
                Route::resource('userBase', 'UserBaseController');
                Route::get('userBaseRead', 'UserBaseController@read');//获取页面数据
//                //线路维护
                Route::resource('baseLine', 'BaseLineController');
                Route::get('baseLineRead', 'BaseLineController@read');//获取页面数据
                Route::post('baseLineTableEdit', 'BaseLineController@tableEdit');//表格编辑
                Route::get('baseLine/{id}/edit_classify', 'BaseLineController@edit_classify');//获取页面数据
                Route::get('baseLine/{id}/edit_tag', 'BaseLineController@edit_tag');//获取页面数据
                Route::get('baseLineDepartureCity_Destination/{id}/edit', 'BaseLineController@departureCity_destination');//获取页面数据
                Route::post('baseLineDepartureCity_Destination', 'BaseLineController@departureCity_destinationUp');//获取页面数据
//                //
//                //出发城市管理
                Route::resource('departureCity', 'DepartureCityController');
                Route::get('departureCityRead', 'DepartureCityController@read');//获取页面数据
                Route::get('departureCityLineRead', 'DepartureCityController@lineRead');//获取产品线路
                Route::post('departureCityTarget', 'DepartureCityController@target');//根据前端发送的树进行拖动
                Route::post('departureCitySort', 'DepartureCityController@sort');//根据前端发送的树进行排序
                Route::post('departureCityNodeEdit/{id}', 'DepartureCityController@nodeEdit');//设置
                Route::post('departureCityTableEdit', 'DepartureCityController@tableEdit');//表格编辑
                Route::post('departureCityStart', 'DepartureCityController@start');//启用
                Route::post('departureCityStop', 'DepartureCityController@stop');//禁止
                Route::post('departureCityDel', 'DepartureCityController@del');//删除
                //目的地管理
                Route::resource('destination', 'DestinationController');
                Route::get('destinationRead', 'DestinationController@read');//获取页面数据
                Route::get('destinationLineRead', 'DestinationController@lineRead');//获取产品线路
                Route::post('destinationTarget', 'DestinationController@target');//根据前端发送的树进行拖动
                Route::post('destinationSort', 'DestinationController@sort');//根据前端发送的树进行排序
                Route::post('destinationNodeEdit/{id}', 'DestinationController@nodeEdit');//设置
                Route::post('destinationTableEdit', 'DestinationController@tableEdit');//表格编辑
                Route::post('destinationStart', 'DestinationController@start');//启用
                Route::post('destinationStop', 'DestinationController@stop');//禁止
                Route::post('destinationDel', 'DestinationController@del');//删除
            });
        });
        //
    });
});
Route::fallback(function () {
    $url = URL::current();
    LOG::error(site()['ip'] . ' --> open_url ==> ' . $url);
    return view('.sys.system.404');
});
//清除Laravel中的缓存（浏览器）
Route::get('/cache', function () {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});
