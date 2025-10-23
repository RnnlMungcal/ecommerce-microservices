<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Product::insert([
            ['name' => 'Laptop', 'price' => 1500.00, 'description' => 'Gaming laptop'],
            ['name' => 'Phone', 'price' => 800.00, 'description' => 'Smartphone'],
            ['name' => 'Headphones', 'price' => 120.00, 'description' => 'Noise cancelling']
        ]);
    }
}
