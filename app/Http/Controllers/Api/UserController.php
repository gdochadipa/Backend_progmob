<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Facades\FCM as FacadesFCM;

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
        // dd($input);
        $user = new User();
        $user->name = $input['name'];
        $user->password = $input['password'];
        $user->email = $input['email'];
        $user->photo_profile = "https://firebasestorage.googleapis.com/v0/b/progmob-pratikum-7.appspot.com/o/user.png?alt=media&token=ff20317c-ac36-4ba5-b78f-60010f8f2734";
        
        if($user->save()){
            $success['token'] =  $user->createToken('nApp')->accessToken;
            $success['name'] =  $user->name;
        }

        

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
    
    public function updateTokenFCM(Request $request){
        $user = Auth::user();
        $user_get = User::find($user->id);
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()], $this->successStatus);
        }

        $user_get->fcm_token = $request->fcm_token;
        $user_get->save();
        return response()->json(['result' => $user->name, 'status' => 'Success'], $this->successStatus);
    }
	
	
    public function testingSendNotification(){
        $user = Auth::user();
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder('my title');
        $notificationBuilder->setBody('Hello world')->setSound('default');
		
		
        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['data' => '{"title":"testing","message":"testing"}']);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $token = $user->fcm_token;

        $downstreamResponse = FacadesFCM::sendTo($token, $option, $notification, $data);

        return response()->json(['result' => $downstreamResponse, 'status' => 'Success'], $this->successStatus);

    } 

    public function updateUser(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()], $this->successStatus);
        }

        $getuser = Auth::user();
        $user  = User::find($getuser->id);
        $user->name = $request->name;
        $user->email = $request->email;
        if($user->save()){
            return response()->json(['result' => $user->name, 'status' => 'Success'], $this->successStatus);
        }

    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo_profile' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()], $this->successStatus);
        }

        $getuser = Auth::user();
        $user  = User::find($getuser->id);
        $user->photo_profile = $request->photo_profile;
        if ($user->save()) {
            return response()->json(['result' => $user->name, 'status' => 'Success'], $this->successStatus);
        }else{
            return response()->json(['status' => 'error', 'status' => 'Failed'], $this->successStatus);
        }
        // photo_profile
    }
}
