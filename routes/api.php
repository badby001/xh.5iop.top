<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
//工具方法
Route::post('/tool/getHashPhP', 'Api\ToolController@getHashPhP');//获取php的hash值
Route::post('/tool/getNewID', 'Api\ToolController@getNewID');//获取getNewID
Route::post('/tool/getCaptcha', 'Api\ToolController@getCaptcha');//获取验证码
Route::post('/tool/getToken', 'Api\ToolController@getToken');//获取token

//业务方法
Route::group(['prefix' => '/', 'namespace' => 'Api', 'middleware' => ['apiVerifySign']], function () {
    Route::post('admin/setAdmInfo', 'IndexController@setAdmInfo');//完善用户info表和各个权限表
    Route::post('pub/setLog', 'IndexController@setLog');//写入日志
    Route::post('admin/getAdmGroup', 'IndexController@getAdmGroup');//获取当前登录账号的数据权限
});

