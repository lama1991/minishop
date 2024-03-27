<?php
namespace App\Http\Traits;
trait GeneralTrait{

    protected function successResponse($data, $message = null, $code = 200)
    {
        return response()->json([
            'status'=> 'Success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function errorResponse($message = null, $code)
    {
        return response()->json([
            'status'=>'Error',
            'message' => $message,
            'data' => null
        ], $code);
    }
      protected function apiResponse($data, $status,$error ,$statuscode = 200)
    {
       $array=[
        'data'=>$data,
        'status'=>$status,
        'error'=>$error,
        'statuscode'=>$statuscode
       ];
       return response($array);
    }
      protected function unAuthoriseResponse()
    {
       
      return $this->apiResponse (null,0,'unAutherized !!' , 401);
    }

     protected function requiredField($message)
    {
       
      return $this->apiResponse (null,0,$message ,200);
    }

   protected function apiValidation($request,$array)
    {
       foreach($array as $field)
       {
        if(!$request->has($field))
        return [false,'Field '.$field.'is required'];
         if(!$request['field']==null)
        return [false,'Field '.$field.'can not be empty'];
       }
     
        return [true,'no error'];
    }


}
