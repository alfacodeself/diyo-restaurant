<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            'Martabak Telur' => [
                [
                    'name' => 'Original',
                    'additional_price' => 0,
                ],
                [
                    'name' => 'Spesial',
                    'additional_price' => 5000,
                ],
                [
                    'name' => 'Istimewa',
                    'additional_price' => 10000,
                ],
            ], 
            'Nasi Goreng' => [
                [
                    'name' => 'Ayam',
                    'additional_price' => 8000,
                ],
                [
                    'name' => 'Sosis',
                    'additional_price' => 5000,
                ]
            ], 
            'Nasi Kuning' => [
                [
                    'name' => 'Ayam',
                    'additional_price' => 8000,
                ],
                [
                    'name' => 'Telur',
                    'additional_price' => 5000,
                ],
                [
                    'name' => 'Tempe',
                    'additional_price' => 2000,
                ],
                [
                    'name' => 'Mie',
                    'additional_price' => 6000,
                ],
            ], 
            'Nasi Pecel' => [
                [
                    'name' => 'Ayam',
                    'additional_price' => 8000,
                ],
                [
                    'name' => 'Lele',
                    'additional_price' => 5000,
                ]
            ], 
            'Sate' => [
                [
                    'name' => 'Lontong',
                    'additional_price' => 8000,
                ],
            ], 
            'Bakso' => [
                [
                    'name' => 'Pentol Besar',
                    'additional_price' => 15000,
                ],
            ], 
            'Es Cendol' => [
                [
                    'name' => 'Susu',
                    'additional_price' => 3000,
                ],
            ], 
            'Es Buah' => [
                [
                    'name' => 'Susu',
                    'additional_price' => 3000,
                ],
            ], 
            'Es Susu' => [
                [
                    'name' => 'Jahe',
                    'additional_price' => 2000,
                ],
            ], 
            'Es Extra Joss' => [
                [
                    'name' => 'Susu',
                    'additional_price' => 3000,
                ],
            ]
        ];
        foreach ($products as $key => $product) {
            $p = Product::create([
                'name' => $key,
                'description' => 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Enim, nisi.',
                'price' => rand(5, 30) * 1000 
            ]);
            foreach ($products[$key] as $variant) {
                $p->product_variants()->create($variant);
            }
        }
    }
}
