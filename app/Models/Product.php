<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
class Product extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;    
    public $translatedAttributes = ['name','warranty','description','detail',];
    
    public $fillable=[
        'name',
        'model',
        'warranty',
        'brand',
        'maximum_supply_voltage',
        'maximum_current_power',
        'price',
        'discount',
        'qty',
        'weight',
        'date_of_production', 
        'category_id',
        'description',
        'is_featured'

    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    
    public function images()
    {
        return $this->hasMany(Image::class);
    }

     
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

     
    public function product_sizes()
    {
        return $this->hasMany(ProductSize::class);
    }

    public function shops()
    {
        return $this->belongsToMany(Shop::class)->withPivot('qty');
    }

  
}
