<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\OrderRecommendation;
use App\Recommendation;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller
{
    //
    public function index(){
        $recomendations = Recommendation::all();
        $users = User::WhereRoleIs('customer')->orderBy('balance','desc')->get();
        $ordered = OrderRecommendation::all();

        return view('admin.dashboard',compact('recomendations','users','ordered'));
    }

}
