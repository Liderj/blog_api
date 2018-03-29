<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Comment extends  Model
{
  protected $table = 'comment';
  protected $hidden = ['created_at','updated_at','pid','uid'];
  protected $guarded = ['id'];

  public function post()
  {
    return $this->belongsTo('App\Post');
  }
}