<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantProductCart extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public $timestamps = false;
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
    public function product_variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
