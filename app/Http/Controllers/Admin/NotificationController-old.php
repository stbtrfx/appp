<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class NotificationController extends Controller
{




    public function create()
    {
        return view('admin.notification.send');

    } // end of edit

    public function send(Request $request)
    {
        $data = $request -> validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'user_type'=>'required',

        ]);
if($request->user_type == 'vip'){
    $users = User::where('vip','1')->whereRoleIs('customer')->get();

}elseif($request->user_type == 'free'){
    $users = User::where('vip','0')->whereRoleIs('customer')->get();
}else{
    $users = User::whereRoleIs('customer')->get();
}

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
                 'title'=>$request->title,
                 'body'=>$request->body,
                  'type'=>'general'
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
         session() -> flash('success', trans('Notification Sent successfully'));
         return redirect() -> route('Notification.create');



    } // end of send

} // end of controller
