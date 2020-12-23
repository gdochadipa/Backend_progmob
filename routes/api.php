<?php

use App\Http\Controllers\AddressController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
Route::post('/transaction/verified', [TransactionController::class, 'verifiedPayment']);
Route::post('/transaction/delivered', [TransactionController::class, 'verifiedDelivered']);
// Route::group(['middleware' => 'auth:api'], function () {
//     Route::get('user/detail', 'Api/UserController@details');
//     Route::post('logout', 'AApi/serController@logout');
// }); 

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/user/detail', [UserController::class, 'details']);
	Route::get('/user/token', [UserController::class, 'getToken']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('/updateToken', [UserController::class, 'updateTokenFCM']);
    Route::get('/user/testNotification', [UserController::class, 'testingSendNotification']);
    Route::post('/user/update', [UserController::class, 'updateUser']);
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/address', [AddressController::class, 'index']);
    Route::post('/address', [AddressController::class, 'store']);
    Route::put('/address/{id}', [AddressController::class, 'update']);
    Route::delete('/address/{id}', [AddressController::class, 'destroy']);
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/cart', [CartController::class, 'index']);
    Route::get('/cart/{id}', [CartController::class, 'getCart']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::put('/cart/{id}', [CartController::class, 'update']);
    Route::delete('/cart/{id}', [CartController::class, 'destroy']);
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/transaction', [TransactionController::class, 'index']);
    Route::post('/transaction', [TransactionController::class, 'store']);
    Route::get('/transaction/{id}', [TransactionController::class, 'showConfirmation']);
    Route::post('/transaction/confirm', [TransactionController::class, 'onConfirmation']);
    Route::post('/transaction/cancel', [TransactionController::class, 'onCanceled']);
});

Route::get('/book/{id}', [BookController::class, 'getBook']);
Route::get('/books', [BookController::class, 'index']);
Route::post('/books', [BookController::class, 'create']);
Route::put('/books/{id}', [BookController::class, 'update']);
Route::delete('/books/{id}', [BookController::class, 'delete']);
