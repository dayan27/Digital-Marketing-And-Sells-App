<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopTranslation extends Model 
{
    use HasFactory;
    protected $fillable = ['shop_name','region','zone','woreda','city'];
    public $timestamps = false;

       
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
