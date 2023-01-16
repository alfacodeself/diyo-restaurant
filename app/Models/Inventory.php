<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = ['id'];
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
