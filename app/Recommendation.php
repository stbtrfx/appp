<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    public $is_buy;
    //
    protected $guarded=[];
    public function getImageAttribute($value)
    {
        return asset('images/recommendations/' . $value);
    } // end of get image attribute

    public function order(){
        return $this->belongsTo(OrderRecommendation::class,'service_id');
    }

    public function user(){
        return $this->belongsToMany(User::class,'order_recommendations','service_id','user_id');
    }

}
