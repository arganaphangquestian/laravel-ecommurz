<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', 'UserController@register');
Route::post('login', 'UserController@login');
Route::get('user', 'UserController@getAuthenticatedUser')->middleware('jwt.verify');

/* Cart */
Route::post('cartamount', 'CartController@changeAmount')->middleware('jwt.verify');
Route::post('addcart', 'CartController@addToCart')->middleware('jwt.verify');
Route::get('user/cart', 'CartController@getCartByUser')->middleware('jwt.verify');
Route::resource('cart', CartController::class)->middleware('jwt.verify');

/* Product */
Route::resource('product', ProductController::class)->middleware('jwt.verify');

/* Transaction */
Route::get('user/transaction', 'TransactionController@getTransactionByUser')->middleware('jwt.verify');
Route::get('pay/transaction/{id}', 'TransactionController@payTransactionByID')->middleware('jwt.verify');
Route::resource('transaction', TransactionController::class)->middleware('jwt.verify');