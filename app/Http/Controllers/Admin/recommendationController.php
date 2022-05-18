<?php

namespace App\Http\Controllers\Admin;

use App\Currency;
use App\Http\Controllers\Controller;
use App\OrderRecommendation;
use Illuminate\Http\Request;
use App\Recommendation;


use App\Traits\imagesTrait;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Auth;

class recommendationController extends Controller
{
    use imagesTrait;

    public function index()
    {

        $recommendations = Recommendation ::orderBy('id','desc')->paginate(10);

        return view('admin.recommendation.index', compact('recommendations'));
    }

    public function create()
    {
        $currency = Currency::all();

        return view('admin.recommendation.create',compact('currency'));
    }

    public function store(Request $request)
    {
        // dd($request);
        $data = $request -> validate([
            'title_en' => 'required|string',
            'price' => 'required|numeric',
            'price_coins' => 'required|numeric',
            'open_price' => 'required',

        ]);
        $data = $request->except(['_token','add']);

        if ($request -> has('image')) {
            $image = $this -> saveImages($request -> image, 'images/recommendations');
            $data['image'] = $image;
        }
        $data['active'] = $request -> active;
        $data['show'] = $request -> show;
        // $users = User::where('vip','1')->whereRoleIs('customer')->get();
        $users = User::get();
    // dd($users);
       $recommendation= Recommendation ::create($data);
        session() -> flash('success', trans('added successfully'));





        // notifications





            $SERVER_API_KEY = env('SERVER_API_KEY');
            //   $token_1 = 'exn9J8SqT6yQiS4vv8LSeM:APA91bHESgzENbXb8kclNUp--G5uHS965mhllPqliLyfQHsxKGu3bSHbCmcQeFrFYZgDMs2mm5sV--Zo9USPkfm6TVntofE1ICS4RO6H-t2__Dyx4lRLx9DY_DOQ_GZKKmcwOCs6X8kQ';
        //    $token_1= $user->fcm_token;

        foreach($users as $u){
        $data = [

                "registration_ids" => [
                    $u->fcm_token,
                ],

                'data'=> [
                    'id'=>$recommendation,
                    'title'=> 'تم اضافة توصية جديدة',
                    'body'=>$recommendation->title_en,
                    'type'=>'new_recommendation'
                ],

                 "sound"=> "default" // required for sound on ios

            ];
            $dataString = json_encode($data);
            $headers = [

                'Authorization: key=' . $SERVER_API_KEY,

                'Content-Type: application/json',

            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

            curl_setopt($ch, CURLOPT_POST, true);

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $response = curl_exec($ch);
        }
            //  dd($response);
            // // end notification

        // return response()->json(['success'=>'true', 'data'=>$order->id]);


        // end notification


        return redirect() -> route('recommendation.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $recommendation = Recommendation::find($id);
        $currency = Currency::all();

        return view('admin.recommendation.edit', compact('recommendation','currency'));

    }

    public function update(Request $request, $id)
    {
        $data = $request -> validate([
            'title_en' => 'required|string',
            'price' => 'required|numeric',
            'price_coins' => 'required|numeric',
            'open_price' => 'required',
        ]);

        $recommendation = Recommendation::find($id);
        $data = $request->except(['_token','add', 'urlPage']);


        DB::beginTransaction();

        if ($request -> has('image')) {
            if($recommendation -> image != 'default.png'){
                Storage ::disk('public_uploads') -> delete('recommendations/' . $recommendation -> image);
            }
            $image = $this -> saveImages($request -> image, 'images/recommendations');
            $data['image'] = $image;
        }
        $data['active'] = $request -> active;
        $data['show'] = $request -> show;


        $recommendation -> update($data);

        DB::commit();

        // $users = User::where('vip','1')->whereRoleIs('customer')->get();
        $users = User::get();
         // notifications





         $SERVER_API_KEY = env('SERVER_API_KEY');
         //   $token_1 = 'exn9J8SqT6yQiS4vv8LSeM:APA91bHESgzENbXb8kclNUp--G5uHS965mhllPqliLyfQHsxKGu3bSHbCmcQeFrFYZgDMs2mm5sV--Zo9USPkfm6TVntofE1ICS4RO6H-t2__Dyx4lRLx9DY_DOQ_GZKKmcwOCs6X8kQ';
     //    $token_1= $user->fcm_token;

     foreach($users as $u){
     $data = [

             "registration_ids" => [
                 $u->fcm_token,
             ],

             'data'=> [
                 'id'=>$recommendation,
                 'title'=> 'تم تعديل توصية ',
                 'body'=>$recommendation->title_en,
                 'type'=>'update_recommendation'
             ],

              "sound"=> "default" // required for sound on ios

         ];
         $dataString = json_encode($data);
         $headers = [

             'Authorization: key=' . $SERVER_API_KEY,

             'Content-Type: application/json',

         ];

         $ch = curl_init();

         curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

         curl_setopt($ch, CURLOPT_POST, true);

         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

         curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

         $response = curl_exec($ch);
     }
         //  dd($response);
         // // end notification

     // return response()->json(['success'=>'true', 'data'=>$order->id]);


     // end notification






        session() -> flash('success', trans('Updated Successfully'));

        if(isset($request->urlPage)){
            return redirect()->to($request->urlPage);
        }else{
            return redirect() -> route('recommendation.index');
        }
        // dd(url()->current());
        // return redirect() ->back();
    }

    public function destroy($id)
    {
        $recommendation = Recommendation ::find($id);

        DB::beginTransaction();

        if($recommendation -> image != 'default.png'){
            Storage ::disk('public_uploads') -> delete('recommendations/' . $recommendation -> image);
        }
        $recommendation ->  delete();

        DB::commit();

        session() -> flash('success', trans('deleted successfully'));
        return redirect() -> route('recommendation.index');
    }

    public function getOrderRecommendation(){
        $orders = OrderRecommendation::where('service','recommendation')->paginate(10);
        //  dd($orders);
        return view('admin.OrderedRecommendation.index',compact('orders'));
    }

    public function destroyOrderRecommendation($id){
        $order = OrderRecommendation ::find($id);
        $order ->  delete();
        session() -> flash('success', trans('deleted successfully'));
        return redirect() -> route('getOrder.recommendations');
    }

}
