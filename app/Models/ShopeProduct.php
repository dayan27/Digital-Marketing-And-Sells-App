<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopeProduct extends Model
{
    use HasFactory;
    public $fillable=[
        'product_id',
        'qty',
        'shop_id',

    ];
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function shops()
    {
        return $this->belongsToMany(Shop::class);
    }

}
