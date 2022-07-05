<?php

namespace App\Http\Resources\UserSide;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
          
            'comment_title'=>$this->comment_title,
            'comment'=>$this->comment,
            'rate'=>$this->rate,
            'date'=>$this->created_at,

        ];
    }
}
