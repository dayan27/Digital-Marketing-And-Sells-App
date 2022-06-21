<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'order_id'=>$this->id,
            'first_name'=>$this->user->first_name,
            'last_name'=>$this->user->last_name,
            'order_date'=>$this->created_at,
            'shop_name'=>$this->shop->shop_name,
            'order_status'=>$this->order_status->status_name,
            

        ];
    }
}
