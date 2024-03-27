<?php

namespace App\Http\Controllers\products;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Traits\GeneralTrait;
use Validator;
use App\Http\Resources\product\ProductDetailsResource;
use function Nette\Utils\isEmail;
use function PHPUnit\Framework\isEmpty;

class ProductController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       try{
           $msg='all products are Right Here';
           $produats=Product::with('category')->get();
            $data=ProductShortResource::collection( $produats);
           return $this->successResponse($data,$msg);
       
       }
       catch (\Exception $ex){
           return $this->errorResponse($ex->getMessage(),500);
       }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator=Validator::make($request->all(),[
            'product_name'=>'required|regex:/[a-zA-Z\s]+/',
                'desc'=>'required|string',
                'price'=>'required|numeric'
            ]
        );
                if($validator->fails()){
            return $this->errorResponse($validator->errors(),422);
        }
      try {
            $category = Category::firstOrCreate([
                'category_name' => $request->category_name
            ]);
            $product = Product::create($request->all());
            $product->category()->associate($category)->save();
           $data=$product;
           $msg='product is created successfully';
            return $this->successResponse($data,$msg,201);
        }
        catch (\Exception $ex)
        {
            return $this->errorResponse($ex->getMessage(),500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Poduct  $poduct
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        try{
            $product=Product::find($id);
            if(!$product)

                return $this->apiResponse(null,0,'No product with such id' ,404);
            $data=new ProductDetailsResource($product);
            return $this->apiResponse($data,1,0 ,200);
            
        }
        catch (\Exception $ex){
            return $this->apiResponse(null, false,$ex->getMessage() , 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $poduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        try{
            $data=Product::find($id);
            if(!$data)
                return $this->errorResponse('No product with such id',404);

            $data->update($request->all());
            $data->save();
            $msg='The product is updated successfully';
            return $this->successResponse($data,$msg);
        }
        catch (\Exception $ex){
            return $this->errorResponse($ex->getMessage(),500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Poduct  $poduct
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $data=Product::find($id);
            if(!$data)
                return $this->errorResponse('No product with such id',404);

            $data->delete();
            $msg='The product is deleted successfully';
            return $this->successResponse($data,$msg);
        }
        catch (\Exception $ex){
            return $this->errorResponse($ex->getMessage(),500);
        }
    }

    public function  filterProductsByCategory($letter)
    {
        try {
            $data= Product::whereRelation('category','category_name','like',$letter.'%')->with('category')->get();
            $msg='Got data Successfully';
            return $this->successResponse($data,$msg);
        }
    catch (\Exception $ex)
    { return $this->errorResponse($ex->getMessage(),500); }
    }

     public function  highReviewed()
    {
        try {
           $products=Product::withCount(['reviews'])->orderBy('reviews_count')
           ->get();
          
           $data= $products;
            $msg='Got data Successfully';
            return $this->successResponse($data,$msg);
        }
    catch (\Exception $ex)
    { return $this->errorResponse($ex->getMessage(),500); }
    }

    public function  highStarsAvg()
    {
        try {
          $products=Product::withAvg('reviews','stars')->orderBy('reviews_avg_stars','desc')
           ->get();
          
           $data= $products;
            $msg='Got data Successfully';
            return $this->successResponse($data,$msg);
        }
    catch (\Exception $ex)
    { return $this->errorResponse($ex->getMessage(),500); 
    }
    }

    public function  group()
    {
        try {
          $products=Product::with(['category' => function($query){
            $query->groupBy(['category_name','id']);
        }])->orderBy('price')->get();
         
           $data= $products;
            $msg='Got data Successfully';
            return $this->successResponse($data,$msg);
        }
    catch (\Exception $ex)
    { return $this->errorResponse($ex->getMessage(),500); 
    }
    }
}
