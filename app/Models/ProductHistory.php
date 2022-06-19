<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductHistory extends Model
{
    use HasFactory;
    public $fillable=['product_id','shop_id','qty','status'];

    // public function products()
    // {
    //     return $this->hasMany(Review::class);
    // }

}
