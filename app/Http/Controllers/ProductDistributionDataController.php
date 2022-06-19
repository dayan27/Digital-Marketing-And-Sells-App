<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductDistributionResource;
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
        return ProductDistributionData::all();
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
         
         $shopProduct= new ProductDistributionData();

         $shopProduct->product_id=$product['product_id'];
         $shopProduct->shop_id=$shop_id;
         $shopProduct->qty=$product['qty'];;
         $shopProduct->provided_date=$product['provided_date'];
        //  $shopProduct->qty=$product->qty;
         $shopProduct->save();
         $productDistributionData['shope_id']=$shop_id;
         $productDistributionData[]=$product;
       }
       return $productDistributionData;

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
         $productDistributionData=[];
         return ProductDistributionResource::collection($datas);
                            
         foreach($datas as $data){
            $productDistributionData[]= $data->product;
         }    
         
         return $productDistributionData;
                                                
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
        //
    }

  
}
