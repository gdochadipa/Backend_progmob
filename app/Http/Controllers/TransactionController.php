<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;
use App\Models\cart;
use App\Models\User;
use App\Models\transaction_detail;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public $successStatus = 200;

    public function update_transaction()
    {
        $today = Carbon::now();
        $transaction = transaction::where('status', '=', 'unverified')->get();
        
        foreach ($transaction as $index) {
            $expired = Carbon::parse($index->timeout);
            if (!$today->lte($expired) && $index->proof_of_payment == null) {

                $index->status = 'expired';
                $index->save();
            }
        }
    }

    public function index()
    {
        $user  =  Auth::user();
        $this->update_transaction();
        $transaction = transaction::with('user')->where('user_id','=',$user->id)->orderBy('id', 'DESC')->get();
        return response()->json(['result' => $transaction], $this->successStatus);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $today = date('Y-m-d H:i:s');
        $todayD = date('Y-m-d');
        $time = Carbon::parse($today);
        $next_time = $time->addDay();
        $user = Auth::user();
        $carts = cart::with('book')->where('user_id', '=', $user->id)->where('status', '=', 'notyet')->get();
        $transaction =  new transaction();
        $transaction->timeout = $next_time;
        $transaction->user_id = $user->id;
        $transaction->address_id = $request->address_id;
        $transaction->status = 'unverified';
        $transaction->total = 0;
        $total = 0;

        if($transaction->save()){
            $getTrans = transaction::where('user_id', '=', $user->id)->where('status', '=', 'unverified')
            ->orderBy('id', 'desc')->first();
             foreach ($carts as $cart ){
                $transaction_det = new transaction_detail();
                $transaction_det->transaction_id = $getTrans->id;
                $transaction_det->book_id = $cart->book_id;
                $transaction_det->qty = $cart->qty;
                $transaction_det->price = $cart->book->price;
                $transaction_det->save();

                $price = $cart->book->price * $cart->qty;
                $total += $price;
                $cart->status = 'checkedout';
                $cart->save();
                $book = book::find($cart->book_id);
                $book->stock = $book->stock - $cart->qty;
                $book->save();
             }
            $getTrans->total = $total;
            $getTrans->save();
            $success = "Success";
            return response()->json(['result' => $success], $this->successStatus);    
        }else{
            $success = "Fail";
            return response()->json(['result' => $success], 401);   
        }
    }

    public function showConfirmation($id)
    {
        $user = Auth::user();
        $transaction = transaction::with('transaction_detail')->where('id', '=', $id)->first();
        $startTime = Carbon::now();
        $finishTime = Carbon::parse($transaction->timeout);
        $totalDuration = $finishTime->diffInSeconds($startTime);
        $time = gmdate('H : i : s', $totalDuration);
        return response()->json(['result' => $transaction,'start_time' => $startTime,'finish_time'=> $finishTime,'total_duration'=> $totalDuration], $this->successStatus);
    }

    public function onConfirmation(Request $request)
    {
        $user = Auth::user();
        $transaction = transaction::with('transaction_detail')->where('id', '=', $request->transaction_id)->first();
        $transaction->status = $request->status;
        $transaction->save();
        $success = "Success";
        return response()->json(['result' => $success], $this->successStatus);    
    }
// Req
//     transaction_id

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(transaction $transaction)
    {
        
    }

    public function onCanceled(Request $request)
    {
        $transaction = transaction::find($request->transaction_id);
        $transaction->status = 'canceled';
        if($transaction->save()){
            $transaction_det = transaction_detail::where('transaction_id','=', $request->transaction_id);
            foreach ($transaction_det as $det ) {
                $book =  book::find($det->id);
                $book->qty = $book->qty + $det->qty;
                $book->save();
            }
        }
        $success = "Success";
        return response()->json(['result' => $success], $this->successStatus);   

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(transaction $transaction)
    {
        //
    }
}
