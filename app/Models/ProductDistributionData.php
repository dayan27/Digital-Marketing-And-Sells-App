<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDistributionData extends Model
{
    use HasFactory;
    public $fillable=['product_id','shop_id','qty','status','provided_date'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function shops()
    {
        return $this->belongsToMany(Shop::class);
    }

}
