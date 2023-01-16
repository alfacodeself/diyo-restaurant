<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = ['id'];
    public function product_variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
