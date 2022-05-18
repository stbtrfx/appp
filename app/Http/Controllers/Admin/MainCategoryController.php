<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\MainCategories;
use App\Traits\imagesTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class MainCategoryController extends Controller
{
    use imagesTrait;

    public function index()
    {
        $categories = MainCategories ::paginate(10);
        return view('admin.maincategories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.maincategories.create');
    }

    public function store(Request $request)
    {
        $data = $request -> validate([
            'name_en' => 'required|string',
            'name_ar' => 'required|string',
            'image' => 'required',


        ]);
        if ($request -> has('image')) {
            $image = $this -> saveImages($request -> image, 'images/mainCategories');
            $data['image'] = $image;
        }
        $data['status'] = $request -> status;

        MainCategories ::create($data);
        session() -> flash('success', trans('added successfully'));
        return redirect() -> route('maincategories.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $category = MainCategories ::find($id);
        return view('admin.maincategories.edit', compact('category'));

    }

    public function update(Request $request, $id)
    {
        $data = $request -> validate([
            'name_en' => 'required|string',
            'name_ar' => 'required|string',
            // 'image' => 'required',
        ]);

        $category = MainCategories ::find($id);

        DB::beginTransaction();

        if ($request -> has('image')) {
            if($category -> image != 'default.png'){
                Storage ::disk('public_uploads') -> delete('mainCategories/' . $category -> image);
            }
            $image = $this -> saveImages($request -> image, 'images/mainCategories');
            $data['image'] = $image;
        }
        $data['status'] = $request -> status;

        $category -> update($data);

        DB::commit();

        session() -> flash('success', trans('Updated Successfully'));
        return redirect() -> route('maincategories.index');
    }

    public function destroy($id)
    {
        $category = MainCategories ::find($id);

        DB::beginTransaction();

        if($category -> image != 'default.png'){
            Storage ::disk('public_uploads') -> delete('mainCategories/' . $category -> image);
        }
        $category ->  delete();

        DB::commit();

        session() -> flash('success', trans('deleted successfully'));
        return redirect() -> route('maincategories.index');
    }

}
