<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RevenueController extends Controller
{
    public function getData(){
        $duration= \Carbon\Carbon::now()->subWeeks(1);

           
            $durationw= \Carbon\Carbon::now()->subWeeks(1);


            $durationm= \Carbon\Carbon::now()->subMonths(1);


            $durationy= \Carbon\Carbon::now()->subYears(1);

        
        $groupedy=  DB::table('orders')
                  ->where('orders.created_at', '>=', $durationy)->sum('total_price');
    
                  $groupedm=  DB::table('orders')
                  ->where('orders.created_at', '>=', $durationm)->sum('total_price');
    
                  $groupedw=  DB::table('orders')
                  ->where('orders.created_at', '>=', $durationw)->sum('total_price');
    
        return    [
            'week'=>$groupedw,
            'month'=>$groupedm,
            'year'=>$groupedy,
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
        $grouped=  DB::table('orders')
        ->where('orders.created_at', '>=', $duration)
        ->join('shops','orders.shop_id','=','shops.id')
        ->join('shop_translations','shops.id','=','shop_translations.shop_id')
        ->where('shop_translations.locale', 'en')
   
                 ->select([DB::raw('count(orders.id) as `order_count`'),DB::raw('sum(orders.total_price) as `total_price`'),DB::raw('shop_translations.shop_name') ])
                 ->groupBy('shops.id')
                 ->groupBy('shop_translations.shop_name')
                 ->orderByDesc('total_price')
                 ->get();
   
                   
               $sum=$grouped->sum('total_price');
               $top1p=0;
               $top2p=0;
               $top1=null;
               $top2=null;
               if($grouped->count() > 0){
                 $top1=$grouped->sortByDesc('total_price')->get(0);
                 $top1p=$top1->total_price;
               }
               if($grouped->count() > 1){
                 $top2=$grouped->sortByDesc('total_price')->get(1);
                 $top2p=$top2->total_price;
                }
               // $top4=$grouped->sortByDesc('product_count')->get(2)->product_count;
               $top3['other']= $sum-$top1p+$top2p;
   
               return ['top1'=>$top1,'top2'=>$top2,'top3'=>$top3];
    
     
     }

     public function getBarGraphData(){

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
        $days = Order::where('created_at', '>=', $duration)
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
