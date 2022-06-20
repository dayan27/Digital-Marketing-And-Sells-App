<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    public $fillable=['unit_price','quanity','product_id','order_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}
