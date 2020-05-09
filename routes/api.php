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
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api'
], function($api) {
    $api->get('user/show/{id?}', 'UserApiController@show');
//    $api->get('user/all', 'UserApiController@all');
    $api->get('user/paginate', 'UserApiController@paginate');
    //微信登陆code
    $api->get('weixin/code', 'UserApiController@code');
    //更新用户
    $api->post('weixin/updateUserInfo', 'UserApiController@updateUserInfo');
    //上传图片
    $api->post('weixin/upload', 'UserApiController@upload');
    //缓存测试
    $api->get('/cache', 'UserApiController@cache');
    //获取token
    $api->get('/token', 'UserApiController@dy');
});
