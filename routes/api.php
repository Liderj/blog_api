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
  $router->get('user/info','UserController@info');
  $router->get('permission/all','PermissionController@all');
  $router->get('permission/{id}','PermissionController@getOne');
  $router->get('permission/{id}/delete','PermissionController@delete');

  $router->get('roles/index','RoleController@index');//角色列表
  $router->get('roles/{id}','RoleController@show');//角色详情
  $router->post('roles/create','RoleController@create');//创建角色
  $router->post('roles/{id}/update','RoleController@update');//更新角色信息
  $router->post('roles/{id}/update_permission','RoleController@updatePermission');//更新角色权限
  $router->post('roles/{id}/destroy','RoleController@destroy');//删除角色
});