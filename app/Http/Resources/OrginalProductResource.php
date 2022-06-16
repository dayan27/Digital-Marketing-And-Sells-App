<?php

namespace App\Http\Resources;

use App\Models\ProductTranslation;
use Illuminate\Http\Resources\Json\JsonResource;

class OrginalProductResource extends JsonResource
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
            'model'=>$this->model,
            'brand'=>$this->brand,
            'material'=>$this->material,
            //'effciency'=>$this->effciency,
            'maximum_supply_voltage'=>$this->maximum_supply_voltage,
            'maximum_current_power'=>$this->maximum_current_power,
            'price'=>$this->price,
            'qty'=>$this->qty,
            'category_id'=>$this->category_id,
            'name'=>$this->translate(request()->language)->name,
            // 'warranty'=>$this->translate(request()->language)->warranty,
            // 'function'=>$this->translate(request()->language)->function,
            'detail'=>$this->translate(request()->language)->detail,
            'description'=>$this->translate(request()->language)->description,
            'images'=>ImageResource::collection($this->images) ?? null,
          //  'translation'=>new ProductTranslationResource( $this->translate(request()->language))
            


        ];
    }
}
