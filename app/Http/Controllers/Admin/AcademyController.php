<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Academy;
use App\Level;

use App\Traits\imagesTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Auth;

class AcademyController extends Controller
{
    use imagesTrait;

    public function index()
    {

        $academies = Academy::orderBy('id','desc')->paginate(10);

        return view('admin.academy.index', compact('academies'));
    }

    public function create()
    {
        $levels = Level::all();
        return view('admin.academy.create',compact('levels'));
    }

    public function store(Request $request)
    {
        // dd($request);
        $data = $request -> validate([
            'title_en' => 'required|string',
            'title_ar' => 'string',
            'des_en' => 'required|string',
            'des_ar' => 'string',
            'price' => 'required|numeric',
            'price_coins' => 'required|numeric',
            // 'level' => 'required|numeric',
            'category' => 'required',
            'image' => 'required',
            'link' => 'required',
        ]);
        $data = $request->except(['_token','add','image']);
        if ($request -> has('image')) {
            $image = $this -> saveImages($request -> image, 'images/academy');
            $data['image'] = $image;
        }
        Academy ::create($data);
        session() -> flash('success', trans('added successfully'));
        return redirect() -> route('academy.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $academy = Academy::find($id);
        $levels = Level::all();

        return view('admin.academy.edit', compact('levels','academy'));

    }

    public function update(Request $request, $id)
    {
        $data = $request -> validate([
            'title_en' => 'required|string',
            'title_ar' => 'string',
            'des_en' => 'required|string',
            'des_ar' => 'string',
            'price' => 'required|numeric',
            'price_coins' => 'required|numeric',
            // 'level' => 'required|numeric',
            'category' => 'required',
            'link' => 'required',

        ]);

        $academy = Academy::find($id);
        $data = $request->except(['_token','add','image']);


        DB::beginTransaction();

        if ($request -> has('image')) {
            if($academy -> image != 'default.png'){
                Storage ::disk('public_uploads') -> delete('academy/' . $academy -> image);
            }
            $image = $this -> saveImages($request -> image, 'images/academy');
            $data['image'] = $image;
        }

        $academy -> update($data);

        DB::commit();

        session() -> flash('success', trans('Updated Successfully'));
        return redirect() -> route('academy.index');
    }

    public function destroy($id)
    {
        $academy = Academy ::find($id);

        DB::beginTransaction();
        $academy ->  delete();

        DB::commit();

        session() -> flash('success', trans('deleted successfully'));
        return redirect() -> route('academy.index');
    }



}
