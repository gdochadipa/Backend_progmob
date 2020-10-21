<?php

namespace App\Http\Controllers;

use App\Models\address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    public $successStatus = 200;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user  =  Auth::user();
        $address =  address::all()->where('user_id','=',$user->id);
        return response()->json(['data' => $address], $this->successStatus);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user  =  Auth::user();
        $validator = Validator::make($request->all(), [
            'address' => 'required|max:255',
            'district' => 'required',
            'province' => 'required',
            
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        
        $address =  new address();
        $address->user_id = $user->id;
        $address->address = $request->address;
        $address->district = $request->district;
        $address->province = $request->province;
        $address->status = $request->status;
        $address->save();
        $success = "Success";
        return response()->json(['status' => $success], $this->successStatus);    
        // $address

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\address  $address
     * @return \Illuminate\Http\Response
     */
    public function show(address $address)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\address  $address
     * @return \Illuminate\Http\Response
     */
    public function edit(address $address)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\address  $address
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user  =  Auth::user();
        $address = address::find($id);
        $validator = Validator::make($request->all(), [
            'address' => 'required|max:255',
            'district' => 'required',
            'province' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        
        $address->address = $request->address;
        $address->district = $request->district;
        $address->province = $request->province;
        $address->status = $request->status;      
        $address->save();
        
        $success = "Success";
        return response()->json(['status' => $success], $this->successStatus);    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\address  $address
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user  =  Auth::user();
        $address = address::find($id);  
        $address->delete();
        $success = "Success";
        return response()->json(['status' => $success], $this->successStatus);    
    }
}
