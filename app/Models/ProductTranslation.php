<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
{
    use HasFactory;
    protected $fillable = ['name','warranty','detail','description'];
    public $timestamps = false;

    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
   
}
