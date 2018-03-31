<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->success(Category::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $rules = [
        'name' => [
          'required',
          'unique:category,name'
        ],
      ];
      $messages = [
        'name.required' => '分类名称不能为空',
        'name.unique' => '该分类名称已存在',
      ];
      $this->validate($request, $rules, $messages);
      $category= new Category($request->only('name'));
      return $category->save() ? $this->message('分类添加成功') : $this->failed('分类添加失败');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
      $rules = [
        'name' => 'required',
      ];
      $messages = [
        'name.required' => '分类名称不能为空',
      ];
      $this->validate($request, $rules, $messages);
      if(Category::where('name',$request->input('name'))->count()&&$category->name !=$request->input('name')){
        return $this->failed('此名称已存在');
      }
      $category->name = $request->input('name');
      return $category->save() ? $this->message('修改成功') : $this->failed('修改失败');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Category $category)
    {
//      删除分类需验证登录密码
      if(!$request->input('password') || !Hash::check( $request->input('password'),Auth::user()->password)){
        return $this->failed('密码错误');
      }
      if($category->id ===1 || $category->id==2){
        return $this->failed('该分类不允许删除');
      }
      //创建数据库事务
      DB::beginTransaction();
      try {
        //    将拥有该分类的文章全部分配到无分类下
        Post::where('cid', $category->id)->update(['cid' => 1]);

        //    删除分类
        $res = $category->delete();

        DB::commit();
        return $res ? $this->message('分类删除成功') : $this->failed('分类删除失败');
      } catch (\Exception $exception) {
        //      遇到异常回滚事务
        　DB::rollback();
        return $this->failed('操作失败，请刷新重试');
      }
    }
}
