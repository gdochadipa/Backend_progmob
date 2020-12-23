<?php

namespace App\Http\Controllers;

use App\Models\cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Storage;
use App\Models\Book;
use Validator;

class CartController extends Controller
{
    public $successStatus = 200;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        // $cart = Cart::with(['user', 'book'])->where('user_id', '=', $user->id)->where('status', '=', 'notyet')->get();
        $cart = Cart::where('user_id', '=', $user->id)->where('status', '=', 'notyet')->get();
        return response()->json(['result' => $cart,'status'=>'Successfull'], $this->successStatus);
    }

    public function getCart($id)
    {
       $cart = Cart::find($id);
       return response()->json(['result' => $cart, 'status' => 'Successfull'], $this->successStatus);
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
        $user = Auth::user();
        $status = 'fail';
        $carts = new Cart();
        $carts->book_id = $request->book_id;
        $carts->user_id = $user->id;
        $carts->qty = $request->qty;
        $carts->status = 'notyet';

        $book = Book::find($request->book_id);
        if ($book->stock > 0 && $request->qty <= $book->stock) {
            if ($carts->save()) {
                $status = 'success';
                return response()->json(['result' => $status], $this->successStatus);
            }
        }
        return response()->json(['result' => $status,'result'=>'books does not enough']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(cart $cart)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $user = Auth::user();
        $carts = Cart::find($id);

        if ($request->qty == 0) {
            $carts->delete();
        } else {
            $carts->qty = $request->qty;
            $carts->save();
        }
        return response()->json(['result' => 'Successfull'], $this->successStatus);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id)
    {
        $user = Auth::user();
        $cart = Cart::find($id);
        $res = response()->json(['status' => 'fail']);

        if ($cart->delete()) {
            $res = response()->json(['status' => 'successfull']);
        }
        return $res;
    }
}
