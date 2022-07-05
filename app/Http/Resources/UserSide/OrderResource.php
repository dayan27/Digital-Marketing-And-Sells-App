<?php

namespace App\Http\Resources\UserSide;

use App\Models\OrderItem;
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
            'id'=>$this->id,
            'order_ref'=>$this->pin,
            'phone_number'=>$this->user->phone_number,
            'order_date'=>$this->created_at,
            'pickup_date'=>$this->pickup_date,
            'total_price'=>$this->total_price,
            'order_status'=>$this->order_status->status_name,
            'payment_method'=>$this->payment_type->payment_name,
            'order_items'=>OrderItemsResource::collection(OrderItem::where('order_id',$this->id)->get()),


        ];
    }
}
