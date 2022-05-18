<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Vip;

use App\Traits\imagesTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Auth;

class VipController extends Controller
{
    use imagesTrait;

    public function index()
    {

        $vips = Vip::orderBy('id','desc')->paginate(10);

        return view('admin.vip.index', compact('vips'));
    }

    public function create()
    {
        return view('admin.vip.create');
    }

    public function store(Request $request)
    {

        // dd($request);
        $data = $request -> validate([
            'title_en' => 'required|string',
            'title_ar' => 'required|string',
            'des_en' => 'required|string',
            'des_ar' => 'required|string',
            'price' => 'required|numeric',
            'months_no' => 'required|numeric',
        ]);
        $data = $request->except(['_token','add']);
        Vip ::create($data);
        session() -> flash('success', trans('added successfully'));
        return redirect() -> route('vip.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $vip = Vip::find($id);

        return view('admin.vip.edit', compact('vip'));

    }

    public function update(Request $request, $id)
    {
        $data = $request -> validate([
            'title_en' => 'required|string',
            'title_ar' => 'required|string',
            'des_en' => 'required|string',
            'des_ar' => 'required|string',
            'price' => 'required|numeric',
            'months_no' => 'required|numeric',
        ]);
        $vip = Vip::find($id);
        $data = $request->except(['_token','add']);


        DB::beginTransaction();



        $vip -> update($data);

        DB::commit();

        session() -> flash('success', trans('Updated Successfully'));
        return redirect() -> route('vip.index');
    }

    public function destroy($id)
    {
        $vip = Vip ::find($id);

        DB::beginTransaction();
        $vip ->  delete();

        DB::commit();

        session() -> flash('success', trans('deleted successfully'));
        return redirect() -> route('level.index');
    }



}
