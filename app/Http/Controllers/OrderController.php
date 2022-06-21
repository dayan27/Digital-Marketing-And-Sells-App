<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return OrderResource::collection(Order::all());
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
       $order->pickup_date=date('Y-m-d',strtotime($request->pickup_date));
       $order->user_id=$request->user_id;
       $order->order_status_id=OrderStatus::where('status_name','pending')->first()->id;
       $order->payment_type_id=$request->payment_type_id;
       $order->shop_id=$request->shop_id;

       
       $orderItems=$request->items;
       $totalPrice=0;
       foreach($orderItems as $item){
        $id=$item['product_id'];
         $item_price=Product::find($id)->price;
        //$item_price=$item->prod->price;
       $totalPrice=$totalPrice + ($item_price*$item['quantity']);
        
       }
       $order->total_price=$totalPrice;
       $order->save();

       foreach($orderItems as $item){

                $orderItem=new OrderItem();
                $orderItem->quantity=$item['quantity'];
                $orderItem->order_id=$order->id;
                $orderItem->product_id=$item['product_id'];
                $orderItem->unit_price=Product::find($item['product_id'])->price;
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
