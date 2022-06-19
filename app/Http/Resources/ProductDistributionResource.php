<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductDistributionResource extends JsonResource
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
            'product_id'=>$this->product->id,
            'qty'=>$this->qty,
            'status'=>$this->status,
            'name'=>$this->product->name,
            'model'=>$this->product->model,

         
        ];
    }
}
