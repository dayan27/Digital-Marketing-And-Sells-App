<?php

namespace App\Http\Controllers;

use App\Http\Resources\SalesResource;
use App\Models\Order;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function getSales(){
      $query=  Order::whereHas('order_status',function($query){
           return $query=$query->where('order_statuses.status_name','completed');
        });

        return SalesResource::collection($query->paginate(request('per_page')??10));
    }
}
