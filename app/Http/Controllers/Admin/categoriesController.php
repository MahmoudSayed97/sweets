<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class categoriesController extends Controller
{
    public function addCategory(Request $request){
        if($request->isMethod('post')){
            $data=$request->all();
            $category = new Category();
            $category->parient_id=$data["parient_id"];
            $category->name=$data['category_name'];
            $category->status=$data['category_status'];
            $category->number_of_pieces=$data['number_of_pieces'];
            if(!empty($data['description'])){
                $category->description=$data['category_description'];
            }
            else{
                $category->description="";}

            if($request->hasfile('img')){

                $iamge=time().'.'.$data['img']->getClientOriginalExtension();
                $data['img']->move(public_path('uploads/categories'),$iamge);
                $category->image=$iamge;
            }
            $category->save();
            return redirect('/admin/add-category');
        }
    }
        public function showCategory(){
            $categories=Category::get();
            return view('admin.Categories.show-categories')->with(compact('categories'));
        }
    public function editCategory(Request $request, $id=null){
        if($request->isMethod('post')){
            $data=$request->all();
            if($request->hasfile('img')){
                $category=new Category();
                echo $img_temp=Input::file('img');
                $extension=$img_temp->getClientOriginalExtension();
                $filename=time(). '.' .$extension;
                $img_path='uploads/categories/' .$filename;
                Image::make($img_temp)->resize(500,500)->save($img_path);
                $category->image=$filename;
            }
            Category::where(['id'=>$id])->update(['name'=>$data['category_name'],'description'=>$data['category_description'],'parient_id'=>$data['parient_id'],'status'=>$data['category_status'],
               'number_of_pieces'=>$data['number_of_pieces'] ]);
            return redirect()->back()->with('flash_message_success','Category has been updated!!');
        }
        //return subcategories
        $levels=Category::where(['parient_id'=>0])->get();

        $categoryDetails=Category::where(['id'=>$id])->first();
        return view('admin.Categories.edit-category')->with(compact('levels','categoryDetails'));
    }

    public function deleteCategory($id=null){
            $category=Category::where(['id'=>$id]);

            //حذف صورة الكاتجوري من التخزين
            $image_path=public_path("uploads/categories/".$category->image);
            if(file_exists($image_path)){
                unlink(public_path("uploads/categories/".$category->image));
            }
            $category->delete();
        return redirect()->back()->with("flash_message_memory","category deleted!!");
    }

}
