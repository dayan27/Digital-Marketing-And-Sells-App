<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class ShopTranslation extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    protected $fillable = ['shop_name','region','zone','woreda','city'];
    public $timestamps = false;
}
