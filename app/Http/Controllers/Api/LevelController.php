<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Level;

class LevelController extends Controller
{
    //
    public function getlevels(){
        $level=Level::all();
        return response()->json(['success'=>'true','data'=>$level]);
    }
}
