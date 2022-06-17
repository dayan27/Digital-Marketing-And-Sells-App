<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *resource for formatting product and shop
     *attribute for shop module
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return[
            'shop_name'=>$this->shop_name,
            'region'=>$this->region,
            'city'=>$this->city,
            'manager_first_name'=>$this->manager->first_name,
            'manager_last_name'=>$this->manager->last_name,
            'shop_status'=>$this->is_active,
            'products'=> ProductForShopResource::collection($this->products),


            

        ];
    }
}
