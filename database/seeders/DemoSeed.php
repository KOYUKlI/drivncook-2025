<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Franchise;
use App\Models\Warehouse;
use App\Models\Truck;
use App\Models\Supplier as Vendor;
use App\Models\Supply;
use App\Models\Dish;
use App\Models\DishIngredient;
use App\Models\CustomerOrder;
use App\Models\OrderItem;
use App\Models\LoyaltyCard;
use App\Models\MaintenanceRecord;
use App\Models\StockOrder;
use App\Models\StockOrderItem;
use App\Services\InventoryService;

class DemoSeed extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'password' => 'password', 'role' => 'admin']
        );

        // Franchises
        $paris = Franchise::firstOrCreate(['name' => 'Paris HQ']);
        $lyon  = Franchise::firstOrCreate(['name' => 'Lyon Branch']);

        // Franchise users
        User::firstOrCreate(
            ['email' => 'paris@example.com'],
            ['name' => 'Paris Manager', 'password' => 'password', 'role' => 'franchise', 'franchise_id' => $paris->id]
        );
        User::firstOrCreate(
            ['email' => 'lyon@example.com'],
            ['name' => 'Lyon Manager', 'password' => 'password', 'role' => 'franchise', 'franchise_id' => $lyon->id]
        );

        // Warehouses
        $pwh1 = Warehouse::firstOrCreate(['name' => 'Entrepôt Est', 'franchise_id' => $paris->id], ['location' => 'Paris 12']);
        $pwh2 = Warehouse::firstOrCreate(['name' => 'Entrepôt Ouest', 'franchise_id' => $paris->id], ['location' => 'Paris 16']);
        $lwh1 = Warehouse::firstOrCreate(['name' => 'Dépôt Centrale', 'franchise_id' => $lyon->id], ['location' => 'Lyon 3']);

        // Trucks
        $ptr1 = Truck::firstOrCreate(['license_plate' => 'AA-123-AA'], ['name' => 'Truck Paris 1', 'franchise_id' => $paris->id]);
        $ptr2 = Truck::firstOrCreate(['license_plate' => 'BB-456-BB'], ['name' => 'Truck Paris 2', 'franchise_id' => $paris->id]);
        $ltr1 = Truck::firstOrCreate(['license_plate' => 'CC-789-CC'], ['name' => 'Truck Lyon 1', 'franchise_id' => $lyon->id]);

        // Vendors (suppliers)
        $vend = Vendor::firstOrCreate(['name' => 'Metro Cash & Carry']);

        // Supplies (SKUs)
        $bun   = Supply::firstOrCreate(['name' => 'Bun Classic'],   ['sku' => 'BUN-001',  'unit' => 'pc', 'cost' => 0.40]);
        $beef  = Supply::firstOrCreate(['name' => 'Beef Patty 150g'], ['sku' => 'BEEF-150','unit' => 'kg', 'cost' => 9.50]);
        $chedd = Supply::firstOrCreate(['name' => 'Cheddar'],        ['sku' => 'CHED-001', 'unit' => 'kg', 'cost' => 7.80]);
        $lett  = Supply::firstOrCreate(['name' => 'Lettuce'],        ['sku' => 'LETT-001', 'unit' => 'kg', 'cost' => 3.20]);
        $tom   = Supply::firstOrCreate(['name' => 'Tomato'],         ['sku' => 'TOM-001',  'unit' => 'kg', 'cost' => 2.90]);

        // Dishes and BOM
        $burger = Dish::firstOrCreate(['name' => 'Classic Burger'], ['description' => 'Bun + Patty + Cheddar + Veggies', 'price' => 9.90]);
        DishIngredient::firstOrCreate(['dish_id' => $burger->id, 'supply_id' => $bun->id],   ['qty_per_dish' => 1,      'unit' => 'pc']);
        DishIngredient::firstOrCreate(['dish_id' => $burger->id, 'supply_id' => $beef->id],  ['qty_per_dish' => 0.15,   'unit' => 'kg']);
        DishIngredient::firstOrCreate(['dish_id' => $burger->id, 'supply_id' => $chedd->id], ['qty_per_dish' => 0.03,   'unit' => 'kg']);
        DishIngredient::firstOrCreate(['dish_id' => $burger->id, 'supply_id' => $lett->id],  ['qty_per_dish' => 0.02,   'unit' => 'kg']);
        DishIngredient::firstOrCreate(['dish_id' => $burger->id, 'supply_id' => $tom->id],   ['qty_per_dish' => 0.05,   'unit' => 'kg']);

        // Loyalty card
        $card = LoyaltyCard::firstOrCreate(['code' => 'CARD-'.Str::upper(Str::random(6))], ['points' => 0]);

        // Stock Orders: one pending, one completed (received into inventory)
        $pending = StockOrder::firstOrCreate([
            'truck_id' => $ptr1->id,
            'warehouse_id' => $pwh1->id,
            'status' => 'pending',
        ], ['ordered_at' => now()->subDays(1)]);
        StockOrderItem::firstOrCreate(['stock_order_id' => $pending->id, 'supply_id' => $bun->id],   ['quantity' => 200]);
        StockOrderItem::firstOrCreate(['stock_order_id' => $pending->id, 'supply_id' => $beef->id],  ['quantity' => 15]);

        $completed = StockOrder::firstOrCreate([
            'truck_id' => $ptr2->id,
            'warehouse_id' => $pwh2->id,
            'status' => 'completed',
        ], ['ordered_at' => now()->subDays(2)]);
        StockOrderItem::firstOrCreate(['stock_order_id' => $completed->id, 'supply_id' => $chedd->id], ['quantity' => 10]);
        StockOrderItem::firstOrCreate(['stock_order_id' => $completed->id, 'supply_id' => $lett->id],  ['quantity' => 5]);

        // Receive completed stock order into inventory
        try {
            app(InventoryService::class)->receiveStockOrder($completed);
        } catch (\Throwable $e) {
            // ignore in seed if service not fully wired; state remains coherent
        }

        // Maintenance records
        MaintenanceRecord::firstOrCreate(['truck_id' => $ptr1->id, 'description' => 'Oil change'],  ['maintenance_date' => now()->subDays(10), 'cost' => 79.90]);
        MaintenanceRecord::firstOrCreate(['truck_id' => $ptr2->id, 'description' => 'Brake pads'],  ['maintenance_date' => now()->subDays(20), 'cost' => 149.00]);

        // Customer orders with items
        $order = CustomerOrder::firstOrCreate(
            ['truck_id' => $ptr1->id, 'status' => 'completed', 'ordered_at' => now()->subDay()],
            ['loyalty_card_id' => $card->id, 'total_price' => 0, 'payment_status' => 'paid', 'order_type' => 'on_site']
        );
        OrderItem::firstOrCreate(
            ['customer_order_id' => $order->id, 'dish_id' => $burger->id],
            ['quantity' => 2, 'price' => 9.90]
        );
        $order->update(['total_price' => 2 * 9.90]);
    }
}
