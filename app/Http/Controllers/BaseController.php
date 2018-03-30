<?php

namespace App\Http\Controllers;

use App\MyTrait\ApiMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

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

  public
  function paginate($table, $page_size = 10, $current_page = 1, $search)
  {
    if (empty($table)) {
      return null;
    }
    $count = DB::table($table)
      ->select(DB::raw('SELECT * FROM ' . $table . 'WHERE CONCAT(`mobile`,`nickname`) LIKE \'%' . $search . '%\''))->get();
//    $offset = ($current_page - 1) * $page_size;

//    $data = Db::select('select * from email limit $offset,$page_size');
    return $count;
  }
}
