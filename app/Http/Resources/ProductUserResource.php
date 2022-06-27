<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductUserResource extends JsonResource
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
            'name'=>$this->name,
            'model'=>$this->model,
            'brand'=>$this->brand,
            'weight'=>$this->weight,
            'maximum_supply_voltage'=>$this->maximum_supply_voltage,
            'maximum_current_power'=>$this->maximum_current_power,
            'price'=>$this->price,
            'qty'=>$this->qty,
            'description'=>$this->description,
            'detail'=>$this->detail,
            'is_active'=>$this->is_active,
            'is_featured'=>$this->is_featured,
            'category_id'=>$this->category_id,
            'images'=>ImageResource::collection($this->images) ?? null,
            'related_products'=>RelatedProductResource::collection(Product::where('brand',$this->brand)
                                                                            ->where('category_id',$this->category_id)
                                                                            ->where('id','!=',$this->id)->paginate(5)),
                                                                           // ->where('id',!$this->id)->paginate(5)),
            'product_you_may_like'=>RecommendedProductResource::collection(Product::latest()->take(5)->get()),
            'reviews'=>$this->reviews,

        ];
    }
}
