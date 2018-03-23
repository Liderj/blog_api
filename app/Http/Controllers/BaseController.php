<?php

namespace App\Http\Controllers;

use App\MyTrait\ApiMessage;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    use ApiMessage;
}
