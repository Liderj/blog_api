<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/26
 * Time: 22:22
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Post extends  Model
{
  protected $hidden = ['updated_at'];
  protected $guarded = ['id'];

  public function user()
  {
    return $this->belongsTo('App\User','pid');
  }

  public function category()
  {
    return $this->belongsTo('App\Category','cid');

  }

  public function comments()
  {
    return $this->hasMany('App\Comment','pid');
  }
}