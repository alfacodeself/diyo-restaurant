<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Unit
        $gram = Unit::create(['name' => 'gram']);
        $litre = Unit::create(['name' => 'litre']);
        $item = Unit::create(['name' => 'item']);

        // Inventories
        $gram->inventories()->create([
            'name' => 'Garam',
            'price' => 50000,
            'amount' => 30
        ]);
        $gram->inventories()->create([
            'name' => 'Gula',
            'price' => 50000,
            'amount' => 40
        ]);
        $litre->inventories()->create([
            'name' => 'Minyak Goreng',
            'price' => 120000,
            'amount' => 4
        ]);
        $item->inventories()->create([
            'name' => 'Gas LPG',
            'price' => 300000,
            'amount' => 15
        ]);
        $item->inventories()->create([
            'name' => 'Kecap',
            'price' => 300000,
            'amount' => 15
        ]);
        $item->inventories()->create([
            'name' => 'Saus',
            'price' => 300000,
            'amount' => 15
        ]);
    }
}
