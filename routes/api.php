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
Route::prefix('auth')->group(function($router) {
  $router-> post('login', 'AuthController@login');
  $router->post('logout', 'AuthController@logout');

});
Route::middleware('refresh.token')->group(function($router) {
  $router->get('user/info','AuthController@info');// 管理员详情

  $router->get('roles/index','RoleController@index');//角色列表
  $router->get('roles/{role}','RoleController@show');//角色详情
  $router->post('roles/create','RoleController@create');//创建角色
  $router->post('roles/{role}/update','RoleController@update');//更新角色信息
  $router->post('roles/{role}/update_permission','RoleController@updatePermission');//更新角色权限
  $router->post('roles/{role}/destroy','RoleController@destroy');//删除角色

  $router->post('/permission/{permission}/update','PermissionController@update'); //更新权限
  $router->post('/user/{user}/update','UserController@update'); //更新用户资料
  $router->post('/user/{user}/frozen','UserController@frozen');//冻结用户

  Route::resource('post','PostController',['except' => ['update','destroy']]);
  Route::apiResources([
    'permission'=> 'PermissionController',//  权限资源路由
    'user'=>'UserController'//  用户资源路由
  ]);

});