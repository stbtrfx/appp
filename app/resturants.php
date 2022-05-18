<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class resturants extends Model
{
    //
     protected $guarded = ["user_id"];
        protected $fillable =['name_en', 'name_ar', 'phone1', 'phone2', 'address','lat', 'lng','facebook','twitter','youtube','image','status','user_id'
    
    ];

    public function getImageAttribute($value)
    {
        return asset('images/resturants/' . $value);

    } // end of get name attribute


    public function mainCategory(){
        return $this->belongsToMany(MainCategories::class);
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
