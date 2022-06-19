<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;
    public $fillable=['order_name','order_description'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

}
