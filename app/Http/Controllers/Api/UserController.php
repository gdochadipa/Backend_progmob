<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public $successStatus = 200;

    public function login(Request $request){

        $validator = Validator::make($request->all(), [
            
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'result' => $validator->errors()], $this->successStatus);
        }
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('nApp')->accessToken;
            return response()->json(['result' => $success,'status'=>'Success'], $this->successStatus);
        }else{
            return response()->json(['status' => 'error', 'result' => 'Email or Password is wrong!'], 401);
        }

    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error','message' => $validator->errors()], $this->successStatus);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
		$input['image_profile'] = "TEST";
		//dd($input);
        $user = User::create($input);
        $success['token'] =  $user->createToken('nApp')->accessToken;
        $success['name'] =  $user->name;

        return response()->json(['result' => $success['name'],'status'=>'Success'], $this->successStatus);
    }

    public function logout(Request $request)
    {
        $logout = $request->user()->token()->revoke();
        if ($logout) {
            return response()->json([
                'status' => 'Success',
                'result' => 'Successfully logged out'
            ], $this->successStatus);
        }
    }

    public function details()
    {
        $user = Auth::user();
       return response()->json(['result' => $user,'status'=>'Success'], $this->successStatus);
    }
	
	public function getToken(){
		$user = Auth::user();
		$success['token'] =  $user->createToken('nApp')->accessToken;
		$success['name'] =  $user->name;
		return response()->json(['result' => $success['name'],'status'=>'Success'], $this->successStatus);
	}
}
