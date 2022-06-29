<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AllProductResource extends JsonResource
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
           // 'name'=>$this->$this->translate(request('lang'))?? $this->name,  
            'price'=>$this->price,
            'description'=>$this->translate(request('lang'))->description?? $this->description,
            'number_of_review'=>$this->reviews()->count(),
            'five_star'=>$this->reviews()->where('rate',5)->count(),
            'four_star'=>$this->reviews()->where('rate',4)->count(),
            'three_star'=>$this->reviews()->where('rate',3)->count(),
            'two_star'=>$this->reviews()->where('rate',2)->count(),
            'one_star'=>$this->reviews()->where('rate',1)->count(),
            'images'=> new ImageResource($this->images()->first()) ?? null
        ];
    }
}
