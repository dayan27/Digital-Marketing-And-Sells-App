<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopProductListResource extends JsonResource
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
            'id'=>$this->id,
            'name'=>$this->name,
            'model'=>$this->model,
            'brand'=>$this->brand,
           // 'warranty'=>$this->warranty,
            //'function'=>$this->function,
            //'application'=>$this->application,
          //  'material'=>$this->material,
            'is_active'=>$this->is_active,
           // 'effciency'=>$this->effciency,
           // 'maxiumum_supply_voltage'=>$this->maxiumum_supply_voltage,
            //'maxiumum_current_power'=>$this->maxiumum_current_power,
            'price'=>$this->price,
             'qty'=>$this->pivot->qty,
            
            'weight'=>$this->weight,
            //'description'=>$this->description,
            'images'=> new ImageListResource($this->images()->inRandomOrder()->first()) ?? null,
            'category'=> new CategoryResource($this->category) ?? null


        ];
    }
}
