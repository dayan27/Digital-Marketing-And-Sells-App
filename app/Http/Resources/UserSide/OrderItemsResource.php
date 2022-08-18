<?php

namespace App\Http\Resources\UserSide;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemsResource extends JsonResource
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
            'name'=>$this->product->name,
            'product_id'=>$this->product_id,
            'model'=>$this->product->model,
            'qty'=>$this->quantity,
            'unit_price'=>$this->unit_price,    
            // 'review'=> new ReviewResource($this->product->reviews()->where('user_id',56)->first())
           // 'model'=>$this->product->model,
        //    'comment_title'=>$this->product->reviews()->where('user_id',56)->first()->comment_title,
        'comment_id'=>$this->product->reviews()->where('user_id',request()->user()->id)->first()->id ??null,
        'comment'=>$this->product->reviews()->where('user_id',request()->user()->id)->first()->comment ??null,
        'rate'=>$this->product->reviews()->where('user_id',request()->user()->id)->first()->rate ??null,
           'date'=>$this->product->reviews()->where('user_id',request()->user()->id)->first()->created_at ??null,
        ];
    }
}
