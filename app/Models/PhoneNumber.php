<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneNumber extends Model
{
    use HasFactory;
    public $fillable=[
        'phone_number',
        'manager_id',

    ];
    
    
    public function manager()
    {
        return $this->belongsTo(Manager::class);
    }

    
}
