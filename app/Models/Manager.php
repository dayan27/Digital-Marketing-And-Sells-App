<?php

namespace App\Models;

use App\Notifications\NewPasswordNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;


class Manager extends Authenticatable implements MustVerifyEmail
{
    use HasFactory,HasApiTokens,Notifiable;
    use HasRoles;
    protected $guard_name = 'web';

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
        'type'
    ];

    public function phone_numbers()
    {
        return $this->hasMany(PhoneNumber::class);
    }


    public function shop()
    {
        return $this->hasOne(Shop::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    // public function sendPasswordResetNotification($token)
    // {
    //     $url = 'https://10.161.176.171:8080/reset-password?token='.$token;
    //     $this->notify(new NewPasswordNotification($url));
    // }

    // public function sendEmailVerificationNotification()
    // {
    //     $this->notify(new VerifyEmailNotification());
    // }
}
