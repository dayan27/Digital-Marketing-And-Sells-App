<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'customer_id'=>$this->id,
            'first_name'=> $this->first_name,
            'last_name'=> $this->last_name,
            'email'=>$this->email,
            'phone_number'=>$this->phone_number,
            'user_region'=>$this->user_region,
            'user_zone'=>$this->user_zone,
            'user_woreda'=>$this->user_woreda,
            'customer_status'=>$this->active,
            'joined_date'=>$this->created_at,

        ];
    }
}
