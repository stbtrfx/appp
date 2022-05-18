<?php

namespace App\Http\Controllers\Api;

use App\Academy;
use App\User;
use App\OrderRecommendation;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AcademyController extends Controller
{
    //
    public function getAcademies($user_id){

        $academy = Academy::with(array('user' => function($query) use ($user_id) {
            $query->where([['service', 'academy'],['user_id',$user_id]]);
       }))->get();

        foreach($academy as $index=>$r){
            // $data[] = $r;
        foreach($r->user as $u){
                if(($u->id ==  $user_id)){
                    $academy[$index]['is_buy'] = 1;
                }else{
                    $academy[$index]['is_buy'] = 0;
                }
        }
        }

        return response()->json(['success'=>'true','data'=>$academy]);
    }


    public function buyAcademy(Request $request){

        $validator = Validator::make($request->all(), [
            'user_id'=>'required',
            'academy_id'=>'required',
            'payByCoins'=>'required',
        ]);

        if ($validator->fails()) {

            return response()->json(['success'=>'false', 'error'=>$validator->messages()]);

        } else {
            $user = User::find($request->user_id);
           $academy =Academy::find($request->academy_id);
        //   if($user->level_id < $academy->level){
        //     return response()->json(['success'=>'false', 'error'=>'You must to be in min level  '. $academy->levels->title_en]);
        //   }else{
           $data=[
            'user_id'=>$request->user_id,
           'service'=>'academy',
           'service_id'=>$request->academy_id,

        ];
           if($request->payByCoins == 1){

    // pay by coins
           if($user->balance >= $academy->price_coins){

                        $data['type']='Coins';
                        $data['total']=$academy->price_coins;
                        DB ::beginTransaction();
                    $order= OrderRecommendation::create($data);
                    //update user balnce
                    $user->balance -= $academy->price_coins;
                    $user->save();
                    DB ::commit();
                    return response()->json(['success'=>'true']);
           }else{
            return response()->json(['success'=>'false', 'error'=>'No Enough Coins']);
           }

        }else{
            // pay by visa

                $data['type']='Mony';
                $data['total']=$academy->price;

                $order= OrderRecommendation::create($data);
                return 'paybyvisa';
            // end pay by visa
        }
    // }
    }
}
}
