<?php

namespace App\Http\Resources;

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
            'first_name'=>$this->user->first_name,
            'last_name'=> $this->user->last_name,
            'comment_title'=>$this->comment_title,
            'comment'=>$this->comment,
            'rate'=>$this->rate,
            'date'=>$this->created_at,

        ];
    }
}
