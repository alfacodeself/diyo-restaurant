<?php

namespace Database\Seeders;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(InventorySeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(PaymentSeeder::class);
    }
}
