<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'warranty'=>$this->warranty,
            'function'=>$this->function,
            'application'=>$this->application,
            'material'=>$this->material,
            'effciency'=>$this->effciency,
            'maximum_supply_voltage'=>$this->maximum_supply_voltage,
            'maximum_current_power'=>$this->maximum_current_power,
            'price'=>$this->price,
            'qty'=>$this->qty,
            'description'=>$this->description,
            'category_id'=>$this->category_id,
            'images'=>ImageResource::collection($this->images) ?? null


        ];
    }
}
