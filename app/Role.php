<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //
  protected $hidden = ['created_at','updated_at'];


  public function permission()
  {
    return $this->belongsToMany('App\Permission','roles_permissions','rid','perid');
  }

}
