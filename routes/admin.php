<?php
Route::group(['namespace'=>'Admin'],function (){

    //Categories crud routes
    Route::match(['get','post'],'add-category','categoriesController@addCategory')->name('add-category');
    Route::match(['get','post'],'edit-category/{id}','categoriesController@editCategory')->name('edit-category');
    Route::get('show-categories','categoriesController@showCategory')->name('show-category');
    Route::get('delete-category/{id}','categoriesController@deleteCategory')->name('show-category');
    //products crud routes
    Route::match(['get','post'],'add-product','productsController@addProduct')->name('add-product');
    Route::match(['get','post'],'edit-product/{id}','productsController@editProduct')->name('edit-product');
    Route::get('show-products','productsController@showproducts')->name('show-products');
    Route::get('delete-product/{id}','productsController@deleteProduct')->name('show-product');

});
