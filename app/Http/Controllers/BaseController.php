<?php

namespace App\Http\Controllers;

use App\MyTrait\ApiMessage;
use Illuminate\Http\Request;

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
          } else {
            $child = array();
            foreach ($arr as $k => $val) {
              $re['lv'] = 1;
              if ($val['id'] == $re['pid'] && array_key_exists('child', $val)) {
                array_push($arr[$k]['child'], $re);
              } else {
                array_push($child, $re);
                $arr[$k]['child'] = $child;
              }
            }
          }
        }
        return $arr;
      }
      return null;
    }
}
