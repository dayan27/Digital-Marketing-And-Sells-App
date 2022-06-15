<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;


class Manager extends Model
{
    use HasFactory,HasApiTokens;
    use HasRoles;

    public $fillable=[
        'first_name',
        'last_name',
        'account_id',
        'email',
        'manager_region',
        'manager_zone',
        'manager_woreda',
        'manager_city',
        'manager_kebele',
        'house_no',


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
