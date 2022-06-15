<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Shop extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    public $translatedAttributes = ['shop_name','region','zone','woreda','city'];
   

    
    public $fillable=[
        //'shop_name',
        // 'region',
        // 'zone',
        // 'woreda',
        // 'city',
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
