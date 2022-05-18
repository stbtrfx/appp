<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Currency;

class CurrencyController extends Controller
{

    public function index()
    {
        $Currencies = Currency::paginate(10);
        return view('admin.currency.index', compact('Currencies'));
    }

    public function create()
    {
        return view('admin.currency.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'code' => 'required|string',

        ]);



        currency::create($data);
        session()->flash('success', trans('added successfully'));
        return redirect()->route('currency.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $currency = Currency::find($id);
        return view('admin.currency.edit', compact('currency'));

    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'code' => 'required|string',

        ]);

        $currency = Currency::find($id);



        $currency->update($data);
        session()->flash('success', trans('Updated Successfully'));
        return redirect()->route('currency.index');
    }

    public function destroy($id)
    {
        Currency::where('id', $id)->delete();
        session()->flash('success', trans('deleted successfully'));
        return redirect()->route('currency.index');
    }

}
