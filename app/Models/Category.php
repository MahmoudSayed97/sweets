<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table='categories';
    protected $fillable=['parient_id','name','description','status','image','number_of_pieces'];
    protected $hidden=[];

    public function Product(){
        return $this->hasMany('App\Models\Product','cat_id');
    }
}
