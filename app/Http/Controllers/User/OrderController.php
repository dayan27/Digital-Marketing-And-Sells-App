<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserSide\OrderResource;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OrderController extends Controller
{
    public function getUserOrders($id){
        $user=User::find($id);
        return  OrderResource::collection($user->orders);
    }


    public function getUserOrderAddress($user_id){

        $user=User::find($user_id);       
        //  return $user->orders->load('address');
         $add=[];
         foreach ($user->orders as $order) {
           
            $addess=$order->address;
            if ($addess) {

                if(in_array($addess, $add)) {
                    // exists
                }  else {
                    $add[]=$addess;
                }                

            }
         }

         return $add;

    }

    public function cancelOrder($order_id){
        $order=Order::find($order_id);
        $perv_stat=OrderStatus::find($order->order_status_id);

        if($perv_stat->status_name == 'pending'){
            $status_id= OrderStatus::where('status_name','canceled')->first()->id;
            $order->order_status_id=$status_id;
            $order->save();  

            return response()->json('successfully canceled',200);
        }else{
            return response()->json('error while canceling',401);

        }
         

         }
        

    
}
