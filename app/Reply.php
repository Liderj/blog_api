<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    //
  protected  $table = 'reply';
  protected $hidden = ['cid','f_uid','t_uid','updated_at'];
  protected $guarded = ['id'];

  public function comment()
  {
    return $this->belongsTo('App\Comment','cid');
  }

  public function user()
  {
    return $this->belongsTo('App\User','f_uid');
  }
}
