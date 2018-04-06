<?php

namespace App\Http\Controllers;

use App\MyTrait\ApiMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BaseController extends Controller
{
  use ApiMessage;

  public function format($data)
  {
    if (!empty($data)) {
      $arr = array();
      foreach ($data as $key => $v) {
        $re = $v;
        if ($v['pid'] == 0) {
          $re['lv'] = 0;
          array_push($arr, $re);
        }
      }
      foreach ($arr as $key => $v) {
        foreach ($data as $k => $val) {
          $re = $val;
          $child = array();
          if ($re['pid'] != 0) {
            $re['lv'] = 1;
            if ($v['id'] == $re['pid'] && array_key_exists('child',  $arr[$key])) {
              array_push($arr[$key]['child'], $re);
            } else {
              array_push($child, $re);
              $arr[$key]['child'] = $child;
            }
          }

        }
      }
      return $arr;
    }
    return null;
  }

  public function upload(Request $request){

    $path = $request->file('file')->store('public');
    return $this->success(Storage::url($path)) ;
  }
}
