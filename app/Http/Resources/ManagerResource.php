<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ManagerResource extends JsonResource
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
            'first_name'=>$this->first_name,
            'last_name'=>$this->last_name,
            'email'=>$this->email,
            'manager_region'=>$this->manager_region,
            'manager_zone'=>$this->manager_zone,
            'manager_city'=>$this->manager_city,
            'manager_woreda'=>$this->manager_woreda,
            'manager_kebele'=>$this->manager_kebele,
            'house_no'=>$this->house_no,
           // 'phone_numbers'=>$this->phone_numbers,
          // 'phone_numbers'=>PhoneNumberResource::collection($this->phone_numbers)
            'phone_numbers'=>PhoneNumberResource::collection($this->phone_numbers),

           // 'products'=> ProductForShopResource::collection($this->products),


            

        ];
    }
}
