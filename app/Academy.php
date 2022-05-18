<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Academy extends Model
{
    //
    protected $guarded=[];


    public function getImageAttribute($value)
    {
        return asset('images/academy/' . $value);
    } // end of get image attribute

    public function levels(){
        return $this->belongsTo(Level::class,'level','id');
    }
    public function order(){

        return $this->belongsTo(OrderRecommendation::class,'service_id');
    }

    public function user(){
        // dd($this->order());
        // if($this->order()->service){
            return $this->belongsToMany(User::class,'order_recommendations','service_id','user_id');
        // }

    }

}
