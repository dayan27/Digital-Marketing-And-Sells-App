<?php

namespace App\Http\Controllers;

use App\Http\Resources\AllProductResource;
use App\Http\Resources\DetailProductResource;
use App\Http\Resources\DetailProductTranslationResource;
use App\Http\Resources\FeaturedProductResource;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\ProductListSearchResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\ReusedModule\ImageUpload;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
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
        $per_page=request()->per_page;
        $query= Product::query();
      
          $query->when(request('filter'),function($query){

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
        
         })->when(request('category'),function($query){
            $query = $query->whereHas('category', function (EloquentBuilder $query) {
                $query->where('categories.id', '=', request('category'));
            });
         }) ;
        return   ProductListResource::collection($query->paginate($per_page));
      
    }

    public function search(){
        $per_page=request()->per_page;

        $query=ProductTranslation::query();
        $query=$query->when(request('search'),function($query){

            $query->where('name','LIKE','%'.request('search').'%');
                //  ->orWhere('products.model','LIKE','%'.request('search').'%');
            });
            return   ProductListSearchResource::collection($query->paginate($per_page));
    }

    public function searchFromUser($id){
        $per_page=request()->per_page;

        $query=ProductTranslation::query();
        $query=$query->when(request('search'),function($query){

            $query->where('name','LIKE','%'.request('search').'%');
                //  ->orWhere('products.model','LIKE','%'.request('search').'%');
            })->whereHas('product', function (EloquentBuilder $query) use($id) {
                $query->where('products.category_id', '=', $id);
            });
            return   ProductListSearchResource::collection($query->get());
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
        $data['weight']=(double)$request->weight;
       // $data['date_of_production']=date('M-m-d',strtotime($request->date_of_production));
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
        $data=$request->all();
        $data['weight']=(double)$request->weight;
       // $data['date_of_production']=date('M-m-d',strtotime($request->date_of_production));
       // $product= Product::create($data);

        $product->update($data);
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
        $images=$product->images;
        foreach($images as $image){
            $image->delete();
            
        }
        $product->delete();
        return response()->json('sucessfully deleted',200); 

    }
    /**
     * add featured products
     * @return \Illuminate\Http\Response
     * @param  \App\Models\Product  $product

     */
    public function addFeaturedProduct(Request $request,$product_id){
      $product=Product::find($product_id);  
      $product->is_featured=$request->is_featured;
      $success=$product->save();
      if($success){
        return response()->json('successful operation!',201);
      }else{
        return response()->json('error while adding featured product ',401);
      }

      }
      /**
       * display all featured products
       */
      public function getFeaturedProducts(){
       //$product= Product::where('is_featured','1')->with('reviews')->get();
       $prodTrans=Product::where('is_featured','1')->get();

       //return $prodTrans->translate(request('lang'));
        return FeaturedProductResource::collection($prodTrans);
      }
      /**
       * display a detail of certian product translation
       */

      public function getFeaturedProductDetail($id){
        // $product=Product::find($product_id)->load('reviews');
        // return new DetailProductResource($product);
        $prod=Product::find($id);
        $productTrans=$prod->translate(request('lang'));
     //return  $productTrans=ProductTranslation::where('product_id',)->get();
        return new DetailProductTranslationResource($productTrans);
        

      }
      
       /**
       * display all  products of certian category
       */
      public function getProducts($category_id){
        $per_page=request()->per_page;
         $product= Product::where('category_id',$category_id)->with('reviews');
         return AllProductResource::collection($product->paginate($per_page));
       }
       /**
        * to  make a product featured or remove 
        */

       public function setFeaturedProduct($product_id){
       $product= Product::find($product_id);
       $product->is_featured=request()->is_featured;
       $product->save();
       return $product->is_featured;
       }

       /**
        * make a product active or inactive(when a production is stopped or selling)
        */

        public function setActive($product_id){
            $product= Product::find($product_id);
            $product->is_active=request()->is_active;
            $product->save();
            return $product->is_active;

        }

    //     public function productFilter(){
            
    //     // $shop=request()->user()->shop;
    //     // // return $shop->products;
    //       $query= Product::all();
        
    //       $query ->when(request('filter'),function($query){
  
    //         if (request('filter') == 'outstock') {
    //            $query= $query->where('qty', '=', 0);
  
    //         }elseif (request('filter') == 'instock') {
    //             $query= $query->where('qty', '!=', 0);
    //         }
    //         elseif (request('filter') == 'active') {
    //             $query= $query->where('is_active', '=', 1);
    //         }
    //         elseif (request('filter') == 'inactive') {
    //             $query= $query->where('is_active', '=', 0);
    //         }
    //         // elseif (request('filter') == 'pending') {
    //         //     $query= $query->where('is_active', '=', 0);
    //         // }
            
    //         elseif(request('filter') == 'all'){
    //             $query= $query;
    //         }
    //         elseif(request('filter') == 'category'){
    //             $query = $query->whereHas('category', function (Builder $query) {
    //                 $query->where('categories.id', '=', request('filter'));
    //             });
            
  
    //         }
               
          
    //      });
    //      return $query;

    //   //  return   ProductListResource::collection($query->paginate());

    //     }

       

    }

    

