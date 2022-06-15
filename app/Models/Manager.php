<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Manager extends Model
{
    use HasFactory,HasApiTokens;

    public $fillable=[
        'first_name',
        'last_name',
        'email',
        'manager_region',
        'manager_zone',
        'manager_woreda',
        'manager_city',
        'house_no',
        'role',


    ];

    public function phone_numbers()
    {
        return $this->hasMany(PhoneNumber::class);
    }

    
    public function shop()
    {
        return $this->hasOne(Shop::class);
    }

}
