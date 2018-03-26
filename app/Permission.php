<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
  //
  protected $hidden = ['created_at','updated_at','pivot','status'];

  public function tree($data,$pid=0,$lv=0)
  {
    $data= $this->where('pid', $pid)->get();
    $lv++;
    if(!empty($data)){
      $tree = array();
      foreach ($data as $val) {
        $child = $this ->tree($val['id'],$lv);
        if(empty($child)){
          $child = null;
        }
        $val['child'] = $child;
        $tree[] = array('name'=>$val->name,'id'=>$val->id,'child'=>$val->child);
      }
    }
    return $tree;
  }
}
