<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Order;
use App\OrderItem;
use App\DeliveryBoy;



class OrdersController extends Controller
{
    //

    public function index()
    {
        //
        
// echo $mytime->toDateTimeString();
        $orders = Order::paginate(10);
        $delivery= DeliveryBoy::where('status','1')->get();

        return view('admin.order.index', compact('orders','delivery'));
    }

    public function getOrderByStatus($status){
        $delivery= DeliveryBoy::where('status','1')->get();

        $orders = Order::where('status',$status)->paginate(10);
        // dd($orders);
        return view('admin.order.index', compact('orders','delivery'));

    }


   
    public function printOrder($id){
        $order = Order::find($id);
        // $product = $order->order_item->product[0];
        // dd($product->order_item->qty);
        // dd($orders->order_item->product[0]->price);
        return view('admin.order.print', compact('order'));

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
       /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $order = Order::find($id);
        return view('admin.order.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = Order::find($id);
       
        return view('admin.order.edit', compact('order'));
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
            
        $order = Order::find($id);
        
        $order['status'] = $request->status;

            $order->save();
        
            session()->flash('success', trans('Updated Successfully'));
            return redirect()->route('order.index');
    }


    public function assignOrder(Request $request, $id)
    {
        
            
        $order = Order::find($id);
        //  dd($order);
        $old_delivery = DeliveryBoy::find($order['delivery_id']);
        //  dd($old_delivery);
         if(isset($old_delivery)){
            $old_delivery->status = '1';
            $old_delivery->save();
         }
        
       
        $order['delivery_id'] = $request->delivery_id;
        
        $delivery= DeliveryBoy::find($request->delivery_id);
        // $delivery->status = '2';

           if( $order->save()){
               $delivery->save();
            session()->flash('success', trans('Updated Successfully'));

           }else{
            session()->flash('Error', trans('Error in Update'));
           }
        
            return redirect()->route('order.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        Order::where('id',$id)->delete();
        session()->flash('success', trans('deleted successfully'));
        return redirect()->route('order.index');
    }



}
