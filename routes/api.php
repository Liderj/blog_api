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
//上传图片
Route::post('upload','BaseController@upload');

//后台接口
Route::prefix('auth')->group(function($router) {
    $router-> post('login', 'AuthController@login')->name('admin.login');
    $router->post('logout', 'AuthController@logout');

});
Route::middleware('refresh.token','admin')->group(function($router) {
  $router->get('user/info','AuthController@info')->name('user.info');// 管理员详情
  $router->post('/user/{user}/update','UserController@update')->name('user.update'); //更新用户资料
  $router->post('/user/{user}/frozen','UserController@frozen')->name('user.frozen');//冻结用户

  $router->get('roles/index','RoleController@index')->name('roles.index');//角色列表
  $router->get('roles/{role}','RoleController@show')->name('roles.show');//角色详情
  $router->post('roles/create','RoleController@create')->name('roles.create');//创建角色
  $router->post('roles/{role}/update','RoleController@update')->name('roles.update');//更新角色信息
  $router->post('roles/{role}/update_permission','RoleController@updatePermission')->name('roles.update_permission');//更新角色权限
  $router->post('roles/{role}/destroy','RoleController@destroy')->name('roles.destroy');//删除角色
  $router->post('/permission/{permission}/update','PermissionController@update')->name('permission.update'); //更新权限
  $router->post('/post/destroy/{post}','PostController@destroy')->name('post.destroy');//删除文章
  $router->get('/post/top','PostController@top');//热推文章列表
  $router->post('/post/hot/{post}','PostController@setHot')->name('post.hot');//热推文章
  $router->post('/post/{post}/disable','PostController@disable')->name('post.disable');//关闭文章
  $router->post('/post/comment/{post}','PostController@setComment')->name('post.comment');//关闭文章评论
  Route::resource('post','PostController',['only' => ['index','show']]);
  Route::resource('category','CategoryController',['only' => ['index','store']]);
  $router->post('/category/{category}/update','CategoryController@update')->name('category.update') ;//更新分类
  $router->post('/category/{category}/destroy','CategoryController@destroy')->name('category.destroy') ;//删除分类

  $router->get('/comment/{comment}','CommentController@show')->name('comment.show');//评论详情
  $router->get('/comment','CommentController@index')->name('comment.index');//评论列表
  $router->post('/comment/{comment}','CommentController@destroy')->name('comment.destroy');//删除评论

  $router->get('/reply','ReplyController@index')->name('reply.index');//回复列表
  $router->post('/reply/{reply}','ReplyController@destroy')->name('reply.destroy');//删除回复

  Route::resource('keyword','KeywordController',['only' => ['index','store','destroy']]);


  Route::apiResources([
    'permission'=> 'PermissionController',//  权限资源路由
    'user'=>'UserController'//  用户资源路由
  ]);

});


//前台接口
Route::prefix('front-end')->group(function ($router){
  $router-> post('login', 'frontEnd\AuthController@login');
  $router->post('register','frontEnd\AuthController@register');
  $router->post('logout', 'frontEnd\AuthController@logout');
  Route::resource('category','CategoryController',['only' => ['index','store']]);
  Route::middleware('refresh.token')->group(function ($router){
    $router->post('/changePWd','frontEnd\AuthController@changePWd');
    $router->post('/changeInfo','frontEnd\AuthController@changeInfo');
    $router->get('user','frontEnd\AuthController@info');// 用户资料
    $router->get('/post/top','PostController@top');//热推文章
    $router->get('/post/like/{post}','frontEnd\PostController@like');
    $router->post('/post/add','frontEnd\PostController@store');
    Route::resource('post','frontEnd\PostController',['only' => ['index','show']]);
    $router->get('/comment','frontEnd\CommentController@index');
    $router->post('/comment','frontEnd\CommentController@addComment');
    $router->post('/reply','frontEnd\CommentController@addReply');

  });
});