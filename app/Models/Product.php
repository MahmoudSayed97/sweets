<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table='products';
    protected $fillable=['name','description','status','image','number_of_pieces','price'
    ,'status','size','delivery','adding_something_special','enough_for','delivery_time','cat_id','additons_id'];
    protected $hidden=[];

    public function categories(){
        return $this->belongsTo('App\Models\Category','cat_id');
    }
    public function additions(){
        return $this->belongsToMany('App\Models\Category','products_additions','addition_id','product_id','id','id');
    }
}
