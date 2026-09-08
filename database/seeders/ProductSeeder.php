<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Primary Database
        |--------------------------------------------------------------------------
        */

        $primaryProducts = [
            [
                'name' => 'Laptop',
                'detail' => 'Dell business laptop with 8GB RAM and 512GB SSD.',
            ],
            [
                'name' => 'Keyboard',
                'detail' => 'Wireless mechanical keyboard for professional use.',
            ],
            [
                'name' => 'Mouse',
                'detail' => 'Wireless Bluetooth mouse with ergonomic design.',
            ],
            [
                'name' => 'Webcam',
                'detail' => 'Full HD webcam for video meetings and streaming.',
            ],
        ];

        foreach ($primaryProducts as $productData) {
            Product::create($productData);
        }

        /*
        |--------------------------------------------------------------------------
        | Secondary Database
        |--------------------------------------------------------------------------
        */

        $secondaryProducts = [
            [
                'name' => 'Monitor',
                'detail' => '24-inch Full HD monitor for office and development work.',
            ],
            [
                'name' => 'Headphones',
                'detail' => 'Wireless headphones with noise cancellation.',
            ],
            [
                'name' => 'Printer',
                'detail' => 'Color printer suitable for home and office use.',
            ],
            [
                'name' => 'Tablet',
                'detail' => 'Lightweight tablet for business and entertainment.',
            ],
        ];

        foreach ($secondaryProducts as $productData) {
            $product = new Product();

            $product->setConnection('mysql_second');

            $product->name = $productData['name'];
            $product->detail = $productData['detail'];

            $product->save();
        }
    }
}