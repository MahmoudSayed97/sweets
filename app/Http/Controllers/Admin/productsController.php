<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Models\Addition;
use App\Models\Product_Delivery;
use App\Models\ProductsAddion;
use App\Product;
use Illuminate\Http\Request;

class productsController extends Controller
{
    public function showProduct(){
        $products=Product::get();
        return view ('admin.Products.view-product')->with(compact('products'));
    }
    public function deleteProduct($id=null){
        $product=Product::where(['id'=>$id]);;
        //حذف صورة المنتج من التخزين
        $image_path=public_path("uploads/products/".$product->image);
        if(file_exists($image_path)){
            unlink(public_path("uploads/products/".$product->image));
        }
        $product->delete();
        $cat_id=Product::select('cat_id')->where(['id'=>$id]);
        $number_of_pieces=Product::select('number_of_pieces')->where(['id'=>$id]);
        $this->decrementCategoryPieces($cat_id , $number_of_pieces);
        return redirect()->back()->with("flash_message_memory","item deleted!!");
    }

    public function addProduct(Request $request){
        if($request->isMethod('post')){
            $data=$request->all();
            $product = new Product();
            $addition=new ProductsAddion();
            $product->name=$data["product_name"];
            $product->cat_id=$data['cat_id'];
            $product->status=$data['product_status'];
            $product->number_of_pieces=$data['number_of_pieces'];
            $product->size=$data['product_size'];
            $product->enough_for=$data['enough_for'];
            $product->delivery_time=$data['delivery_time'];
            if(!empty($data['product_description'])){
                $product->description=$data['product_description'];
            }
            else{
                $product->adding_something_special="";}
            if(!empty($data['adding_something_special'])){
                $product->adding_something_special=$data['adding_something_special'];
            }
            else{
                $product->adding_something_special="";}

            $product->price=$data['product_price'];

            if($request->hasfile('img')){

                $iamge=time().'.'.$data['img']->getClientOriginalExtension();
                $data['img']->move(public_path('uploads/products'),$iamge);
                $product->image=$iamge;
            }
            $product->save();
            $this->incrementCategoryPieces($data['cat_id'],$data['number_of_pieces']);
            return redirect('/admin/add-product')->with('message','Product has been added');
        }
        $categories=Category::all();
        $additions=Addition::all();
        return view ('admin.Products.add-product')->with(compact('categories','additions'));
    }
    public function editProduct(Request $request, $id=null){
        if($request->isMethod('post')){
            $data=$request->all();
            $previousItemNumber=Product::select('number_of_pieces')->where(['id'=>$id]);

            //update number of items in category
            if ($data['number_of_pieces']>$previousItemNumber){
                $res=$data['number_of_pieces']-$previousItemNumber;
                $this->incrementCategoryPieces($data['cat_id'], $res);
            }
            else{
                $res=$previousItemNumber-$data['number_of_pieces'];
                $this->decrementCategoryPieces($data['cat_id'], $res);
            }
            if($request->hasfile('image')){
                $product=new Product();
                echo $img_temp=Input::file('image');
                $extension=$img_temp->getClientOriginalExtension();
                $filename=time(). '.' .$extension;
                $img_path='uploads/products/' .$filename;
                Image::make($img_temp)->resize(500,500)->save($img_path);
                $product->image=$filename;
            }

            if(!empty($data['product_description'])){
                $data['product_description']="";
            }
            if(!empty($data['adding_something_special'])){
                $data['adding_something_special']="";
            }
            Product::where(['id'=>$id])->update(['name'=>$data['product_name'],'cat_id'=>$data['cat_id'],'description'=>$data['product_description'],
                'price'=>$data['product_price'],'number_of_pieces'=>$data['number_of_pieces'],'size'=>$data['product_size'],
                'enough_for'=>$data['enough_for'],'delivery_time'=>$data['delivery_time'],'adding_something_special'=>$data['adding_something_special']]);
            return redirect()->back()->with('flash_message_success','Product has been updated!!');


        }
        $prodDetails=Product::where(['id'=>$id])->first();
        $categories=Category::all();
        return view('admin.Products.edit-product')->with(compact('prodDetails','categories'));

 }
    public function incrementCategoryPieces($cat_id, $num){
        $category=Category::where(['id'=>$cat_id])->first();
        $number=$category->number_of_pieces;
        $number+=$num;
        Product::where(['id'=>$cat_id])->update(['number_of_pieces'=>$number]);
    }
    public function decrementCategoryPieces($cat_id, $num){
        $category=Category::where(['id'=>$cat_id])->first();
        $number=$category->number_of_pieces;
        $number-=$num;
        Product::where(['id'=>$cat_id])->update(['number_of_pieces'=>$number]);
    }


}
