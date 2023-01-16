<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public $timestamps = false;
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
