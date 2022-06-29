<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopTranslationAdminResource extends JsonResource
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
            'kebele'=>$this->kebele,
            'latitude'=>$this->latitude,
            'longitude'=>$this->longitude,
            'is_active'=>$this->is_active,         
            'manager_id'=>$this->manager_id,
             'shop_name'=>$this->translate(request()->language)->shop_name ?? null,
            'region'=>$this->translate(request()->language)->region ?? null,
            'zone'=>$this->translate(request()->language)->zone ?? null,
            'woreda'=>$this->translate(request()->language)->woreda ?? null,
            'city'=>$this->translate(request()->language)->city ?? null,
            //'images'=>ImageResource::collection($this->images) ?? null,
          // 'translation'=>new ProductTranslationResource( $this->products)
        ];
    }
}
