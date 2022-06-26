<?php

namespace App\Http\Resources;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
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
           'first_name'=>$this->user->first_name,
            'last_name'=>$this->user->last_name,
            'phone_number'=>$this->user->phone_number,
            'region'=>$this->user->user_region,
            'zone'=>$this->user->user_zone,
            'woreda'=>$this->user->user_woreda,
            'total_price'=>$this->total_price,
            'shop'=>new ShopResource($this->shop),
            'payment_type'=>$this->payment_type->payment_name,

            'order_items'=>OrderItemsResource::collection(OrderItem::where('order_id',$this->id)->get()) ?? null,

            //'order_date'=>$this->created_at,
            //'shop_name'=>$this->shop->shop_name,
            //'order_status'=>$this->order_status->status_name,
            

        ];
    }
}
