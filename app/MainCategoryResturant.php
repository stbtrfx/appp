<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MainCategoryResturant extends Model
{
    //
    protected $table='main_categories_resturants';
    public $timestamps = false;

    public function resturant(){
        return $this->belongsTo(resturants::class, 'resturants_id','id');
    }
}
