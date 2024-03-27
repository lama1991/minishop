<?php

namespace App\Http\Controllers\orders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Traits\GeneralTrait;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\order\OrderDetailsResourse;

class OrderController extends Controller
{
    use GeneralTrait;
    public function index()
    {
       try{
           
           $data=Order::with('products')->get();
           $data=OrderDetailsResourse::collection( $data);
           return $this->apiResponse($data, true,0 , 200);
          
       }
       catch (\Exception $ex){
           return $this->apiResponse(null, false,$ex->getMessage() , 500);
       }
    }

    public function store(Request $request)
    {

      try {
           // $order=Order::create(['user_id'=>Auth::id()]);
           $order=new Order();
            Auth::user()->orders()->save($order);
            $products=$request['product_id'];
            $quantities=$request['quantity'];
            if(count($products)!=count($quantities))
            //review the status code
            return $this->apiResponse(null, 0,'not equal arrays' , 404);
          
            for($i=0;$i<count($products);$i++)
            {
                $order->products()->attach($products[$i],['quantity' => $quantities[$i]]);
            }
            $data= new OrderDetailsResourse($order);
           return  $this->apiResponse($data, true,0 , 201);
         
          }

        catch (\Exception $ex)
        {
            return $this->apiResponse(null, false,$ex->getMessage() , 500);
        }
    }
    public function update(Request $request, $id)
    {

        try{
            $order=Order::with('products')->find($id);
            if(!$order)
              return  $this->apiResponse(null, false,'No order with such id',404);
            if($order->user_id !=Auth::id())
                return $this->apiResponse(null,false,'you do not have this order',403 );
           
            $products=$request['product_id'];
            $quantities=$request['quantity'];

            if(count($products)!=count($quantities))
            return $this->apiResponse(null,false,'bad request not equal arrays',400 );

            for($i=0;$i<count($products);$i++)
                {
                    $pivot_data[]=['quantity'=>$quantities[$i]];
                }
            $sync_data=array_combine($products, $pivot_data);
            $order->products()->sync( $sync_data);
            $data=  $data= new OrderDetailsResourse($order);
            return  $this->apiResponse($data, true,null , 202);
         
        }
        catch (\Exception $ex){
            return $this->apiResponse(null, false,$ex->getMessage() , 500);
        }
    
    }

    public function show($id)
    {

        try{
            $order=Order::with('products')->find($id);
            if(!$order)
            return  $this->apiResponse(null, false,'No order with such id',404);
          
            $data=new OrderDetailsResourse($order);
            return  $this->apiResponse($data,1,null,200);
        }
        catch (\Exception $ex){
            return $this->apiResponse(null, false,$ex->getMessage() , 500);
        }
    }

    public function destroy($id)
    {
        try{
            $order=Order::find($id);
            if(!$order)
            return  $this->apiResponse(null, false,'No order with such id',404);
            $order->products()->detach();
            $order->delete();
            return  $this->apiResponse([],1,null,200);
        }
        catch (\Exception $ex){
            return $this->apiResponse(null, false,$ex->getMessage() , 500);
        }
    }

}
