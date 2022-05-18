<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\resturants;
use App\MainCategories;
use App\User;
use App\MainCategoryResturant;
use App\Traits\imagesTrait;
use DB;
use Auth;

class ResturantController extends Controller
{
    use imagesTrait;
    public function index()
    {
        $resturants = resturants::with('mainCategory')->paginate(10);
        // dd($resturants);
        return view('admin.resturant.index', compact('resturants'));
    }

    public function create()
    {
        
        $MainCategories= MainCategories::all();
        $vendors= User::whereRoleIs('vendor') -> get();
        return view('admin.resturant.create',compact('MainCategories','vendors'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_en' => 'required|string',
            'name_ar' => 'required|string',
            'phone1' => 'required|string',
            'image' => 'required',
            'user_id' => 'required',
         
        ]);

        $data= $request->all();
      
    if ($request -> has('image')) {
            $image = $this -> saveImages($request -> image, 'images/resturants');
            $data['image'] = $image;
        }

            DB::beginTransaction();
        $res = resturants::create($data);
            // dd($res);
            if(isset($res)){
                foreach($request->maincategories as $c){
                    // dd($p['itemQuantity']);
                $MainCategoryResturant =  new MainCategoryResturant();
                
                $MainCategoryResturant->resturants_id = $res->id;
                $MainCategoryResturant->main_categories_id = $c;               
                $MainCategoryResturant->save();
               
                }
         
                DB::commit();
            }else{
                DB::rollback();
                session()->flash('success', trans('Error in add resturant'));
                return redirect()->back();
            }
            
    
        

      
        session()->flash('success', trans('added successfully'));
        return redirect()->route('resturants.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $resturant = resturants::with('mainCategory')->find($id);
        // dd($resturant);
        $MainCategories= MainCategories::all();
        $vendors= User::whereRoleIs('vendor') -> get();
        return view('admin.resturant.edit', compact('resturant','MainCategories','vendors'));

    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name_en' => 'required|string',
            'name_ar' => 'required|string',
            'phone1' => 'required|string',
            'user_id' => 'required|string',
        ]);

        $resturant = resturants::find($id);

        if ($request -> has('image')) {
            $image = $this -> saveImages($request -> image, 'images/resturants');
            $data['image'] = $image;
        }


       
            DB::beginTransaction();

           
            try {    
               $resturant->update($data);
        //    dd($order->toArray());
            if(isset($resturant) && $request->maincategories != null){

               MainCategoryResturant::where('resturants_id',$resturant->id)->delete();

                foreach($request->maincategories as $c){
                $MainCategoryResturant =  new MainCategoryResturant();
                
                $MainCategoryResturant->resturants_id = $resturant->id;
                $MainCategoryResturant->main_categories_id = $c;               
                $MainCategoryResturant->save();
               
                }
         
            
            }
            DB::commit();
        }catch(\Exception $e) {
            DB::rollback();
            session()->flash('success', trans('Error in update'));
            return redirect()->back();
        }
        

      
        session()->flash('success', trans('updated successfully'));
        if(Auth::user()->hasRole(['admin','moderator'])){
        return redirect()->route('resturants.index');
        }else{
            return redirect()->route('home');
        }
    }
    

    public function destroy($id)
    {
        try {    
        DB::beginTransaction();
        resturants::where('id', $id)->delete();
        MainCategoryResturant::where('resturants_id',$id)->delete();
        DB::commit();
    }catch(\Exception $e) {
        DB::rollback();
        session()->flash('success', trans('Error in update'));
        return redirect()->back();
    }
        session()->flash('success', trans('deleted successfully'));
        return redirect()->route('resturants.index');
    }



    public function getVendorResturant(){
        $vendor = Auth::user();
        $resturant= resturants::where('user_id',$vendor->id)->first();
        // dd($resturant);
        $MainCategories= MainCategories::all();

        return view('admin.resturant.vendorEdit', compact('resturant','MainCategories'));


    }

}
