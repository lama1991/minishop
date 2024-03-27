<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Product;
use Illuminate\Http\Request;
use Validator;
use App\Http\Traits\GeneralTrait;
class ShopController extends Controller
{
    use GeneralTrait;
    public function index()
    {
        //
    }

  
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'name'=>'required|regex:/[a-zA-Z\s]+/'
             ]
        );
                if($validator->fails())
                {
                   return $this->errorResponse($validator->errors(),422);
                }
     try{
        $request->merge(['user_id' => auth()->id()]);
        $shop = Shop::create($request->all());
        $msg='shop is created successfully';
        return $this->successResponse($shop,$msg,201);
            }
    
    catch (\Exception $ex)
    {
        return $this->errorResponse($ex->getMessage(),500);
    }
    }


    public function show(Shop $shop)
    {
        
    }

   


 
    public function update(Request $request, Shop $shop)
    {
        //
    }

  
    public function destroy(Shop $shop)
    {
        //
    }
    public function addProducts(Request $request)
    {
        try
        {
        $shop=Shop::with('products')->find(request['shop_id']);
        $products=Product::whereIn('id',request['product_id']);
        $shop->products()->saveMany($products);
        return $this->successResponse($shop,'add products');
        }catch(\Exception $ex)
        {
            return $this->errorResponse($ex->getMessage(),500);
        }

    }
}
