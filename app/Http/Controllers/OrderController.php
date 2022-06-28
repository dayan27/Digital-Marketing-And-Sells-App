<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerOrderResource;
use App\Http\Resources\OrderDetailResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OrderController extends Controller
{
    /**
     * filter and display an order from a certian shop
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $per_page=request()->per_page;
        $query= Order::query()->where('shop_id',request()->user()->shop->id);
        // Order::where('shop_id',request()->user()->shop->id)->get();

          $query->when(request('filter'),function($query){

            if (request('filter') == 'pending') {
               $query= $query->wherehas('order_status', function(  $query ){
                $query->where('order_statuses.status_name','=',request('filter'));
               
            });
 
            }elseif (request('filter') == 'canceled') {
                $query= $query->wherehas('order_status', function(  $query ){
                    $query->where('order_statuses.status_name','=',request('filter'));
            });
        }
        elseif (request('filter') == 'completed') {
            $query= $query->wherehas('order_status', function(  $query ){
                $query->where('order_statuses.status_name','=',request('filter'));
        });
    }         
            elseif(request('filter') == 'all'){
                $query= Order::query();
            }  
      //  return   ProductListResource::collection($query->paginate($per_page));
    });
 return OrderResource::collection($query->paginate($per_page));


}
    /**
     * search order by buyer name and order reference no(pin)
     */
    public function search(){
           $query=Order::query();
        $query->where('pin',request('search'))
                ->orwhere(function($query){
                    $query = $query->whereHas('user', function (Builder $query) {
                        $query = $query->where('users.first_name', '=', request('search'));
                    });
                });
           return  orderResource::collection($query->get());
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
       $user= $request->user();
       $order=new Order();
       $order->pin=rand(1000,9999);
       $order->pickup_date=date('Y-m-d',strtotime($request->pickup_date));
        if ($request->isNew) {
           
            $data=$request->all();
            $otp=rand(1000,9999);
            $data['verification_code']=$otp;
            $data['password']=Hash::make($request->last_name.'1234');
            
            $newuser= User::create($data);
            $order->user_id=$newuser->id;

        }else {
            $order->user_id=$request->user_id;

        }
       $order->order_status_id=OrderStatus::where('status_name','pending')->first()->id;
       $order->payment_type_id=1;
       $order->shop_id=$user->shop->id;
    //   ret Str::random(10)
       
       $orderItems=$request->items;
     //  $totalPrice=0;
    //    foreach($orderItems as $item){
    //     $id=$item['id'];
    //      $item_price=Product::find($id)->price;
    //     //$item_price=$item->prod->price;
    //    $totalPrice=$totalPrice + ($item['price']*$item['qty']);
        
    //   }
       $order->total_price=$request->total_price;
       $order->save();

       foreach($orderItems as $item){

                $orderItem=new OrderItem();
                $orderItem->quantity=$item['qty'];
                $orderItem->order_id=$order->id;
                $orderItem->product_id=$item['id'];
                $orderItem->unit_price=Product::find($item['id'])->price;
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
    /**
     * return detail about order
     */
    public function orderDetail($id){
       $order=Order::find($id);
      return new OrderDetailResource($order);
    //    $order_items=OrderItem::where()
    //    foreach()


    }

    public function orderProduct(Request $request){
        
          // $order=Order::where('user_id',$request->user_id)->where('status','pending')->get();
    //    try {
    //     DB::beginTransaction();
    //     DB::commit();
    //    } catch (\Throwable $th) {
    //     //throw $th;
    //     DB::rollBack();
    //    }
       $user= $request->user();
       $order=new Order();
       $order->pin=rand(1000,9999);
       $order->pickup_date=date('Y-m-d',strtotime($request->pickup_date));
       
            $order->user_id=$request->user_id;

    
       $order->order_status_id=OrderStatus::where('status_name','pending')->first()->id;
       $order->payment_type_id=1;
       $order->shop_id=$request->shop_id;
    //   ret Str::random(10)
       
       $orderItems=$request->products;
       $totalPrice=0;
       foreach($orderItems as $item){
        $id=$item['id'];
         $item_price=Product::find($id)->price;
        //$item_price=$item->prod->price;
       $totalPrice=$totalPrice + (double)$item['price']*(double)$item['qty'];
        
       }
       $order->total_price=$totalPrice;
       $order->save();

       foreach($orderItems as $item){

                $orderItem=new OrderItem();
                $orderItem->quantity=$item['qty'];
                $orderItem->order_id=$order->id;
                $orderItem->product_id=$item['id'];
                $orderItem->unit_price=Product::find($item['id'])->price;
                $orderItem->save();

         }
          
         return response()->json(new CustomerOrderResource($order),201);
            

    }
    /**
     * return all the leatest order resource
     */
    public function allOrders(){
        
        $per_page=request()->per_page;
        $query= Order::query();
      
          $query->when(request('filter'),function($query){

            if (request('filter') == 'pending') {
               $query= $query->wherehas('order_status', function(  $query ){
                $query->where('order_statuses.status_name','=',request('filter'));
               
            });
 
            }elseif (request('filter') == 'canceled') {
                $query= $query->wherehas('order_status', function(  $query ){
                    $query->where('order_statuses.status_name','=',request('filter'));
            });
        }
        elseif (request('filter') == 'completed') {
            $query= $query->wherehas('order_status', function(  $query ){
                $query->where('order_statuses.status_name','=',request('filter'));
        });
      }         
            elseif(request('filter') == 'all'){
                $query= Order::query();
            }  
      //  return   ProductListResource::collection($query->paginate($per_page));
      // return OrderResource::collection(Order::where('shop_id',request()->user()->shop->id)->get());
       });
     return OrderResource::collection($query->paginate($per_page));
    }
    /***
     * change the status of the order
     */
    // public function setOrderStatus($order_id){
    //     $order=Order::find($order_id);
    //     $order->order_status_id=request()->order_status_id;

    // }


    public function changeOrderStatus($order_id){
        $order=Order::find($order_id);
       $status_id= OrderStatus::where('status_name',request()->status)->first()->id;
         $order->order_status_id=$status_id;
         $order->save();
        

    }

}
