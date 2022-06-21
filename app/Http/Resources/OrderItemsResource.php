<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemsResource extends JsonResource
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
            'name'=>$this->product->name,
            'model'=>$this->product->model,
            'qty'=>$this->product->qty,
            'unit_price'=>$this->unit_price,    
            'image'=>new ImageResource($this->product->images->first()) ?? null,  

        ];
    }
}
