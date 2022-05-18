<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use App\Category;
use App\resturants;
use Auth;
use App\Traits\imagesTrait;
use BeyondCode\Vouchers\Models\Voucher;

use BeyondCode\Vouchers\Facades\vouchers as Vouchers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class productController extends Controller
{
    use imagesTrait;

    public function index()
    {
        if(Auth::user()->hasRole('vendor')){
            
            $resturant = resturants::where('user_id', Auth::user()->id)->first();
            $categories=Category::where('resturant_id',$resturant->id)->pluck('id');
            $products = Product::whereIn('category_id',$categories)->paginate(10); 
            // $products = Product::where('resturant_id',$resturant->id)->paginate(10);    
        }else
        $products = Product::paginate(10);
       
        return view('admin.product.index', compact('products'));
    }


    public function createCopon()
    {
        // $product = Product ::find($id);
        // return view('admin.copon.create', compact('product'));
        return view('admin.copon.create');
    }

    public function storeCopon(Request $request)
    {
        $data = $request -> validate([
            'discount' => 'required|string|max:100',
            'days' => 'required|string|max:10',
        ]);
        // $product = Product ::find($request -> product_id);
        $product = Product ::all();
        // foreach($products as $product){
    $copons = $product[0] -> createVouchers(1, ['discount' => $data['discount']], today() -> addDays($data['days']));
// 
// }

        session() -> flash('success', trans('added successfully'));
        return redirect() -> route('copons.index');

    }

    public function allCopons()
    {
        $copons = Voucher ::orderBy('id', 'DESC') -> get();
        // dd($copons[3]->product->name_en);
        return view('admin.copon.index', compact('copons'));
    }

    public function destroyCopon($id)
    {
        $copon = Voucher ::where('id', $id) -> delete();
        return redirect() -> route('copons.index');
    }


    public function createvo()
    {
        $videoCourse = Product ::find(3);
        $voucher = $videoCourse -> createVouchers(1, ['discount' => 50], today() -> addDays(7));

        dd($voucher);
    }

    public function create()
    {
        $categories = Category ::all();
        return view('admin.product.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // dd($request->veg);
        $data = $request -> validate([
            'name_en' => 'required|string|max:100',
            'name_ar' => 'required|string|max:100',

            'des_en' => 'string',
            'des_ar' => 'string',
            'image' => 'required',
            'category_id' => 'required',
            'price' => 'required',
            // 'cal' => 'required',
        ]);
        $data = $request->all();
        if ($request -> has('image')) {
            $image = $this -> saveImages($request -> image, 'images/products');
            $data['image'] = $image;
        }
        if($request->status == null){
           $data['status'] = 0; 
        }
         if($request->status == null){
             $data['veg'] = 0;
         }
        
        if($request->promotional == null){
          $data['promotional'] = 0;  
        }
        
        

        Product ::create($data);
        session() -> flash('success', trans('added successfully'));
        return redirect() -> route('product.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $product = Product ::find($id);
        $categories = Category ::all();

        return view('admin.product.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $data = $request -> validate([
            'name_en' => 'required|string|max:100',
            'name_ar' => 'required|string|max:100',

            'des_en' => 'string',
            'des_ar' => 'string',
            // 'image'             => 'required',
            'category_id' => 'required',
            'price' => 'required',
            // 'cal' => 'required',
            // 'veg' =>'required',

        ]);

        $product = Product ::find($id);
$data= $request->all();
        DB::beginTransaction();

        if ($request -> has('image')) {
            if($product -> image != 'default.png'){
                Storage ::disk('public_uploads') -> delete('products/' . $product -> image);
            }
            $image = $this -> saveImages($request -> image, 'images/products');
            $data['image'] = $image;
        }

      if($request->status == null){
           $data['status'] = 0; 
        }
         if($request->status == null){
             $data['veg'] = 0;
         }
        
        if($request->promotional == null){
          $data['promotional'] = 0;  
        }

        $product -> update($data);

        DB::commit();

        session() -> flash('success', trans('Updated Successfully'));
        return redirect() -> route('product.index');
    }

    public function destroy($id)
    {
        $product = Product ::find($id);

        DB::beginTransaction();

        if($product -> image != 'default.png'){
            Storage ::disk('public_uploads') -> delete('products/' . $product -> image);
        }
        $product-> delete();

        DB::commit();

        session() -> flash('success', trans('deleted successfully'));
        return redirect() -> route('product.index');
    }
    public function search(Request $request){
        $products = Product::where('name_en','like',  '%'.$request->name.'%')->orWhere('name_ar','like',  '%'.$request->name.'%')->orWhereHas('category', function($q) use ($request){
            $q->where('name_en','like','%'.$request->name.'%');
         })->paginate(10);
        //  dd($products);
        return view('admin.product.index', compact('products'));
    }
}
