<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Supplier;
use App\Models\Item;
use App\Models\Customer;
use App\Models\OrderInfo;
use App\Models\OrderLine;
use App\Models\Payment;
use App\Models\ProductSold;
use App\Models\Rental;
use App\Models\Expense;
use App\Models\ItemReview;
use App\Models\ItemImage;
use App\Models\Barcode;
use App\Models\Stock;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@brutor.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'fname' => 'Admin',
            'lname' => 'User',
            'avatar' => 'images/avatars/jerome.jpg',
            'email_verified_at' => now(),
            'status' => 'active',
        ]);

        // Customer user
        $customerUser = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'fname' => 'John',
            'lname' => 'Doe',
            'avatar' => 'images/avatars/jerome.jpg',
            'email_verified_at' => now(),
            'status' => 'active',
        ]);

        // Suppliers
        $supplier1 = Supplier::create([
            'name' => 'AutoParts Co.',
            'contact_email' => 'info@autoparts.com',
            'contact_phone' => '09171234567',
            'lead_time' => '3-5 days',
            'website' => 'https://autoparts.com',
        ]);

        $supplier2 = Supplier::create([
            'name' => 'ToolRent PH',
            'contact_email' => 'hello@toolrent.ph',
            'contact_phone' => '09181234567',
            'lead_time' => '1-2 days',
            'website' => 'https://toolrent.ph',
        ]);

        // Items
        $item1 = Item::create([
            'title' => 'Spark Plug',
            'description' => 'NGK Iridium spark plug for most engines',
            'cost_price' => 150.00,
            'sell_price' => 250.00,
            'image_path' => 'images/items/sparkplug.jpg',
            'category' => 'Engine',
            'stock_quantity' => 50,
            'supplier_id' => $supplier1->supplier_id,
            'type' => 'product',
        ]);

        $item2 = Item::create([
            'title' => 'Oil Filter',
            'description' => 'High-quality oil filter for sedans',
            'cost_price' => 200.00,
            'sell_price' => 350.00,
            'image_path' => 'images/items/oilfilter.jpg',
            'category' => 'Engine',
            'stock_quantity' => 30,
            'supplier_id' => $supplier1->supplier_id,
            'type' => 'product',
        ]);

        $item3 = Item::create([
            'title' => 'Brake Pad Set',
            'description' => 'Ceramic brake pads for front wheels',
            'cost_price' => 800.00,
            'sell_price' => 1200.00,
            'image_path' => 'images/items/diskbrake front.jpg',
            'category' => 'Bodywork',
            'stock_quantity' => 20,
            'supplier_id' => $supplier1->supplier_id,
            'type' => 'product',
        ]);

        $item4 = Item::create([
            'title' => 'Impact Wrench',
            'description' => 'Electric impact wrench for rent',
            'cost_price' => 3000.00,
            'sell_price' => 150.00,
            'image_path' => 'images/items/impact wrench.jpg',
            'category' => 'Other',
            'stock_quantity' => 5,
            'supplier_id' => $supplier2->supplier_id,
            'type' => 'tool',
        ]);

        // New Items Added
        Item::create([
            'title' => 'Akrapovič Slip-On Exhaust (Titanium)',
            'description' => 'Lightweight high-performance exhaust that improves throttle response and engine sound.',
            'cost_price' => 38500.00,
            'sell_price' => 46160.00,
            'image_path' => 'images/items/default.png',
            'category' => 'Engine',
            'stock_quantity' => 10,
            'supplier_id' => $supplier1->supplier_id,
            'type' => 'product',
        ]);

        Item::create([
            'title' => 'Alpinestars Tech-Air 5 Airbag Vest',
            'description' => 'An autonomous wearable airbag system that provides upper body protection during a crash.',
            'cost_price' => 39000.00,
            'sell_price' => 48500.00,
            'image_path' => 'images/items/default.png',
            'category' => 'Bodywork (Safety)',
            'stock_quantity' => 5,
            'supplier_id' => $supplier2->supplier_id,
            'type' => 'product',
        ]);

        Item::create([
            'title' => 'Yuasa YTZ6V Maintenance-Free Battery',
            'description' => 'High-performance AGM battery commonly used for NMAX, Click, and ADV models.',
            'cost_price' => 1650.00,
            'sell_price' => 2100.00,
            'image_path' => 'images/items/default.png',
            'category' => 'Electrical',
            'stock_quantity' => 25,
            'supplier_id' => $supplier1->supplier_id,
            'type' => 'product',
        ]);

        Item::create([
            'title' => 'Quad Lock Vibration Dampener',
            'description' => 'A specialized mount add-on that protects smartphone camera sensors from engine vibrations.',
            'cost_price' => 1150.00,
            'sell_price' => 1699.00,
            'image_path' => 'images/items/default.png',
            'category' => 'Other (Accessories)',
            'stock_quantity' => 50,
            'supplier_id' => $supplier2->supplier_id,
            'type' => 'product',
        ]);

        Item::create([
            'title' => 'Forcite MK1S Smart Helmet',
            'description' => 'Carbon fiber helmet with built-in 4K camera, navigation LEDs, and Harman Kardon audio.',
            'cost_price' => 51000.00,
            'sell_price' => 62500.00,
            'image_path' => 'images/items/default.png',
            'category' => 'Other (Safety)',
            'stock_quantity' => 3,
            'supplier_id' => $supplier2->supplier_id,
            'type' => 'product',
        ]);

        Item::create([
            'title' => 'Fluke 101 Digital Multimeter',
            'description' => 'Professional-grade handheld tool for diagnosing battery and electrical wiring issues.',
            'cost_price' => 2800.00,
            'sell_price' => 3840.00,
            'image_path' => 'images/items/default.png',
            'category' => 'Electrical',
            'stock_quantity' => 12,
            'supplier_id' => $supplier2->supplier_id,
            'type' => 'product',
        ]);

        Item::create([
            'title' => 'Knipex 86 03 180 Pliers Wrench',
            'description' => 'Replaces a full set of metric and imperial wrenches; smooth jaws prevent damage to chrome bolts.',
            'cost_price' => 2950.00,
            'sell_price' => 3730.00,
            'image_path' => 'images/items/default.png',
            'category' => 'Bodywork (Tools)',
            'stock_quantity' => 8,
            'supplier_id' => $supplier2->supplier_id,
            'type' => 'product',
        ]);

        Item::create([
            'title' => '1/2" Drive Click-Type Torque Wrench',
            'description' => 'Tool used to ensure axle nuts and engine bolts are tightened to exact manufacturer specs.',
            'cost_price' => 1200.00,
            'sell_price' => 1600.00, // Deposit
            'image_path' => 'images/items/default.png',
            'category' => 'Other (Maintenance)',
            'stock_quantity' => 5,
            'supplier_id' => $supplier2->supplier_id,
            'type' => 'tool',
        ]);

        Item::create([
            'title' => 'Motion Pro PBR Chain Tool',
            'description' => 'Specialty tool used to break, press, and rivet motorcycle drive chains.',
            'cost_price' => 5800.00,
            'sell_price' => 500.00, // Daily Rental
            'image_path' => 'images/items/default.png',
            'category' => 'Other (Maintenance)',
            'stock_quantity' => 2,
            'supplier_id' => $supplier2->supplier_id,
            'type' => 'tool',
        ]);

        Item::create([
            'title' => 'Spectro V-Twin Full Synthetic Oil Kit',
            'description' => 'Complete kit including engine oil, filter, and O-rings for a single service.',
            'cost_price' => 3400.00,
            'sell_price' => 4250.00,
            'image_path' => 'images/items/default.png',
            'category' => 'Consumables',
            'stock_quantity' => 15,
            'supplier_id' => $supplier1->supplier_id,
            'type' => 'product',
        ]);

        // Second Set of New Items
        Item::create([
            'title' => 'Yamaha Sniper 155 Throttle Body (34mm)',
            'description' => 'Performance upgrade to increase air intake, providing better acceleration and top-end power.',
            'cost_price' => 3100.00,
            'sell_price' => 3850.00,
            'image_path' => 'images/items/default.png',
            'category' => 'Engine',
            'stock_quantity' => 12,
            'supplier_id' => $supplier1->supplier_id,
            'type' => 'product',
        ]);

        Item::create([
            'title' => 'Firefly Mini Driving Light V2 (Dual Color)',
            'description' => 'Auxiliary LED lights with high-beam (white) and low-beam (yellow) for better visibility in rain or fog.',
            'cost_price' => 280.00,
            'sell_price' => 425.00,
            'image_path' => 'images/items/default.png',
            'category' => 'Electrical',
            'stock_quantity' => 40,
            'supplier_id' => $supplier1->supplier_id,
            'type' => 'product',
        ]);

        Item::create([
            'title' => 'RCB (Racing Boy) S1 Forged Brake Master Cylinder',
            'description' => 'High-precision brake lever and pump that provides a more responsive and "solid" braking feel.',
            'cost_price' => 2100.00,
            'sell_price' => 2850.00,
            'image_path' => 'images/items/default.png',
            'category' => 'Bodywork',
            'stock_quantity' => 8,
            'supplier_id' => $supplier1->supplier_id,
            'type' => 'product',
        ]);

        Item::create([
            'title' => 'Metzeler Roadtec Scooter Tire (110/70-13)',
            'description' => 'Premium wet-weather tire designed for high-grip performance on Philippine asphalt.',
            'cost_price' => 1100.00,
            'sell_price' => 2990.00,
            'image_path' => 'images/items/default.png',
            'category' => 'Consumables',
            'stock_quantity' => 20,
            'supplier_id' => $supplier1->supplier_id,
            'type' => 'product',
        ]);

        Item::create([
            'title' => 'RK Takasago Gold Chain & Sprocket Set (428 Series)',
            'description' => 'Heavy-duty drive chain set known for durability and reduced friction; a favorite for underbone bikes.',
            'cost_price' => 1800.00,
            'sell_price' => 2450.00,
            'image_path' => 'images/items/default.png',
            'category' => 'Consumables',
            'stock_quantity' => 15,
            'supplier_id' => $supplier1->supplier_id,
            'type' => 'product',
        ]);

        Item::create([
            'title' => 'PIAA OTO Style Horn (12V)',
            'description' => 'A loud, car-like deep tone horn upgrade to ensure you are heard by larger vehicles on the road.',
            'cost_price' => 210.00,
            'sell_price' => 325.00,
            'image_path' => 'images/items/default.png',
            'category' => 'Electrical',
            'stock_quantity' => 30,
            'supplier_id' => $supplier1->supplier_id,
            'type' => 'product',
        ]);

        Item::create([
            'title' => 'Oxford Aquatex Waterproof Motorcycle Cover',
            'description' => 'Double-stitched nylon cover that protects the bike from UV rays, rain, and dust during storage.',
            'cost_price' => 1050.00,
            'sell_price' => 1490.00,
            'image_path' => 'images/items/default.png',
            'category' => 'Other',
            'stock_quantity' => 10,
            'supplier_id' => $supplier2->supplier_id,
            'type' => 'product',
        ]);

        Item::create([
            'title' => 'Makita Brushless Impact Wrench (1/2" Drive)',
            'description' => 'High-torque power tool used for quickly removing stubborn axle nuts or CVT bolts.',
            'cost_price' => 1650.00,
            'sell_price' => 250.00, // Daily Rental
            'image_path' => 'images/items/default.png',
            'category' => 'Other (Tools)',
            'stock_quantity' => 4,
            'supplier_id' => $supplier2->supplier_id,
            'type' => 'tool',
        ]);

        Item::create([
            'title' => 'Flyman 46-Piece Socket Wrench Set',
            'description' => 'A comprehensive set of sockets and driver bits used for almost all general motorcycle repairs.',
            'cost_price' => 850.00,
            'sell_price' => 1150.00,
            'image_path' => 'images/items/default.png',
            'category' => 'Other (Tools)',
            'stock_quantity' => 20,
            'supplier_id' => $supplier2->supplier_id,
            'type' => 'product',
        ]);

        Item::create([
            'title' => '12V Portable Jump Starter (98800mAh)',
            'description' => 'A compact power bank capable of jump-starting a motorcycle with a dead battery in seconds.',
            'cost_price' => 820.00,
            'sell_price' => 1098.00,
            'image_path' => 'images/items/default.png',
            'category' => 'Electrical',
            'stock_quantity' => 15,
            'supplier_id' => $supplier2->supplier_id,
            'type' => 'product',
        ]);

        // Customers
        $customer1 = Customer::create([
            'title' => 'Mr.',
            'fname' => 'Alice',
            'lname' => 'Wonderland',
            'addressline' => '123 Rabbit Hole',
            'town' => 'London',
            'zipcode' => '12345',
            'phone' => '09123456789',
        ]);

        // Orders
        $order1 = OrderInfo::create([
            'customer_id' => $customer1->customer_id,
            'user_id' => $customerUser->id,
            'date_placed' => now(),
            'date_shipped' => now()->addDays(2),
            'shipping' => 50.00,
            'status' => 'Delivered',
        ]);

        // Order Lines
        OrderLine::create([
            'orderinfo_id' => $order1->orderinfo_id,
            'product_id' => $item1->item_id,
            'quantity' => 2,
            'rate' => $item1->sell_price,
        ]);

        // Payments
        Payment::create([
            'transaction_id' => $order1->orderinfo_id,
            'payment_type' => 'Credit Card',
            'amount' => ($item1->sell_price * 2) + 50.00,
            'payment_date' => now(),
        ]);

        // Products Sold
        ProductSold::create([
            'transaction_id' => $order1->orderinfo_id,
            'product_id' => $item1->item_id,
            'quantity' => 2,
            'rate_charged' => $item1->sell_price,
        ]);

        // Rentals
        Rental::create([
            'transaction_id' => $order1->orderinfo_id,
            'customer_id' => $customerUser->id,
            'item_id' => $item4->item_id,
            'start_date' => now(),
            'due_date' => now()->addDays(1),
            'rate_charged' => 150.00,
            'quantity' => 1,
        ]);

        // Expenses
        Expense::create([
            'title' => 'Electricity Bill',
            'amount' => 1500.00,
            'expense_date' => now(),
            'notes' => 'Utilities for the month',
        ]);

        // Item Reviews
        ItemReview::create([
            'item_id' => $item1->item_id,
            'user_id' => $customerUser->id,
            'rating' => 5,
            'comment' => 'Excellent quality!',
        ]);

        // Item Images
        ItemImage::create([
            'item_id' => $item1->item_id,
            'image_path' => 'images/items/sparkplug.jpg',
            'is_primary' => true,
        ]);

        // Barcodes
        Barcode::create([
            'item_id' => $item1->item_id,
            'barcode_ean' => '1234567890123',
        ]);

        // Stock
        Stock::create([
            'item_id' => $item1->item_id,
            'quantity' => 50,
        ]);
    }
}
