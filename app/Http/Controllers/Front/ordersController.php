<?php

namespace App\Http\Controllers\Front;

use App\Category;
use App\Http\Controllers\Controller;
use App\Models\Addition;
use App\Models\Cart;
use App\Models\Orders;
use App\Models\ProductsAddion;
use Illuminate\Http\Request;

class ordersController extends Controller
{
    public function addOrder(Request $request, $id){
        if($request->isMethod('post')){
            $data=$request->all();
            $order = new Orders();
            $order->order_name=$data["order_name"];
            $order->price=$data['price'];
            //chk if user logged in or use middleware auth on routes
            if (Auth::check())
            {
                $order->customer_mobile=auth()->user()->mobile;
                $order->customer_email=auth()->user()->email;
            }
            $order->qantity=$data['order_quantity'];
            $order->delivery=$data['delivery'];
            //later will code order_number using some package
            $order->order_number='';
            //
            if(!empty($data['notes'])){
                $order->adding_something_special=$data['notes'];
            }
            else{
                $order->notes="";}
            $order->order_time=date('Y-m-d H:i:s');
            $order->payment=$data['payment'];
            $order->save();
            return redirect('/front/cart')->with('message','تم ارسال الطلب بنجاح سوف يتم ارسال رسالة نصية برقم الطلب');
        }

        $carts=Cart::where(['id'=>$id])->first();
        return view('front.orders',with(compact('carts')));
    }
}
