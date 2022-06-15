<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrginalProductResource;
use App\Http\Resources\ProductTranslationResource;
use App\Models\Product;
use App\Models\ProductTranslation;
use Illuminate\Http\Request;

class ProductTranslationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $article_data = [
            '$request->language' => [
                'name'=>$request->name,
                'warranty'=>$request->warranty,
                'function'=>$request->function,
                'application'=>$request->application,
                'description'=>$request->warranty,
            ],
         ];
     
         // Now just pass this array to regular Eloquent function and Voila!    
       return  Product::create($article_data);
        // $product=Product::find($product_id);
        // $product->translate('am')->description=$request->description;
        // $product->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductTranslation  $productTranslation
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$product_id)
    {
        $product=Product::find($product_id);
         $product->translate($request->language);
       // return $productTrans;
       return new OrginalProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductTranslation  $productTranslation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $product= Product::find($id);
        $product_data = [
            $request->language => [
                'name'=>$request->name,
                'warranty'=>$request->warranty,
                'function'=>$request->function,
                'application'=>$request->application,
                'description'=>$request->description,
            ],
         ];
     
         $product=$product->update($product_data);
         if($product){
            return response()->json('sucessfully added translation',200); 

         }
         else{
            return response()->json('something want wrong',500); 

         }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductTranslation  $productTranslation
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductTranslation $productTranslation)
    {
        //
    }
}
