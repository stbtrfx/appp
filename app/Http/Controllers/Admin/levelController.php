<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Level;

use App\Traits\imagesTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Auth;

class levelController extends Controller
{
    use imagesTrait;

    public function index()
    {

        $levels = Level::orderBy('id','desc')->paginate(10);

        return view('admin.level.index', compact('levels'));
    }

    public function create()
    {
        return view('admin.level.create');
    }

    public function store(Request $request)
    {

        // dd($request);
        $data = $request -> validate([
            'title_en' => 'required|string',
            'title_ar' => 'required|string',
            'price' => 'required|numeric',
        ]);
        $data = $request->except(['_token','add']);
        Level ::create($data);
        session() -> flash('success', trans('added successfully'));
        return redirect() -> route('level.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $level = Level::find($id);

        return view('admin.level.edit', compact('level'));

    }

    public function update(Request $request, $id)
    {
        $data = $request -> validate([
            'title_en' => 'required|string',
            'title_ar' => 'required|string',
            'price' => 'required|numeric',
        ]);
        $level = Level::find($id);
        $data = $request->except(['_token','add']);


        DB::beginTransaction();



        $level -> update($data);

        DB::commit();

        session() -> flash('success', trans('Updated Successfully'));
        return redirect() -> route('level.index');
    }

    // public function destroy($id)
    // {
    //     $level = Level ::find($id);

    //     DB::beginTransaction();
    //     $level ->  delete();

    //     DB::commit();

    //     session() -> flash('success', trans('deleted successfully'));
    //     return redirect() -> route('level.index');
    // }



}
