<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopSearchResource extends JsonResource
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
           // 'id'=>$this->product->id,
            'name'=>$this->shop_name,
            'region'=>$this->region,
            'zone'=>$this->zone,
            'woreda'=>$this->woreda,
            'first_name'=>$this->shop->manager->first_name,
             'last_name'=>$this->shop->manager->last_name,
            'shop_status'=>$this->shop->is_active,

            // 'kebele'=>$this->kebele,
            // 'latitude'=>$this->latitude,
            // "longitude": "11",
            // "is_active": 1,
            // "manager_id": 4,
            // "created_at": "2022-06-24T20:29:38.000000Z",
            // "updated_at": "2022-06-24T20:29:38.000000Z",
            // "shop_name": "Bahir Dar Rensys solar shop",
            // "region": "Amhara",
            // "zone": "Bahir Dar",
          //  "woreda": "Bahir Dar",
            "city"=>$this->city,

        ];
    }
}
