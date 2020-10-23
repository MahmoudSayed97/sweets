<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Addition extends Model
{
    protected $table='additions';
    protected $fillable=['name','price','status','created_at','updated_at'];
    protected $hidden=['created_at','updated_at'];
    public function products(){
        return $this->belongsToMany('App\Models\Product','products_additions','product_id','addition_id','id','id');
    }
}
