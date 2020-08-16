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
        //不满意案件待办列表
        Route::group(['prefix' => 'handlingManagement/', 'namespace' => 'Feedback'], function () {
            //列表
            Route::resource('feedback', 'FeedbackController');
            Route::get('feedbackRead', 'FeedbackController@read');//获取页面数据
            Route::post('feedbackTableEdit', 'FeedbackController@tableEdit');//表格编辑
            Route::post('feedbackCancel', 'FeedbackController@cancel');//取消
            Route::get('feedbackInfo', 'FeedbackController@read');//详情
            Route::get('feedbackChuLi/{id}/edit', 'FeedbackController@ChuLi');//处理页面
            Route::post('feedbackChuLi/{id}', 'FeedbackController@ChuLiUp');//处理页面
            Route::get('feedbackPrint/{id}', 'FeedbackController@print');//打印
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
        });
        //框架/系统运维相关
        Route::group(['prefix' => 'system/', 'namespace' => 'System'], function () {
            //路由管理
            Route::resource('route', 'RouteController');
            Route::post('storeSon', 'RouteController@storeSon');//子项路由添加
            Route::get('routeSon/{id}/edit', 'RouteController@routeSonEdit');//路由编辑页
            Route::get('buttonPackage/{id}', 'RouteController@buttonPackage');//路由按钮套餐生成
        });
        //
    });
});
//清除Laravel中的缓存（浏览器）
Route::get('/cache', function () {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});
