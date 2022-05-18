<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MainCategories extends Model
{
    //
    protected $fillable=[
        'name_en',
        'name_ar',
        'image',
        'status',
    ];
   
    public function getImageAttribute($value)
    {
        return asset('images/mainCategories/' . $value);

    } // end of get name attribute

    public function resturant(){
        return $this->belongsToMany(resturants::class);
    }
}
