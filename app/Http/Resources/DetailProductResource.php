<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DetailProductResource extends JsonResource
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
            'weight'=>$this->weight,
            'discount'=>$this->discount,
            'maximum_supply_voltage'=>$this->maximum_supply_voltage,
            'maximum_current_power'=>$this->maximum_current_power,
            'price'=>$this->price,
            'qty'=>$this->qty,
            'warranty'=>$this->warranty,
            'description'=>$this->description,
            'detail'=>$this->detail,
            'category_id'=>$this->category_id,
            'reviews'=>ReviewResource::collection($this->reviews),
            'images'=>ImageResource::collection($this->images) ?? null,


        ];
    }
}
