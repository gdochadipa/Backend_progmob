<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Book::all();
        return response()->json(['data'=> $data, 'status' => 'successfull']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(request $request)
    {
        $res = response()->json(['status' => 'fail']);
        $book = new Book();
        $book->title = $request->title;
        $book->description = $request->description;
        $book->writer = $request->writer;
        $book->cover = $request->cover;
        $book->title = $request->title;
        $book->language = $request->language;
        $book->price = $request->price;
        $book->qty = $request->qty;
        if($book->save()){
            $res = response()->json(['status' => 'successfull']);
        }
        return $res;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id)
    {
        $res = response()->json(['status' => 'fail']);
        $book = Book::find($id);
        $book->title = $request->title;
        $book->description = $request->description;
        $book->writer = $request->writer;
        $book->cover = $request->cover;
        $book->title = $request->title;
        $book->language = $request->language;
        $book->price = $request->price;
        $book->qty = $request->qty;
        if($book->save()){
            $res = response()->json(['status' => 'successfull']);
        }
        return $res;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $res = response()->json(['status' => 'fail']);
        $book = Book::find($id);
        $book->title = $request->title;
        $book->description = $request->description;
        $book->writer = $request->writer;
        $book->cover = $request->cover;
        $book->title = $request->title;
        $book->language = $request->language;
        $book->price = $request->price;

        if ($book->save()) {
            $res = response()->json(['status' => 'successfull']);
        }
        return $res;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = Book::find($id);
        $res = response()->json(['status' => 'fail']);

        if ($book->delete()) {
            $res = response()->json(['status' => 'successfull']);
        }
        return $res;
    }
}
