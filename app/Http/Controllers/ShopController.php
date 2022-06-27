<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShopPaginatedResource;
use App\Http\Resources\ShopProductResource;
use App\Models\Account;
use App\Models\Manager;
use App\Models\PhoneNumber;
use App\Models\shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return Shop::all()->load('manager');
        $per_page=request()->per_page;
        $query= Shop::query();
      
          $query->when(request('filter'),function($query){

            if (request('filter') == 'active') {
               $query= $query->where('is_active', '=', 1);
 
            }elseif (request('filter') == 'inactive') {
                $query= $query->where('is_active', '=', 0);
            }  
            elseif(request('filter') == 'all'){
                $query= Shop::query();
            }   
         });
        return  ShopPaginatedResource::collection($query->paginate($per_page));
    }

    /**
     *store all together the manager,phone number
     shop and its shop attribute translation
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $account=Account::create(['user_name'=>$request->email,'password'=>Hash::make($request->last_name.'1234') ]);
        $data=$request->all();
        $data['account_id']=$account->id;
        $manager= Manager::create($data);
        if($manager){
         $phone_numbers=[];
         $phoneNumbers=$request->phone_numbers;
         //return $phoneNumbers;
         foreach($phoneNumbers as $phoneNumber ){
            $phoneNum=new PhoneNumber();
            $phoneNum->phone_number=$phoneNumber;
            $phoneNum->manager_id=$manager->id;
 
            $phoneNum->save();
            $phone_number['id']=$phoneNum->id;
            $phone_number['phone_number']=$phoneNum->phone_number;
            $phone_numbers[]=$phone_number;
 
         }
        $manager['phone_numbers']=$phone_numbers;
        
        }
        $data=[];
        $data=$request->all();
        $data['manager_id']=$manager->id;
        $shop=shop::create($data);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function show(shop $shop)
    {
        return $shop->load('manager');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, shop $shop)
    {
    
       return $shop->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function destroy(shop $shop)
    {
        $products=$shop->products;
        if($products->isEmpty()){
            $shop->manager->delete();
            $shop->delete();
            
        }
        else{
            return response()->json('you can not delet a shop with histroy',400); 

        }
        return response()->json('sucessfully deleted',200); 

    }
    /**
     * Add product to specific branch by headquarter manager
     * 
     */
    public function addProducts(Request $request,$shop_id){
       // return $request;
       $products=[];
        $shop=Shop::find($shop_id);
        foreach($request->products as $product){
            //return $product['qty'];
            // return $product['id'];
            $shop->products()->attach($product['id'],['qty'=>$product['qty']]);
        
            $products[]['product']= $shop->products;
          
        }
        return $products;

    }
    /**
     * get all product of a specific shop
     * 
     */

    // public function getShopProducts($shop_id){
    //     $shop=Shop::find($shop_id); 
    // //    return $shop->load('products','manager');
    // return new ShopProductResource( $shop->load('products','manager'));

    // }
}
