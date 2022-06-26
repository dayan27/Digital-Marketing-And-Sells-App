<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerOrderResource extends JsonResource
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
            'pin'=>$this->pin,
            'pickup_date'=>$this->pickup_date,
            'total_price'=>$this->total_price,
            'phone_number'=>$this->user->phone_number,
            'payment_method'=>$this->payment_type->payment_name,

        ];
    }
}
