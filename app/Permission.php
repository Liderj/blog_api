<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
  protected $hidden = ['created_at','updated_at','pivot','status'];
  protected $guarded = ['id'];

  public function roles()
  {
    return $this->belongsToMany('App\Role', 'roles_permissions', 'perid', 'rid');

  }
}
