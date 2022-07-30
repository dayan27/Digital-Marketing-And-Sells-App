<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopTranslationResource extends JsonResource
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
          'kebele'=>$this->kebele,
          'latitude'=>$this->latitude,
            'longitude'=>$this->longitude,
            'is_active'=>$this->is_active,         
            'manager_id'=>$this->manager_id,
             'shop_name'=>$this->translate($lang=request()->lang ?request()->lang: 'en')->shop_name ,
            'region'=>$this->translate($lang=request()->lang ?request()->lang: 'en')->region,
            'zone'=>$this->translate($lang=request()->lang ?request()->lang: 'en')->zone ?? null,
            'woreda'=>$this->translate($lang=request()->lang ?request()->lang: 'en')->woreda ?? null,
            'city'=>$this->translate($lang=request()->lang ?request()->lang: 'en')->city ?? null,
            'name'=>$this->manager->first_name ." ". $this->manager->last_name  ,
           'phone_numbers'=>$this->manager->phone_numbers,
            //phone_numbers
            //'name'
            //'images'=>ImageResource::collection($this->images) ?? null,
          // 'translation'=>new ProductTranslationResource( $this->products)
        ];    }
}
