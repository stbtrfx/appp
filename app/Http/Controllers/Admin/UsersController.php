<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Level;
use Illuminate\Http\Request;
use App\User;
use App\Models\Role;
use App\Vip;
use Illuminate\Support\Facades\Hash;
use App\Provider;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Carbon;

class UsersController extends Controller
{
    //
    public function index(){
        $roles= Role::all();
        $users = User::orderBy('id','desc')->paginate(20);
        $vip = Vip::all();
        $level = Level::all();
        //  dd($users);
        return view('admin.user.index',compact('users','roles','vip','level'));
    }


    public function create(){
        $roles= Role::all();

        // dd($users);
        return view('admin.user.create',compact('roles'));
    }

    protected function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'balance'=>['numeric']
        ]);
        // dd($data['password']);
        $data['password'] = Hash::make($data['password']);

        if($request->role == 6){
        $data['role']= 'vendor';
        }else if($request->role == 3){
            $data['role']= 'delivery';
        }
        $user =  User::create($data);
        $user->attachRole($request->role); // customer default


        if($request->role == 3 ){ //delivery
            session()->flash('success', trans('validation.User Added Successfully, Please Fill Delivery Information'));
            return redirect()->route('deliveryBoy.create');
        } else if($request->role == 6){//vendor
            session()->flash('success', trans('validation.User Added Successfully, Please Fill Resturant Information'));
            return redirect()->route('resturants.create');
        }else {
            session()->flash('success', trans('validation.added successfully'));
            return redirect()->route('user.index');
        }

    }


    public function edit($id){
        $user = User::findOrFail($id);
        $roles= Role::all();
        return view ('admin.user.edit',compact('user','roles'));
    }

    public function update(Request $request,$id){
// dd($request);
        $data = $request->validate([
          'name' => ['required', 'string', 'max:255'],
          'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$id],
          'phone' => ['required', 'string', 'max:255', 'unique:users,phone,'.$id],
        //   'password' => ['string', 'min:8', 'confirmed'],
        'balance'=>['numeric']

        ]);
        $user = User::find($id);
        $data['password'] = Hash::make($request->password);
        $data = $request->except(['urlPage']);
        
            $user->detachRole($user->roles[0]->id); 
            $user->attachRole($request->role);
        
        $user->update($data);
        session()->flash('success', trans('validation.updated successfully'));

        if(isset($request->urlPage)){
            return redirect()->to($request->urlPage);
        }else{
            return redirect()->route('user.index');
        }


    }


    public function addRole(){
        $role = 4;
        $id = 4;
        $user = User::find($id);
        // $user->attachRole($role);
        $user->roles()->attach([$role]);
        dd($user);
    }


    public function editUserRole( Request $request){
        // $role = 5;
        //  $id = 4;
        // dd($user->roles[0]->name);
        $user = User::find($request->id);
        $user->detachRole($user->roles[0]->id); // parameter can be a Role object, array, id or the role string name
         $user->attachRole($request->role);
        // $user->roles()->attach(['customer']);
        session()->flash('success', trans('Updated successfully'));
        return redirect()->route('user.index');
    }
    public function editUserVip( Request $request){


        $user = User::find($request->id);
        $vip = Vip::find($request->vip);
        if($request->vip){
            $user->vip = 1;
            $user->from =  Carbon::now();
            $user->to =   Carbon::now()->addMonths($vip->months_no);
        }else{
            $user->vip = 0;
            $user->from =  NULL;
            $user->to =   NULL;
        }
// dd($user);
        $user->save();
        session()->flash('success', trans('Updated successfully'));
        return redirect()->route('user.index');
    }

    public function editUserLevel(Request $request){
        $user = User::find($request->id);

        if($request->level){
            $user->level_id =$request->level ;
        }
        $user->save();
        session()->flash('success', trans('Updated successfully'));
        return redirect()->route('user.index');
    }

    public function destroy( $id){

        $user = User::find($id);
        $user_provider = Provider::where('user_id',$id)->get();
        DB::beginTransaction();
        $user->detachRole($user->roles[0]->name); // parameter can be a Role object, array, id or the role string name
         $user->delete();
         foreach($user_provider as $u){
            $u->delete();
         }

         DB::commit();
        // $user->roles()->attach(['customer']);

        session()->flash('success', trans('deleted successfully'));
        return redirect()->route('user.index');
    }



}
