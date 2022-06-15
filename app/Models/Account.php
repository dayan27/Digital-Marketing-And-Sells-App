<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Account extends Model
{
    
    public $fillable=[
        'user_name',
        'password',
    ];
    use HasFactory,HasApiTokens;
}
