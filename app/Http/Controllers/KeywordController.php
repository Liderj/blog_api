<?php

namespace App\Http\Controllers;

use App\Keyword;
use Illuminate\Http\Request;

class KeywordController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        获取所有敏感词
        return $this->success(Keyword::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        添加敏感词
      $rules = [
        'content' => [
          'required',
          'unique:keywords,content'
        ],
      ];
      $messages = [
        'content.required' => '内容不能为空',
        'content.unique' => '已存在',
      ];
      $this->validate($request, $rules, $messages);
      $keyword= new Keyword($request->only('content'));
      return $keyword->save() ? $this->message('敏感字添加成功') : $this->failed('敏感字添加失败');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Keyword $keyword)
    {
//        删除敏感词
         return $keyword->delete() ? $this->message('删除成功') : $this->failed('删除失败');
     }
}
