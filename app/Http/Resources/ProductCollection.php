<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;



class ProductCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $price = $this->price;
        $price_before = $this->price;
        $is_discount = 0;
        if((isset($this->discount)) && ($this->discount->published == 1)&&  ($this->discount->to >= Carbon::now()->format('Y-m-d'))){
        //  dd('here');
                $is_discount = 1;
                
                if($this->discount->discount_type == 'Flat'){
                   $price  = $this->price - $this->discount->discount;
                   
                }else{
                    $price = $this->price * ($this->discount->discount)/100;
                }
            }
        return [
            'id'=>$this->id,
            'name_en'=>$this->name_en,
            'name_ar'=>$this->name_ar,
            'des_en'=>$this->des_en,
            'des_ar'=>$this->des_ar,
            'image'=>$this->image,
            'category_id'=>$this->category_id,
            'category_name'=>$this->category->name_en,
            'is_discount'=> $is_discount,
            'price'=>strval($price),
            'price_before'=>$price_before,
            'cal'=>$this->cal,
            'veg'=>$this->veg,
            'promotional'=>$this->promotional,
            'status'=>$this->status,

        ];

        // return parent::toArray($request);
    }
}
