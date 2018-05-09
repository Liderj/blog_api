<?php

namespace App\Http\Controllers;

use App\Reply;
use Illuminate\Http\Request;

class ReplyController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
//        获取所有回复
      $page_size = $request->query('page_size', 10);//每页条数
      $page = $request->query('page',1);//当前页数
      $search = $request->query('search');//搜索
      $cid =  $request->query('cid');//评论id
//    查询结果分页
      $res = Reply::where([
        ['content', 'like', '%' . $search . '%'],
      ])->where(function ($query ) use ($cid){
        if($cid !== null){
          $query->where('cid',$cid);
        }
      })
        ->latest()
        ->skip(($page - 1) * $page_size)->take($page_size)->get();
//    获取总条数
      $count = Reply::where([
        ['content', 'like', '%' . $search . '%'],
      ])->count();
//    分页结果添加用户昵称
      foreach ($res as $key=>$val){
        $val['user']= $val->user()->get(['nickname'])->first()->nickname;
      }
      $data = [
        'count'=>$count,
        'current_page'=>$page,
        'list'=>$res->isNotEmpty()?$res:null
      ];
      return $this->success($data);
    }

    public function destroy(Reply $reply)
    {
//       删除回复
      return  $reply->delete() ? $this->message('删除回复成功') : $this->failed('删除回复失败');
    }
}
