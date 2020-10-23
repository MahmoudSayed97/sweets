<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();
Route::group(['namespace'=>'Front','middleware'=>['auth']],function () {
  //routes of add to cart
    Route::match(['get', 'post'], '/add-cart', 'cartController@addCart')->name('add-cart');
    Route::match(['get', 'post'], '/cart', 'cartController@Cart')->name('show-cart');
    Route::match(['get', 'post'], '/cart/delete-product/{id}', 'cartController@deleteCartProduct')->name('delete-cart');
  //route of update product cart quantity
    Route::get('/cart/update-quantity/{id}/{quantity}', 'cartController@updateQuantityCart');

    //routes of orders
    Route::match(['get', 'post'], '/order/{id}', 'orderController@addOrder')->name('add-order');
});
