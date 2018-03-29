<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    protected $guarded = [
      'id'
    ];
    protected $hidden = ['password','created_at','updated_at'];
    public function getJWTIdentifier()
    {
      return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
      return [];
    }

    public function post()
    {
      return $this->hasMany('App\Post','pid');
    }
    public function comment()
    {
      return $this->hasMany('App\Comment','uid');
    }

    public function reply()
    {
      return $this->hasMany('App\Reply','f_uid');
    }
}
