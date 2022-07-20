<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DetailProductTranslationResource extends JsonResource
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
            //'id'=>$this->product_id,
            'name'=>$this->name??null,
            'model'=>$this->product->model,
            'id'=>$this->product->id,
            'brand'=>$this->product->brand,
            'weight'=>$this->product->weight,
            'discount'=>$this->product->discount,
            'maximum_supply_voltage'=>$this->product->maximum_supply_voltage,
            'maximum_current_power'=>$this->product->maximum_current_power,
            'price'=>$this->product->price,
            'qty'=>$this->product->qty,
             'warranty'=>$this->warranty,
            'description'=>$this->description,
             'detail'=>$this->detail,
             'category_id'=>$this->product->category_id,
             'five_star'=>$this->product->reviews()->where('rate',5)->count(),
            'four_star'=>$this->product->reviews()->where('rate',4)->count(),
            'three_star'=>$this->product->reviews()->where('rate',3)->count(),
             'two_star'=>$this->product->reviews()->where('rate',2)->count(),
             'one_star'=>$this->product->reviews()->where('rate',1)->count(),
             'reviews'=>ReviewResource::collection($this->product->reviews),
            'images'=>ImageResource::collection($this->product->images) ?? null,


        ];
    }
}
