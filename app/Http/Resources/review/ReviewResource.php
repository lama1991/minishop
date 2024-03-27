<?php

namespace App\Http\Resources\review;

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
        return  [
            'id' => $this->id,
            'product'=>$this->product->product_name,
            'user'=>$this->user->name,
            'comment'=>$this->comment,
            'stars'=>$this->stars

        ];
    }
}
