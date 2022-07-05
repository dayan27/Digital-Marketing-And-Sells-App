<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    public $fillable=['user_id','order_status_id','payment_type_id','total_price','pin','shop_id'];

    public function order_status()
    {
        return $this->belongsTo(OrderStatus::class);
    }
    
    public function order_items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment_type()
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function address()
    {
        return $this->belongsTo(OrderAddress::class,'order_address_id');
    }

}
