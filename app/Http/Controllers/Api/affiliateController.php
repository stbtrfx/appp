<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


use App\Affiliate;
use App\User;

use Str;
use Illuminate\Support\Facades\DB;
class affiliateController extends Controller
{

    public function genCode(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id'=>['required'],
        ]);

        if ($validator->fails()) {

            return response()->json(['success'=>'false', 'error'=>$validator->messages()]);

        } else {
           $user =  User::where('id',$request->user_id)->first();

            $data = $request->all();
            $code= Str::random(10);

            $user->balance +=$request->points;
            $user->code =$code;
            $user->save();

          return response()->json(['success'=>'true', 'date'=>$user]);
    }
    }

    public function addPoints(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id'=>['required'],
            'code'=>['required'],
            'points'=>['required'],
        ]);

        if ($validator->fails()) {

            return response()->json(['success'=>'false', 'error'=>$validator->messages()]);

        } else {
            $code_owner =  User::where('code',$request->code)->first();
             $new_user= User::where('id',$request->user_id)->first();

            if(isset($code_owner)){
DB::beginTransaction();
                $data = $request->all();
                $gen_code = Str::random(10);



                $code_owner->balance += $request->points;

                // $new_user= new Affiliate();

                 $new_user->balance += $request->points;

                $new_user->code = $gen_code;
                $code_owner->save();
                $new_user->save();


            }else{
                return response()->json(['success'=>'false', 'error'=>'الكود غير صحيح']);
            }
DB::commit();
            // if(!isset($new_user)){
            //     $new_user->user_id = $request->user_id;
            //     $new_user->points += $request->points;
            //     $new_user->save();
            // }else{
            //     return response()->json(['success'=>'false', 'error'=>'خطأ في المستخدم']);
            // }
            return response()->json(['success'=>'true', 'date'=>[[$code_owner->id, $code_owner->balance],[$new_user->id,$new_user->balance]]]);
        }
    }

    public function getUserPoints($user_id){

        $user = User::where('id',$user_id)->first();

        if(isset($user)){
            return response()->json(['success'=>'true',
            'data'=>$user->balance]);
        }else{

            return response()->json(['success'=>'false',
            'data'=>'There is no user with this data']);
        }



    }

}
