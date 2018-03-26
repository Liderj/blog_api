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
  protected $hidden = ['created_at','updated_at'];

}