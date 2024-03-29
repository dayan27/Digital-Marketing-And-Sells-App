<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class FeaturedProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //App::setLocale('oro');
        


        return[
            'id'=>$this->id,
            'name'=>$this->name,  
            'price'=>$this->price,
            
            'description'=> $this->translate(request('lang')),
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
