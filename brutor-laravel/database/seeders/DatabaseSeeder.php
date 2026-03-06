<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Supplier;
use App\Models\Item;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@brutor.com',
            'password' => 'admin123',
            'role' => 'admin',
            'fname' => 'Admin',
            'lname' => 'User',
            'avatar' => 'images/avatars/jerome.jpg',
        ]);

        // Customer user
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'role' => 'customer',
            'fname' => 'John',
            'lname' => 'Doe',
            'avatar' => 'images/avatars/jerome.jpg',
        ]);

        // Suppliers
        Supplier::create([
            'name' => 'AutoParts Co.',
            'contact_email' => 'info@autoparts.com',
            'contact_phone' => '09171234567',
            'lead_time' => '3-5 days',
            'website' => 'https://autoparts.com',
        ]);

        Supplier::create([
            'name' => 'ToolRent PH',
            'contact_email' => 'hello@toolrent.ph',
            'contact_phone' => '09181234567',
            'lead_time' => '1-2 days',
            'website' => 'https://toolrent.ph',
        ]);

        // Items
        Item::create([
            'title' => 'Spark Plug',
            'description' => 'NGK Iridium spark plug for most engines',
            'cost_price' => 150.00,
            'sell_price' => 250.00,
            'image_path' => 'images/items/sparkplug.jpg',
            'category' => 'Engine',
            'stock_quantity' => 50,
            'supplier_id' => 1,
            'type' => 'product',
        ]);

        Item::create([
            'title' => 'Oil Filter',
            'description' => 'High-quality oil filter for sedans',
            'cost_price' => 200.00,
            'sell_price' => 350.00,
            'image_path' => 'images/items/oilfilter.jpg',
            'category' => 'Engine',
            'stock_quantity' => 30,
            'supplier_id' => 1,
            'type' => 'product',
        ]);

        Item::create([
            'title' => 'Brake Pad Set',
            'description' => 'Ceramic brake pads for front wheels',
            'cost_price' => 800.00,
            'sell_price' => 1200.00,
            'image_path' => 'images/items/diskbrake front.jpg',
            'category' => 'Bodywork',
            'stock_quantity' => 20,
            'supplier_id' => 1,
            'type' => 'product',
        ]);

        Item::create([
            'title' => 'Impact Wrench',
            'description' => 'Electric impact wrench for rent',
            'cost_price' => 3000.00,
            'sell_price' => 150.00,
            'image_path' => 'images/items/impact wrench.jpg',
            'category' => 'Other',
            'stock_quantity' => 5,
            'supplier_id' => 2,
            'type' => 'tool',
        ]);

        Item::create([
            'title' => 'Car Battery',
            'description' => '12V 60Ah maintenance-free battery',
            'cost_price' => 3500.00,
            'sell_price' => 4500.00,
            'image_path' => 'images/items/car battery.jpg',
            'category' => 'Electrical',
            'stock_quantity' => 15,
            'supplier_id' => 1,
            'type' => 'product',
        ]);

        Item::create([
            'title' => 'Engine Oil 1L',
            'description' => 'Fully synthetic 5W-30 engine oil',
            'cost_price' => 400.00,
            'sell_price' => 550.00,
            'image_path' => 'images/items/shell advance.jpg',
            'category' => 'Consumables',
            'stock_quantity' => 100,
            'supplier_id' => 1,
            'type' => 'product',
        ]);
    }
}
