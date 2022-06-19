<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    public $fillable=['user_id','order_status_id','payment_type_id','price','pin'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}
