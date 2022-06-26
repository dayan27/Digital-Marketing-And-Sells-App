<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductListResource;
use App\Http\Resources\ShopProductListResource;
use App\Http\Resources\ShopProductResource;
use App\Models\ProductDistributionData;
use App\Models\Shop;
use App\Models\ShopeProduct;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ShopeProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shop=request()->user()->shop;
      // return $shop->products;
        $query= $shop->products();
      
        $query ->when(request('filter'),function($query){

          if (request('filter') == 'outstock') {
             $query= $query->where('product_shop.qty', '=', 0);

          }elseif (request('filter') == 'instock') {
              $query= $query->where('product_shop.qty', '!=', 0);
          }
          elseif (request('filter') == 'active') {
              $query= $query->where('is_active', '=', 1);
          }
          elseif (request('filter') == 'inactive') {
              $query= $query->where('is_active', '=', 0);
          }
          // elseif (request('filter') == 'pending') {
          //     $query= $query->where('is_active', '=', 0);
          // }
          
          elseif(request('filter') == 'all'){
              $query= $query;
          }
          elseif(request('filter') == 'category'){
              $query = $query->whereHas('category', function (Builder $query) {
                  $query->where('categories.id', '=', request('filter'));
              });
          

          }
             
        
       });
      return   ShopProductListResource::collection($query->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *get all product of a specific shop

     * @param  \App\Models\ShopeProduct  $shopeProduct
     * @return \Illuminate\Http\Response
     */
    public function show($shop_id)
    {
        $shop=Shop::find($shop_id); 
        //    return $shop->load('products','manager');
        return new ShopProductResource($shop->load('products','manager'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopeProduct  $shopeProduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShopeProduct $shopeProduct)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShopeProduct  $shopeProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShopeProduct $shopeProduct)
    {
        //
    }
    /**
     * 
     * 
     */

    public function acceptProductRequest(Request $request){
                 $shop=Shop::find($request->shop_id);

                    foreach ($request->products as $product) {

                        $exist=$shop->products()->wherePivot('product_id',$product['product_id'])->first();

                        if($exist){
                            $sqty= $exist->pivot->qty+$product['qty'];

                          $shop->products()->updateExistingPivot($product['product_id'],['qty'=>$sqty]);
                          ProductDistributionData::where('product_id',$product['product_id'])
                                                                  ->where('status','pending')
                                                                ->update(['status'=>'accepted']);
                        }else{
                            $shop->products()->attach($product['product_id'],['qty'=>$product['qty']]);
                            ProductDistributionData::where('product_id',$product['product_id'])
                                                            ->where('status','pending')
                                                        ->update(['status'=>'accepted']);
                    }
                }
                    return response()->json('succsesfully accepted',200);


        // $shop=Shop::find($request->shop_id);
        // if (!($shop->products->isEmpty())) {
    
        // foreach($shop->products as $shop_product){
        
        //     foreach ($request->products as $product) {
        //         if ($product['product_id'] == $shop_product->pivot->product_id) {
        //            // return $product['id'] .'=='. $shop_product->pivot->product_id;
        //             $sqty= $shop_product->pivot->qty+$product['qty'];

        //             $shop->products()->updateExistingPivot($product['product_id'],['qty'=>$sqty]);
        //             ProductDistributionData::where('product_id',$product['product_id'])
        //                                        ->where('status','pending')
        //                                       ->update(['status'=>'accepted']);
        //                                        break;
        //         }else{
        //             //return 'oo';
        //             $shop->products()->attach($product['product_id'],['qty'=>$product['qty']]);
        //             ProductDistributionData::where('product_id',$product['product_id'])
        //             ->update(['status'=>'accepted']);
        //             // break;
        //         }
        //     }
        // }
        // }else{
        //     foreach ($request->products as $product) {
            
        //             $shop->products()->attach($product['product_id'],['qty'=>$product['qty']]);
        //             ProductDistributionData::where('product_id',$product['product_id'])
        //             ->update(['status'=>'accepted']);
                   
                
        //     }
        // }

          
    }
}
