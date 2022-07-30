<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserSide\CategoryResource;
use App\Http\Resources\CategoryTranslationResource;
use App\Http\Resources\ProductListResource;
use App\Models\Category;
use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        return CategoryResource::collection(Category::all());
        return Category::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//         $role = Role::create(['name' => 'writer']);
//    return $permission = Permission::create(['name' => 'edit articles']);

         $file=$request->image;
         $data=$request->all();
         $name = Str::random(5).time().'.'.$file->extension();
         $file->move(public_path().'/categoryimages/', $name);
         $data['image_path']=$name;
         $category= Category::create($data);

         
         $category->image_path = asset('/categoryimages').'/'.$name;
       
         return $category;
  

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
      
        return   ProductListResource::collection($category->products);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
       // $product= Product::find($id);
        $category_data = [
            $request->language => [
                'title'=>$request->title,
                'description'=>$request->description,        
            ],
         ];
     
         $category=$category->update($category_data);
          if($category){
            return response()->json('sucessfully added translation',200); 
          }else{
            return response()->json('something want wrong',500); 
           }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
  
        if($category->products->isEmpty()){
        $category->delete();
        }
        else{
            return response()->json('unable to delete',404);
        }
    }

    public function categoryDetail($id)
    {
        $category=Category::find($id);
        $category= $category;
        //$category_trans=$category->translate(request()->language);
        if($category) {
            return new CategoryTranslationResource($category);            
        }else{
           return $category;
          }
    }
    
}
