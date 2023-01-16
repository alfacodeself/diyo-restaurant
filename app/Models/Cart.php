<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public $timestamps = false;

    public function variant_product_carts()
    {
        return $this->hasMany(VariantProductCart::class);
    }
    public function sale_details()
    {
        return $this->hasMany(SaleDetail::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
}
