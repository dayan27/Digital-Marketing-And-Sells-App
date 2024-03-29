<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopProductListSearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->product->id,
            'name'=>$this->name,
            'model'=>$this->product->model,
            'brand'=>$this->product->brand,
            'price'=>$this->product->price,
             'qty'=>$this->product->shops()->wherePivot('shop_id',request()->user()->shop->id)->first()->pivot->qty,
             'is_featured'=>$this->product->is_featured,
            
            'weight'=>$this->product->weight,
            //'description'=>$this->description,
            'category'=> new CategoryResource($this->product->category) ?? null, 
            'images'=> new ImageListResource($this->product->images()->inRandomOrder()->first()) ?? null,
            //'category'=> new CategoryResource($this->category) ?? null


        ];
}
}