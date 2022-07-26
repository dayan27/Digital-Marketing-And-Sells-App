<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getData(){
        $total_customer=User::count();
        $total_order=Order::count();
        $total_product=Product::count();
      
     return    [
            'total_customer'=>$total_customer,
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
        $grouped=  DB::table('order_items')
        ->where('order_items.created_at', '>=', $duration)
        ->join('products','order_items.product_id','=','products.id')
        ->join('product_translations','products.id','=','product_translations.product_id')
        ->where('product_translations.locale', 'en')
   
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

        $duration= \Carbon\Carbon::now()->subWeeks(1);
        $time_format='l';
        if (request('time') == 'week') {
           
            $duration= \Carbon\Carbon::now()->subWeeks(1);
            $time_format='l';

        }else if (request('time') == 'month') {

            $duration= \Carbon\Carbon::now()->subMonths(1);
            $time_format='l';


        }else if (request('time') == 'year') {

            $duration= \Carbon\Carbon::now()->subYears(1);
            $time_format='F';

        }
        $days = Order::where('created_at', '>=', $duration)
        ->orderBy('created_at')
        ->get()
        ->groupBy(function ($val) use($time_format){
            return Carbon::parse($val->created_at)->format($time_format);
        });

        foreach ($days as $key => $value) {
            $all[$key]= $value->count();
        }

        return $all;
     }
}
