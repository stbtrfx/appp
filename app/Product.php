<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use BeyondCode\Vouchers\Traits\HasVouchers;
use App\Collection\ProductCollection;

class Product extends Model
{
    //
use HasVouchers;
    protected $fillable=[
        'name_en',
        'name_ar',
        'des_en',
        'des_ar',
        'image',
        'category_id',
        'price',
        'resturant_id',
        'in_package_price',
        'cal',
        'veg',
        'promotional',
        'status',
    ];

    public function getImageAttribute($value)
    {
        return asset('images/products/' . $value);
    } // end of get image attribute

    public function discount(){
        return $this->hasOne(Discount::class);
    }

    public function wishlist(){
        return $this->belongsTo(Wishlist::class,'product_id','id');
    }


    public function category(){
        return $this->belongsTo(Category::class,'category_id');
    }


    public function order_item(){
        return $this->belongsTo(OrderItem::class,'id','product_id');
    }

    public function packageBookingExtraItems()
    {
        return $this -> hasMany(PackageBookingExtraItems::class, 'product_id');
    } // end of package booking extra items

    public function newCollection(array $models = [])
    {

        return new ProductCollection($models);
    }
}
