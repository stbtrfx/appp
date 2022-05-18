<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    protected $fillable=[
        'user_id',
        'points',
        'code',
    ];
}
