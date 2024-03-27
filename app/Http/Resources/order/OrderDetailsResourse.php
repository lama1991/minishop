<?php

namespace App\Http\Resources\order;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\user\UserResource;
class OrderDetailsResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'user'=>new UserResource($this->user),
            'products'=>$this->products,
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans()

        ];
    }
}
