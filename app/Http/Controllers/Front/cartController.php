<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class cartController extends Controller
{
    public function addCart(Request $request)
    {
        //$sizeArr=explode('-',$request['product_size']);
        if ($request['product_size'] == 0) {
            return redirect()->back()->with('flash_message_error', 'Choose Size firstly');

        }
        //echo '<pre>';print_r()
        $session_id = Session::get('session_id');
        if (empty($session_id)) {
            $session_id = str_random(40);
            Session::put('session_id', $session_id);
        }
        $countProducts = DB::table('carts')->where(['product_id' => $request['product_id'], 'product_name' => $request['product_name'],
            'size' => $request['product_size'], 'user_email' => '', 'session_id' => $session_id])->count();
        if ($countProducts > 0) {
            return redirect('/cart')->with('flash_message_error', 'Product already exists yo can increase quantity');
        } else {
            DB::table('carts')->insert(['product_id' => $request['product_id'], 'product_name' => $request['product_name'],
                'product_code' => $request['product_code'], 'product_color' => $request['product_color'], 'size' => $request['product_size'],
                'price' => $request['product_price'], 'quantity' => $request['quantity'], 'user_email' => '',
                'session_id' => $session_id]);
            return redirect('/cart')->with('flash_message_success', 'Product added successfully in cart');
        }
    }

    public function Cart(Request $request)
    {
        $session_id = Session::get('session_id');
        //$userCart=Cart::where(['session_id'=>$session_id])->get();
        $userCart = DB::table('carts')->where(['session_id' => $session_id])->get();
        /*get the image name from products table and add it in $userCart array*/
        foreach ($userCart as $key => $cart) {
            $getProduct = Product::where(['id' => $cart->product_id])->first();
            $userCart[$key]->image = $getProduct->image;
        }
        return view('front.products.cart')->with(compact('userCart'));
    }
    public function deleteCartProduct($id=null){
        DB::table('carts')->where(['id'=>$id])->delete();
        return redirect('/cart')->with('flash_message_error','Product deleted successfully');
    }
    public function updateQuantityCart($id=null, $quantity){
        DB::table('carts')->where('id',$id)->increment('quantity',$quantity);
        return redirect()->back()->with('flash_message_success','Product quantity updated successfully');
    }

}
