<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\Franchise;
use App\Models\Warehouse;
use App\Models\Truck;
use App\Models\Supplier;
use App\Models\Supply;
use App\Models\Dish;
use App\Models\DishIngredient;
use App\Models\Location;
use App\Models\TruckDeployment;
use App\Models\MaintenanceRecord;
use App\Models\StockOrder;
use App\Models\StockOrderItem;
use App\Models\CustomerOrder;
use App\Models\OrderItem;
use App\Services\InventoryService;

class BulkSeed extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('fr_FR');

        // Admin user (idempotent)
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'password' => 'password', 'role' => 'admin']
        );

        // Franchises
        $franchiseCount = 5;
        $franchises = collect();
        for ($i=1; $i <= $franchiseCount; $i++) {
            $name = 'Franchise '.Str::upper(Str::random(3));
            $franchises->push(Franchise::firstOrCreate(['name' => $name]));
        }

        // Users per franchise
        foreach ($franchises as $f) {
            for ($u=1; $u<=3; $u++) {
                User::firstOrCreate(
                    ['email' => Str::lower(str_replace(' ', '', $f->name)).'_'.$u.'@example.com'],
                    ['name' => $f->name.' Manager '.$u, 'password' => 'password', 'role' => 'franchise', 'franchise_id' => $f->id]
                );
            }
        }

        // Warehouses per franchise
        $warehouses = collect();
        foreach ($franchises as $f) {
            for ($w=1; $w<=2; $w++) {
                $warehouses->push( Warehouse::firstOrCreate([
                    'name' => $f->name.' Depot '.$w,
                    'franchise_id' => $f->id
                ], ['location' => $faker->city]) );
            }
        }

        // Trucks per franchise
        $trucks = collect();
        foreach ($franchises as $f) {
            for ($t=1; $t<=3; $t++) {
                $plate = strtoupper($faker->bothify('??-###-??'));
                $trucks->push( Truck::firstOrCreate(['license_plate' => $plate], ['name' => $f->name.' Truck '.$t, 'franchise_id' => $f->id]) );
            }
        }

        // Suppliers
        $suppliers = collect();
        for ($s=1; $s<=10; $s++) {
            $suppliers->push( Supplier::firstOrCreate(['name' => 'Vendor '.$s]) );
        }

        // Supplies
        $units = ['pc','kg','l'];
        $supplies = collect();
        for ($i=1; $i<=100; $i++) {
            $name = 'Supply '.Str::upper(Str::random(5));
            $sku  = 'SKU-'.Str::upper(Str::random(6));
            $supplies->push( Supply::firstOrCreate(['name' => $name], [
                'sku' => $sku,
                'unit' => $faker->randomElement($units),
                'cost' => $faker->randomFloat(2, 0.2, 25.0),
            ]) );
        }

        // Dishes + ingredients
        $dishes = collect();
        for ($d=1; $d<=20; $d++) {
            $dish = Dish::firstOrCreate(['name' => 'Dish '.$d], ['description' => $faker->sentence(), 'price' => $faker->randomFloat(2, 5, 25)]);
            $dishes->push($dish);
            $ingredientCount = rand(3,6);
            $picked = $supplies->shuffle()->take($ingredientCount);
            foreach ($picked as $supply) {
                DishIngredient::firstOrCreate(['dish_id' => $dish->id, 'supply_id' => $supply->id], [
                    'qty_per_dish' => $faker->randomFloat(3, 0.01, 1.5),
                    'unit' => $supply->unit ?? 'pc',
                ]);
            }
        }

        // Locations
        $locations = collect();
        for ($i=1; $i<=50; $i++) {
            $locations->push( Location::firstOrCreate(['label' => 'Spot '.$i], [
                'address' => $faker->streetAddress(),
                'city' => $faker->city(),
                'postal_code' => $faker->postcode(),
                'lat' => $faker->randomFloat(6, 43.0, 50.0),
                'lng' => $faker->randomFloat(6, 0.0, 7.0),
            ]) );
        }

        // Deployments per truck
        foreach ($trucks as $truck) {
            for ($k=0; $k<10; $k++) {
                $start = now()->subDays(rand(1,120))->setTime(rand(8,11), 0);
                $end = (clone $start)->addHours(rand(3,8));
                TruckDeployment::firstOrCreate([
                    'truck_id' => $truck->id,
                    'location_id' => $locations->random()->id,
                    'starts_at' => $start,
                ], ['ends_at' => $end]);
            }
        }

        // Stock Orders per truck
        foreach ($trucks as $truck) {
            for ($o=0; $o<10; $o++) {
                $status = $o % 2 === 0 ? 'completed' : 'pending';
                $wh = $warehouses->where('franchise_id', $truck->franchise_id)->random();
                $order = StockOrder::firstOrCreate([
                    'truck_id' => $truck->id,
                    'warehouse_id' => $wh->id,
                    'status' => $status,
                ], ['ordered_at' => now()->subDays(rand(1,60))]);
                $items = $supplies->shuffle()->take(rand(3,6));
                foreach ($items as $sp) {
                    StockOrderItem::firstOrCreate([
                        'stock_order_id' => $order->id,
                        'supply_id' => $sp->id
                    ], ['quantity' => rand(1,30)]);
                }
                if ($status === 'completed') {
                    try { app(InventoryService::class)->receiveStockOrder($order); } catch (\Throwable $e) {}
                }
            }
        }

        // Maintenance per truck
        foreach ($trucks as $truck) {
            for ($m=0; $m<5; $m++) {
                MaintenanceRecord::firstOrCreate([
                    'truck_id' => $truck->id,
                    'description' => 'Service '.Str::upper(Str::random(4))
                ], ['maintenance_date' => now()->subDays(rand(10,180)), 'cost' => $faker->randomFloat(2, 40, 400)]);
            }
        }

        // Customer orders per truck
        foreach ($trucks as $truck) {
            for ($c=0; $c<50; $c++) {
                $order = CustomerOrder::create([
                    'truck_id' => $truck->id,
                    'status' => 'completed',
                    'payment_status' => 'paid',
                    'ordered_at' => now()->subDays(rand(0,90))->subMinutes(rand(0, 1440)),
                    'total_price' => 0,
                ]);
                $lineCount = rand(1,4);
                $pickedDishes = $dishes->shuffle()->take($lineCount);
                $total = 0;
                foreach ($pickedDishes as $dish) {
                    $qty = rand(1,3);
                    OrderItem::create([
                        'customer_order_id' => $order->id,
                        'dish_id' => $dish->id,
                        'quantity' => $qty,
                        'price' => $dish->price,
                    ]);
                    $total += $qty * (float) $dish->price;
                }
                $order->update(['total_price' => round($total, 2)]);
            }
        }
    }
}
