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
            [
                'name' => 'Gaming Laptop',
                'image_url' => 'https://dlcdnwebimgs.asus.com/gain/c787985c-3288-4672-b758-97865efb3580/',
                'price' => 1500.00,
                'description' => 'High-performance gaming laptop with Intel Core i7, NVIDIA RTX 4060 GPU, 16GB RAM, and 1TB SSD. Perfect for gaming, streaming, and productivity.'
            ],
            [
                'name' => 'Smartphone',
                'image_url' => 'https://cdn.thewirecutter.com/wp-content/media/2024/09/iphone-2048px-6979.jpg?auto=webp&quality=75&width=1024',
                'price' => 800.00,
                'description' => 'Sleek smartphone with a 6.7-inch OLED display, 128GB storage, and 48MP dual camera system. Delivers stunning photos and smooth performance all day.'
            ],
            [
                'name' => 'Wireless Headphones',
                'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTju4BfZRPQWYDlGa4w9C-6iCdqTrouk4GKkA&s',
                'price' => 120.00,
                'description' => 'Noise-cancelling wireless headphones with up to 30 hours of battery life. Features deep bass, clear mids, and a comfortable over-ear fit.'
            ],
            [
                'name' => 'Smartwatch',
                'image_url' => 'https://img.freepik.com/free-vector/smart-watch-realistic-image-black_1284-11873.jpg',
                'price' => 250.00,
                'description' => 'Track your fitness, heart rate, and sleep with a bright AMOLED display and water-resistant body. Compatible with both Android and iOS.'
            ],
            [
                'name' => 'Tablet Pro 10"',
                'image_url' => 'https://img.freepik.com/free-vector/digital-device-mockup_53876-89357.jpg',
                'price' => 600.00,
                'description' => '10-inch tablet with high-resolution display, 8-core processor, and 128GB storage. Ideal for streaming, note-taking, and creative work.'
            ],
            [
                'name' => 'Digital Camera',
                'image_url' => 'https://img.freepik.com/free-photo/photo-camera-still-life_23-2150630955.jpg?semt=ais_hybrid&w=740&q=80',
                'price' => 900.00,
                'description' => 'DSLR camera with 24.2MP sensor, 4K video recording, and dual-lens kit. Perfect for photographers who need power and portability.'
            ],
            [
                'name' => '4K Monitor 27"',
                'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR9XLj6X3guAG-n-miQx84Jb8nQ9ozUYPkENQ&s',
                'price' => 300.00,
                'description' => 'Ultra HD 4K monitor with 27-inch IPS panel, 144Hz refresh rate, and HDR10 support. Great for design, gaming, and productivity setups.'
            ],
            [
                'name' => 'Mechanical Keyboard',
                'image_url' => 'https://img.freepik.com/premium-vector/modern-grey-keyboard-minimalistic-keyboard-with-black-buttons-realistic-grey-color-computer-bluetooth-keyboard-white-background-vector-illustration_399089-3491.jpg',
                'price' => 80.00,
                'description' => 'Compact mechanical keyboard with customizable RGB backlight and hot-swappable switches. Designed for gamers and coders alike.'
            ],
            [
                'name' => 'Wireless Mouse',
                'image_url' => 'https://img.freepik.com/premium-photo/computer-mouse-isolated_78361-21.jpg',
                'price' => 50.00,
                'description' => 'Ergonomic wireless mouse with silent click buttons and adjustable DPI. Long-lasting battery life and smooth Bluetooth connectivity.'
            ],
            [
                'name' => 'All-in-One Printer',
                'image_url' => 'https://img.freepik.com/premium-vector/realistic-inkjet-printer-isoalted-white-background_208593-71.jpg?semt=ais_hybrid&w=740&q=80',
                'price' => 200.00,
                'description' => 'Multifunction printer that supports wireless printing, scanning, and copying. Compact, efficient, and compatible with major OS platforms.'
            ],
        ]);
    }
}
