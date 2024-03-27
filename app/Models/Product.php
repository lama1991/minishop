<?php

namespace App\Models;
use  App\Models\User;
use App\Http\Controllers\products\ProductController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable=['product_name','desc','price'];
    protected $casts = [
        'id' => 'integer',
        'category_id'=>'integer'
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity');
    }
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
    // public function usersReviewed()
    // {
    //     $reviews=$this->reviews;
    //     $users=array();
    //     foreach($reviews as $review)
    //     {
    //         $users[]=$review->user;
    //     }
    //     return $users;
    // }
   
    public function usersReviewed()
    {
       $users=User::whereRelation('reviews','product_id','=',$this->id)->get();
        return $users;
    }
  


}
