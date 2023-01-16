<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = ['id'];
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
}
