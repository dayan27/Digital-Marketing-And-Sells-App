<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{

    use HasFactory;
    protected $hidden=[ 

        'created_at',
        'updated_at',
        
    ];
    protected $fillable=[ 

    'user_region',
    'user_zone',
    'user_woreda',
    'phone_number',
];

public function orders()
{
    return $this->hasMany(Order::class);
}
}
