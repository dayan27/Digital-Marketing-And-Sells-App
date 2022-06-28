<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
       
        return[
            'id'=>$this->id,
            'name'=>$this->name,
            'model'=>$this->model,
            'brand'=>$this->brand,
            'price'=>$this->price,
             'qty'=>$this->qty,
             'is_featured'=>$this->is_featured,
            'is_active'=>$this->is_active,
            'weight'=>$this->weight,
            //'description'=>$this->description,
            'images'=> new ImageListResource($this->images()->inRandomOrder()->first()) ?? null,
            'category'=> new CategoryResource($this->category) ?? null


        ];
    }
}
