<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentMethod::create(['name' => 'BRI']);
        PaymentMethod::create(['name' => 'BNI']);
        PaymentMethod::create(['name' => 'Dana']);
        PaymentMethod::create(['name' => 'OVO']);
        PaymentMethod::create(['name' => 'GoPay']);
        PaymentMethod::create(['name' => 'Shopee Pay']);
    }
}
