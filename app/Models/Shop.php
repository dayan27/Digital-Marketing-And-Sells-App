<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;
    public $translatedAttributes = ['shop_name','region','zone','woreda','city'];
   

    
    public $fillable=[
        'shop_name',
        'region',
        'zone',
        'woreda',
        'city',
        'kebele',
        'latitude',
        'longitude',
        'manager_id',
        'is_active', 


    ];

    
    public function manager()
    {
        return $this->belongsTo(Manager::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('qty');
    }
    
}
