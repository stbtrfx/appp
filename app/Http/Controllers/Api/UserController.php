<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Level;
use App\Product;
use Illuminate\Http\Request;
use App\Vip;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\OrderRecommendation;
use App\Recommendation;
use App\SiteSetting;
use Carbon\Carbon as CarbonCarbon;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\orderResource;

class UserController extends Controller
{



    public function isFav(Request $request)
    {


        $recommendations = Recommendation::all();

        foreach($recommendations as $index=>$r){
            // $data[] = $r;
            foreach($r->user as $u){
            if(($u->id ==  $request->user_id)){
                $recommendations[$index]['is_buy'] = 1;
            }else{
                $recommendations[$index]['is_buy'] = 0;
            }
        }
        }
           return $recommendations;


        if (isset($recommendations)) {
            return response()->json(['success' => 'true', 'data' => 'true']);
        } else {
            return response()->json(['success' => 'true', 'data' => 'false']);
        }
    } // end of isFav



    public function updateUser(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'name' => ['string','max:255'],
            'address' => ['string'],
            'lat' => ['max:255'],
            'lng' => ['max:255'],
            'email' => [ 'string', 'email', 'max:255', 'unique:users'],
            'phone' => [ 'string', 'max:255', 'unique:users'],
        ]);




        if ($validator->fails()) {
            return response()->json(['success' => 'false', 'error' => $validator->messages()]);
        } else {

            $data =$request->all();
            if(isset($request->password)){
                if (Hash::check($request->old_password , $user->password)){
              $data['password'] = Hash::make($request->password);
                }else{
                    return response()->json(['success' => 'false', 'error' => 'Old password not true']);
                }
            }

            $user = User::find($request->user_id);
            $user->update($data);
            return response()->json(['success' => 'true', 'data' => $user]);
        }
    }

    public function Setting(){
        $setings=  SiteSetting::select('hot_line')->first();
        return response()->json(['success' => 'true', 'data' => $setings]);

    }


    public function upgradeLevel(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
           'level'=>'required',
           'payByCoins'=>'required',
        ]);
        if ($validator->fails()) {

            return response()->json(['success' => 'false', 'error' => $validator->messages()]);
        } else {
            $user = User::find($request->user_id);
            $level = Level::find($request->level);
            $data=[
                'user_id'=>$request->user_id,
               'service'=>'level',
               'service_id'=>$request->level,

            ];
if($request->payByCoins == 1){

    // pay by coins
    if($level->price_coins <= $user->balance){

        $data['type']='Coins';
        $data['total']=$level->price_coins;
        DB ::beginTransaction();
        $order= OrderRecommendation::create($data);

        $user->level_id = $request->level;
        $user->balance -= $level->price_coins;
        $user->save();
        DB ::commit();
        }else{
            return response()->json(['success' => 'false', 'error' => 'No Enough Coins']);
        }
        // end pay by coins

}else{
    // pay by visa
    DB ::beginTransaction();
    $data['type']='Mony';
    $data['total']=$level->price;

    $order= OrderRecommendation::create($data);

        $user->level_id = $request->level;
        $user->save();
        DB ::commit();
    // return 'paybyvisa';

    // end pay by visa
}



        }
        return response()->json(['success' => 'true' , 'data'=>$user]);
    }


    public function buyVip(Request $request){ // to by Vip by coins or visa
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
             'vip' => 'required',
            // 'from' => 'required',
            // 'to' => 'required',

        ]);
        if ($validator->fails()) {

            return response()->json(['success' => 'false', 'error' => $validator->messages()]);
        } else {
            // payment for vip ......
            $user=User::find($request->user_id);
            $vip = Vip::find($request->vip);
            // if paid
            $user->vip = 1;

            $user->from =  Carbon::now();
            $user->to =   Carbon::now()->addMonths($vip->months_no);

            $user->save();
            return response()->json(['success' => 'true' , 'data'=>$user]);
            // if not paid
            return response()->json(['success' => 'false']);

        }
    }

    public function updateVip(Request $request){ //to update vip state when open mobile app
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);
        if ($validator->fails()) {

            return response()->json(['success' => 'false', 'error' => $validator->messages()]);
        } else {
            $user = User::find($request->user_id);
                // here code  get user with user_id
                if(($user && ($user->vip != 0))){
                    $current_date = CarbonCarbon::now();
                    $expire_date = $user->to;
                    if($expire_date < $current_date ){
                        $user->vip = 0;
                        $user->save();
                    }
                    return response()->json(['success' => 'true', 'data' => $user]);
                }else{
                    return response()->json(['success' => 'true', 'data' => 'no subscription in vip']);
                }
                // check if date < $request->date update vip to 0
        }
    }
    public function addPoints(Request $request){ //to add points after watch video
        $validator = Validator::make($request->all(), [
            'user_id'=>['required'],
        ]);

        $user = User::find($request->user_id);
        if(isset($user)){
            $user->balance += 50;
            $user->save() ;
            return response()->json(['success' => 'true' , 'data'=>$user->balance]);
        }else{
            return response()->json(['success' => 'false', 'error' => 'Error in user Data']);
        }


    }

public function notifications($user_id){
        
       

        $user = User::find($user_id);

        $notifications = $user->notifications()->paginate(10);

        return response()->json(['success' => 'true' , 'data' => $notifications]);
    }

    public function notificationsMarkAsRead(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id'=>['required'],
        ]);
        if ($validator->fails()) {

            return response()->json(['success' => 'false', 'error' => $validator->messages()]);
        }
        $user = User::find($request->user_id);

        $user->unreadNotifications->markAsRead();

        return response()->json(['success' => 'true']);
    }

} //end of controller
