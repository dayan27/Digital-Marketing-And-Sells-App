<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       // $order=Order::where('user_id',$request->user_id)->where('status','pending')->get();
    //    try {
    //     DB::beginTransaction();
    //     DB::commit();
    //    } catch (\Throwable $th) {
    //     //throw $th;
    //     DB::rollBack();
    //    }
       $order=new Order();
       $order->pin=rand(100,1000);
       $order->pickup_date=date('Y-m-d',strtotime($request->expected_time));
       $order->user_id=$request->user_id;
       $order->order_status_id=OrderStatus::where('name','pending')->first()->id;
       $order->payment_type_id=$request->payemnt_type_id;
       
       $orderItems=$request->order_Items;
       $totalPrice=0;
       foreach($orderItems as $item){
        $totalPrice=$totalPrice+($item->unit_price*$item->quantity);
        
       }
       $order->total_price=$totalPrice;
       $order->save();

       foreach($orderItems as $item){

                $orderItem=new OrderItem();
                $orderItem->quantity=$item->quantity;
                $orderItem->order_id=$item->order_id;
                $orderItem->product_id=$item->product_id;
                $orderItem->unit_price=Product::find($request->product_id)->price;
                $orderItem->save();

         }
          
         return response()->json('successfully ordered',201);
            



        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
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
    }
}
