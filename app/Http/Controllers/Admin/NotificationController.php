<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;



class NotificationController extends Controller
{



    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(10);
        // $notifications = notifications()->all();
        return view('admin.notification.index', compact('notifications'));

    } // end of index
    public function create()
    {
        return view('admin.notification.send');

    } // end of create

    public function send(Request $request)
    {
        $data = $request -> validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'user_type'=>'required',

        ]);
        if($request->user_type == 'vip'){
            $users = User::where('vip','1')->orWhereRoleIs('admin')->get();


        }elseif($request->user_type == 'free'){
            $users = User::where('vip','0')->orWhereRoleIs('admin')->get();
        }else{
            $users = User::all();
        }

        foreach($users as $u){
            sendmessage($u->fcm_token,$request->title,$request->body);
            $data['user'] = Auth::user();
            $u->notify(new \App\Notifications\notifications($data));
        }
         //  dd($response);
         // // end notification
         session() -> flash('success', trans('Notification Sent successfully'));
         return redirect() -> route('Notification.create');



    } // end of send
    public function markAsRead(){
        Auth::user()->unreadNotifications->markAsRead();

        return redirect()->back();
     }

} // end of controller
