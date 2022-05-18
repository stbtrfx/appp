<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Recommendation;
use App\OrderRecommendation;
use Carbon\Carbon;
use App\Http\Resources\ProductCollection;
use BeyondCode\Vouchers\Models\Voucher;
use Validator;
use App\User;
use Illuminate\Support\Facades\DB;

use BeyondCode\Vouchers\Facades\vouchers as Vouchers;


class RecommendationController extends Controller
{
    //
    public function getRecommendations($user_id){
            $Recommendation =  Recommendation::with(array('user' => function($query) use ($user_id) {
                $query->where([['service', 'recommendation'],['user_id',$user_id]]);
           }))->orderBy('id','desc')->get();
            foreach($Recommendation as $index=>$r){
                // $data[] = $r;
            foreach($r->user as $u){
                    if(($u->id ==  $user_id)){
                        $Recommendation[$index]['is_buy'] = 1;
                    }else{
                        $Recommendation[$index]['is_buy'] = 0;
                    }
            }
            }
            return response()->json(['success'=>'true', 'data'=>$Recommendation]);
    }

    public function buyRecommendation(Request $request){

        $validator = Validator::make($request->all(), [
            'user_id'=>'required',
            'recommendation_id'=>'required',
            'payByCoins'=>'required',
        ]);

        if ($validator->fails()) {

            return response()->json(['success'=>'false', 'error'=>$validator->messages()]);

        } else {
           $user = User::find($request->user_id);
           $recommendation =Recommendation::find($request->recommendation_id);
           $data=[
            'user_id'=>$request->user_id,
           'service'=>'recommendation',
           'service_id'=>$request->recommendation_id,
           'count'=>1,

        ];
           if($request->payByCoins == 1){

    // pay by coins
           if($user->balance >= $recommendation->price_coins){
// check number of times for buy recommendation
            // $user_order_count_today = OrderRecommendation::where([['user_id', $request->user_id]])->whereDate('created_at','=',Carbon::today()->toDateString())->count();

            // if($user_order_count_today == 3){ //this value must be variable
            // return response()->json(['success'=>'false', 'error'=>'You cant open new recommendation today']);
            // }
            // end of check times

            $data['type']='Coins';
                        $data['total']=$recommendation->price_coins;
                        DB ::beginTransaction();
                    $order= OrderRecommendation::create($data);
                    //update user balnce
                    $user->balance -= $recommendation->price_coins;
                    $user->save();
                    DB ::commit();
           }else{
            return response()->json(['success'=>'false', 'error'=>'No Enough Coins']);
           }

        }else{
            // pay by visa

                $data['type']='Mony';
                $data['total']=$recommendation->price;

                $order= OrderRecommendation::create($data);
                // return 'paybyvisa';
            // end pay by visa
        }

        return response()->json(['success'=>'true', 'data'=>$order]);
        }

    }

    public function getUserOrders($id){
        $orders_recommendations = OrderRecommendation::where([['user_id',$id],['service','recommendation']])->with('recommendation')->get();
        $orders_academy = OrderRecommendation::where([['user_id',$id],['service','academy']])->with('academy')->get();
        return response()->json(['success'=>'true', 'data'=>['recommendation'=>$orders_recommendations,'academy'=>$orders_academy]]);
    }

   public function openRecommendation(Request $request){
    $validator = Validator::make($request->all(), [
        'user_id'=>'required',
        'recommendation_id'=>'required',
    ]);

    if ($validator->fails()) {

        return response()->json(['success'=>'false', 'error'=>$validator->messages()]);

    } else {
            $order = OrderRecommendation::where([['user_id',$request->user_id],['service','recommendation'],['service_id',$request->recommendation_id]])->first();
            if(($order)&&($order->count < 2)){
                $order->count += 1;
                $order->save();
                return response()->json(['success'=>'true']);
            }else{
                $order->delete();
                return response()->json(['success'=>'true', 'data'=>'deleted']);
            }


    }
   }

    // public function getProductCategory($id){
    //     $products= Product::where('category_id',$id)->get();
    //     return response()->json(['success'=>'true', 'data'=>ProductCollection::collection($products)]);
    // }

    // public function getProduct($id){
    //     $product= Product::where('id',$id)->with('category')->get();
    //     return response()->json(['success'=>'true', 'data'=>ProductCollection::collection($products)]);
    // }

    // public function search($product){
    //     $products = Product::where('name_en','Like','%'.$product.'%')->orWhere('name_ar','Like','%'.$product.'%')->get();
    // // dd($products);
    // return response()->json(['success'=>'true', 'data'=>ProductCollection::collection($products)]);

    // }

    // public function promocode( Request $request){
    //     $copon = Voucher ::where([['code',$request->code],['expires_at', '>=', Carbon::now()]]) ->first();

    //     if($copon){
    //         $discount=   $copon->data->get('discount');
    //         return response()->json(['success'=>'true', 'data'=>$discount]);
    //     }else{
    //         return response()->json(['success'=>'false', 'data'=>'Code not valid']);
    //     }

    // }



}
