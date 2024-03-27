<?php

namespace App\Http\Controllers\reviews;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\User;
use App\Http\Traits\GeneralTrait;
use Illuminate\Support\Facades\Auth;
use Validator;

use App\Http\Resources\review\ReviewResource;
use App\Models\Product;

class ReviewController extends Controller
{
    use GeneralTrait;
    public function index()
    {
       try{
           
          $reviews=Review::with('user','product')->get();
          $data=ReviewResource::collection( $reviews);
           return $this->apiResponse($data, true,0 , 200);
       }
       catch (\Exception $ex){
        return $this->apiResponse(null, false,$ex->getMessage() , 500);
       }
    }

    public function store(Request $request)
    {
        // $user_id=Auth::id(); 
        // $user=User::with('orders.products:id')->find( $user_id);
         //
             $prod_ids=array();
            $products=Product::whereRelation('orders','user_id',Auth::id());
             $prod_ids[]=$products->pluck('id');
            
             $validator=Validator::make($request->all(),[
            'product_id'=>'required|numeric',
            'stars'=>'required|numeric',
            'comment'=>'regex:/[a-zA-Z\s]+/']
        );
                if($validator->fails()){
            return $this->errorResponse($validator->errors(),422);
        }
        if(!in_array($request['product_id'],$prod_ids))
        return $this->errorResponse('you did not buy this product',422);
      try {
        $data= $validator->validated();
        $data['user_id']=Auth::id();
           
        $review=Review::create($data);
        
           $data=$review;
           $msg='review is created successfully';
            return $this->successResponse($data,$msg,201);
        }
        catch (\Exception $ex)
        {
            return $this->errorResponse($ex->getMessage(),500);
        }

    }
    
    public function update(Request $request, $id)
    {

        try{
            $data=Review::find($id);
            if(!$data)
                return $this->errorResponse('No review with such id',404);

            $data->update($request->all());
            $data->save();
            $msg='The review is updated successfully';
            return $this->successResponse($data,$msg);
         
        }
        catch (\Exception $ex){
            return $this->errorResponse($ex->getMessage(),500);
        }
    }
   
}
