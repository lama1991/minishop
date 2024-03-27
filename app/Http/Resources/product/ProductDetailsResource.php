<?php

namespace App\Http\Resources\product;
use App\Http\Resources\user\UserResource;
use App\Http\Resources\review\ReviewResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailsResource extends JsonResource
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
            'name'=>$this-> product_name,
            'description'=>$this->desc,
            'price'=>$this->price,
            'category'=>$this->category->category_name,
            'reviews count'=>$this->reviews()->count(),
            'avg stars'=>$this->reviews()->avg('stars'),
            'all reviews'=>ReviewResource::collection($this->reviews),
            'user rieviewed'=>UserResource::collection($this->usersReviewed()),
             'how many orders'=>$this->orders()->count()



        ];
    }
}
