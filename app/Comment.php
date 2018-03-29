<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Comment extends  Model
{
  protected $table = 'comment';
  protected $hidden = ['created_at','pid','uid'];
  protected $guarded = ['id'];

  public function post()
  {
    return $this->belongsTo('App\Post','pid');
  }

  public function user()
  {
    return $this->belongsTo('App\User','uid');
  }

  public function reply()
  {
    return $this->hasMany('App\Reply','cid');
  }


}