<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrginalProductResource;
use App\Http\Resources\OrginalShopTranslationResource;
use App\Models\Shop;
use App\Models\ShopTranslation;
use Illuminate\Http\Request;

class ShopTranslationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  shop_id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $shop_id)
    {
        $shop=Shop::find($shop_id);
        $shop->translate($request->language);
      // return $productTrans;
      return new OrginalShopTranslationResource($shop);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopTranslation  $shopTranslation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $shop= Shop::find($id);
        $shop_data = [
            $request->language => [
                'shop_name'=>$request->shop_name,
                'zone'=>$request->zone,
                'region'=>$request->region,
                'woreda'=>$request->woreda,
                'city'=>$request->city,
                // 'description'=>$request->description,
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
     * @param  \App\Models\ShopTranslation  $shopTranslation
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShopTranslation $shopTranslation)
    {
        //
    }
}
