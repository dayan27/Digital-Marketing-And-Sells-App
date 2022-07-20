<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductListResource;
use App\Http\Resources\ShopProductDetailResource;
use App\Http\Resources\ShopProductListResource;
use App\Http\Resources\ShopProductListSearchResource;
use App\Http\Resources\ShopProductResource;
use App\Http\Resources\ShopSearchResource;
use App\Models\Product;
use App\Models\ProductDistributionData;
use App\Models\ProductTranslation;
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
        $per_page=request()->per_page;

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
      return   ShopProductListResource::collection($query->paginate($per_page));
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

    public function shopProductDetail(Request $request, $p_id){
        $shop=$request->user()->shop; 
        $product= $shop->products()->where('products.id',$p_id)->first();
        return new ShopProductDetailResource($product);

        
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopeProduct  $shopeProduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $shop= Shop::find($id);
        $shop_data = [
            $request->language => [
                'shop_name'=>$request->shop_name,
                'region'=>$request->region,
                'zone'=>$request->zone,
                'woreda'=>$request->woreda,
                'city'=>$request->city,
                
            ],
         ];
     
         $shop=$shop->update($shop_data);
         if($shop){
            return response()->json('sucessfully added translation',200); 

         }
         else{
            return response()->json('something want wrong',500); 

         }
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
                   $id= $product['product_id'];
                   $prod=Product::find($id);
                   $prod->qty=$prod->qty - $product['qty'];
                 // return $product->qty=$p;
                   $prod->save();
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

    public function searchProductShop(){
        $per_page=request()->per_page;
    
        // $shop=request()->user()->shop;
        // $products=$shop->products();
        //$query=$products->translate;
        //$query=ProductTranslation::query();
        $query=ProductTranslation::query();

        $query=$query->when(filled('search'),function($query){

            $query->where('name','LIKE','%'.request('search').'%');
            $query = $query->whereHas('product', function (Builder $query) {
                $query = $query->whereHas('shops', function (Builder $query) {
                    $query->where('product_shop.shop_id', '=', request()->user()->shop->id);
                });          
            
            });        
            
            });
            return  ShopProductListSearchResource ::collection($query->paginate($per_page));
    }
}
