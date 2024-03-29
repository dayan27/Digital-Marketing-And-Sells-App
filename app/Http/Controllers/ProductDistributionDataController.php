<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductDistributionResource;
use App\Http\Resources\ProductDistributionShowResource;
use App\Models\Product;
use App\Models\ProductDistributionData;
use App\Models\Shop;
use Illuminate\Http\Request;

class ProductDistributionDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ProductDistributionData::where('status','!=','pending')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

       // return $request;
       //return  ProductDistributionData::create($request->all());
       $shop_id=$request->shop_id;
       $products=$request->products;
       $productDistributionData=[];
       foreach($products as $product){
         
        $p=Product::find($product['product_id']); 
         $pendeng_pro=ProductDistributionData::
                        where('product_id',$product['product_id'])
                        ->where('status','pending')
                        ->sum('qty');
                       // return $product['qty'];
         if ( ($p->qty - $pendeng_pro)  < $product['qty'] ) {
            return response()->json('greater than possible minimun quntity',201);
         }
         $shopProduct= new ProductDistributionData();

         $shopProduct->product_id=$product['product_id'];
         $shopProduct->shop_id=$shop_id;
         $shopProduct->qty=$product['qty'];
         $shopProduct->provided_date='2014-10-10';
        //  $shopProduct->qty=$product->qty;
         $shopProduct->save();
        // return $shopProduct;
         //$productDistributionData['shope_id']=$shop_id;
         $productDistributionData[]=$shopProduct;
       }
       return ProductDistributionResource::Collection($productDistributionData);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductDistributionData  $productDistributionData
     * @return \Illuminate\Http\Response
     */
    public function show($shop_id)
    {
        $datas= ProductDistributionData::where('shop_id',$shop_id)
                                        ->where('status','pending')->get();
         return ProductDistributionShowResource::collection($datas);
                            
        //  foreach($datas as $data){
        //     $productDistributionData[]= $data->product;
        //  }    
         
        //  return $productDistributionData;
                                                
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductDistributionData  $productDistributionData
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductDistributionData $productDistributionData)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductDistributionData  $productDistributionData
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductDistributionData $productDistributionData)
    {
        $productDistributionData->delete();
    }

  
}
