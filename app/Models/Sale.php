<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    public function sale_details()
    {
        return $this->hasMany(SaleDetail::class);
    }
    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
