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

});