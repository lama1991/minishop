<?php

namespace App\Http\Controllers\Api;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Traits\GeneralTrait;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Validator;
use function PHPUnit\Framework\isEmpty;

class AuthController
{
    use GeneralTrait;
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:3',
            ]);

        if($validator->fails()){
            return $this->apiResponse(null, 0,$validator->errors(),422);
           
        }
        try {
            $roles = Role::whereIn('id', $request['roles_id'])->get();
            if($roles->count()==0)
            {
                return $this->$this->apiResponse(null, 0,'No roles with such ids',500);
              
            }
            $input = $validator->validated();
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);
            $user->roles()->attach($roles);
           $data['name'] = $user->name;
            $data['user'] = $user;
            return $this->apiResponse($data, 1,'User is registered successfully.',200);
          
        }
        catch (\Exception $ex){
            return $this->apiResponse(null, false,$ex->getMessage() , 500);
        }
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validator =Validator::make($request->all(),[
            'email' => 'required|string',
            'password' => 'required|string'
        ]);
        if($validator->fails()){
            return $this->apiResponse(null, 0,$validator->errors(),422);
        }

try {
    $user = User::where('email', $request['email'])->first();

    if (!$user || !Hash::check($request ['password'], $user->password)) {
        return $this->apiResponse(null, 0,'incorrect username or password',400);
       
    }
    $data['token'] = $user->createToken('apiToken')->plainTextToken;
    $data['name'] = $user->name;
    return $this->apiResponse($data, 1,null,200);


}
        catch(\Exception $ex)
        {
            return $this->apiResponse(null, false,$ex->getMessage() , 500);
        }

    }

  public function logout(Request $request)
  {
      auth('sanctum')->user()->tokens()->delete();

      return $this->apiResponse([], 1,'User has logged out successfully.',200);
     
  }

}
