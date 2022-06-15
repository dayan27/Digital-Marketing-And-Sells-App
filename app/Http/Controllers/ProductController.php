<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductListResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\ReusedModule\ImageUpload;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query= Product::query();
      
          $query ->when(request('filter'),function($query){

            if (request('filter') == 'outstock') {
               $query= $query->where('qty', '=', 0);
 
            }elseif (request('filter') == 'instock') {
                $query= $query->where('qty', '!=', 0);
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
                $query= Product::query();
            }
            elseif(request('filter') == 'category'){
                $query = $query->whereHas('category', function (Builder $query) {
                    $query->where('categories.id', '=', request('filter'));
                });
            

            }
               
          
         });
        return   ProductListResource::collection($query->paginate());
      
    }

    public function search(){
        $query=ProductTranslation::query();
        $query=$query->when(request('search'),function($query){

            $query->where('name','LIKE','%'.request('search').'%');
                //  ->orWhere('products.model','LIKE','%'.request('search').'%');
            });
            return   ProductListResource::collection($query->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       // return Product::all();
        $data=$request->all();
        $data['date_of_production']=date('Y-m-d',strtotime($request->date_of_production));
        $product= Product::create($data);

         //saving blog images
         //calling image upload method from php class

            $iu=new ImageUpload();
            $upload=$iu->multipleImageUpload($request->images,$product->id);
            $product['images']=$upload;

             if (count($upload) > 0) {
            return response()->json($product,201);
            }else{
            return response()->json('error while uploading..',401);
            }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //return $product->load('images');
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $product->update($request->all());
        return $product;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json('sucessfully deleted',200); 

    }

    
}
