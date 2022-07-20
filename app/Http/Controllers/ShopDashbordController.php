<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopDashbordController extends Controller
{

    public function getData(){

        $user=request()->user();


        $total_sales=Order::where('shop_id',$user->shop->id)->sum('total_price');
        $total_order=Order::where('shop_id',$user->shop->id)->count();
        $total_product= $user->shop->products()->count();
      
     return    [
            'total_sales'=>$total_sales,
            'total_order'=>$total_order,
            'total_product'=>$total_product,
        ];

  
     }

     public function getPichartData(){

        $duration= \Carbon\Carbon::now()->subWeeks(1);

        if (request('time') == 'week') {
           
            $duration= \Carbon\Carbon::now()->subWeeks(1);

        }else if (request('time') == 'month') {

            $duration= \Carbon\Carbon::now()->subMonths(1);

        }else if (request('time') == 'year') {

            $duration= \Carbon\Carbon::now()->subYears(1);

        }
        $user=request()->user();

        $grouped=  DB::table('order_items')
        ->where('order_items.created_at', '>=', $duration)
        ->join('products','order_items.product_id','=','products.id')
        ->join('product_shop','product_shop.product_id','=','products.id')
        ->join('shops','shops.id','=','product_shop.shop_id')
        ->join('product_translations','products.id','=','product_translations.product_id')
        ->where('product_translations.locale', 'en')
        ->where('shops.id',$user->shop->id)
   
                 ->select([DB::raw('count(products.id) as `product_count`'),DB::raw('product_translations.name') ])
                 ->groupBy('products.id')
                 ->groupBy('product_translations.name')
                 ->orderByDesc('product_count')
                 ->get();
   
   
                 $sum=$grouped->sum('product_count');
               $top1=$grouped->sortByDesc('product_count')->get(0);
               $top2=$grouped->sortByDesc('product_count')->get(1);
               // $top4=$grouped->sortByDesc('product_count')->get(2)->product_count;
               $top3['other']=$sum-($top1->product_count+$top2->product_count);
   
               return ['top1'=>$top1,'top2'=>$top2,'top3'=>$top3];
            //    return ['top1'=>$top1,'top2'=>$top2,'top3'=>$top3,'total'=>$sum];
   
     }

     public function getBarGraphData(){

        $all=[];
        $duration= \Carbon\Carbon::now()->subWeeks(1);
        $time_format='l';
        if (request('time') == 'week') {
           
            $duration= \Carbon\Carbon::now()->subWeeks(1);
            $time_format='l';

        }else if (request('time') == 'month') {

            $duration= \Carbon\Carbon::now()->subMonths(1);
            $time_format='M';


        }else if (request('time') == 'year') {

            $duration= \Carbon\Carbon::now()->subYears(1);
            $time_format='Y';

        }

        $user=request()->user();

        $days = Order::where('shop_id',$user->shop->id)->where('created_at', '>=', $duration)
        ->orderBy('created_at')
        ->get()
        ->groupBy(function ($val) use($time_format){
            return Carbon::parse($val->created_at)->format($time_format);
        });

        foreach ($days as $key => $value) {
            
            $all[$key]= $value->sum('total_price');
        }

        return $all;
     }
}


