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

         foreach ($user->orders as $order) {
           
            $addess=$order->address;
            if ($addess) {
                $add[]=$addess;

            }
         }

         return $add;

    }
}
