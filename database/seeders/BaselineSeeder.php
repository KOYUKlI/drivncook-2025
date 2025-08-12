<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\{User, Franchise, Warehouse, Supply, LoyaltyRule, Truck, Dish, DishIngredient, StockOrder, StockOrderItem, CustomerOrder, OrderItem};
use App\Services\InventoryService;

/**
 * Consolidated, idempotent seeder supporting profiles via SEED_PROFILE env:
 *  - minimal (default): core users, franchise, warehouses, supplies, loyalty rule
 *  - demo: minimal + sample dish, truck, stock orders, received inventory, one customer order
 *  - bulk: demo + scaled dataset (extra supplies & trucks)
 */
class BaselineSeeder extends Seeder
{
    public function run(): void
    {
        $profile = env('SEED_PROFILE', 'minimal');
        if (! in_array($profile, ['minimal','demo','bulk'])) {
            $profile = 'minimal';
        }

        $this->seedMinimal();
        if ($profile === 'demo' || $profile === 'bulk') {
            $this->seedDemo();
        }
        if ($profile === 'bulk') {
            $this->seedBulk();
        }
    }

    protected function seedMinimal(): void
    {
        $admin = User::firstOrCreate(['email' => 'admin@local.test'], [
            'name' => 'Admin Demo',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $franchise = Franchise::firstOrCreate(['name' => 'Demo Franchise']);
        User::firstOrCreate(['email' => 'franchise@local.test'], [
            'name' => 'Franchise Demo',
            'password' => Hash::make('password'),
            'role' => 'franchise',
            'franchise_id' => $franchise->id,
        ]);

        Warehouse::firstOrCreate(['name' => 'Entrepôt Est', 'franchise_id' => $franchise->id], ['location' => 'Zone Est']);
        Warehouse::firstOrCreate(['name' => 'Entrepôt Ouest', 'franchise_id' => $franchise->id], ['location' => 'Zone Ouest']);

        $coreSupplies = [
            ['name' => 'Pain burger', 'unit' => 'pc', 'cost' => 0.40],
            ['name' => 'Steak 150g',  'unit' => 'kg', 'cost' => 9.50],
            ['name' => 'Cheddar',     'unit' => 'kg', 'cost' => 7.80],
        ];
        foreach ($coreSupplies as $s) {
            Supply::firstOrCreate(['name' => $s['name']], $s);
        }

        LoyaltyRule::firstOrCreate(['active' => true], [
            'points_per_euro' => 1.0,
            'redeem_rate' => 100.0,
            'expires_after_months' => null,
        ]);
    }

    protected function seedDemo(): void
    {
        $franchise = Franchise::firstWhere('name','Demo Franchise');
        if (! $franchise) { return; }

        $truck = Truck::firstOrCreate(['license_plate' => 'AA-123-AA'], [
            'name' => 'Truck Demo 1',
            'franchise_id' => $franchise->id,
        ]);

        $dish = Dish::firstOrCreate(['name' => 'Burger Demo'], [
            'description' => 'Pain + Steak + Cheddar',
            'price' => 9.90,
        ]);
        $map = Supply::whereIn('name',['Pain burger','Steak 150g','Cheddar'])->get()->keyBy('name');
        $qtyMap = [
            'Pain burger' => 1,
            'Steak 150g' => 0.15,
            'Cheddar' => 0.03,
        ];
        foreach ($qtyMap as $sName => $qty) {
            if (!isset($map[$sName])) continue;
            DishIngredient::firstOrCreate([
                'dish_id' => $dish->id,
                'supply_id' => $map[$sName]->id,
            ], ['qty_per_dish' => $qty, 'unit' => $map[$sName]->unit]);
        }

        $warehouse = Warehouse::firstWhere('franchise_id', $franchise->id);
        if ($warehouse) {
            $order = StockOrder::firstOrCreate([
                'truck_id' => $truck->id,
                'warehouse_id' => $warehouse->id,
                'status' => 'completed',
            ], ['ordered_at' => now()->subDay()]);
            foreach ($map as $supply) {
                StockOrderItem::firstOrCreate([
                    'stock_order_id' => $order->id,
                    'supply_id' => $supply->id,
                ], ['quantity' => 5]);
            }
            try { app(InventoryService::class)->receiveStockOrder($order->load('items')); } catch (\Throwable $e) {}
        }

        $order = CustomerOrder::firstOrCreate([
            'truck_id' => $truck->id,
            'status' => 'completed',
            'payment_status' => 'paid',
            'ordered_at' => now()->subHours(6),
        ], [ 'total_price' => 0, 'order_type' => 'on_site' ]);
        OrderItem::firstOrCreate([
            'customer_order_id' => $order->id,
            'dish_id' => $dish->id,
        ], ['quantity' => 2, 'price' => 9.90]);
        $order->update(['total_price' => 2 * 9.90]);
    }

    protected function seedBulk(): void
    {
        $supplyCount = Supply::count();
        if ($supplyCount < 60) {
            for ($i=$supplyCount; $i<60; $i++) {
                $name = 'BulkSupply '.Str::upper(Str::random(4));
                Supply::firstOrCreate(['name' => $name], [
                    'unit' => 'pc',
                    'cost' => mt_rand(20,200)/100,
                ]);
            }
        }

        $franchise = Franchise::firstWhere('name','Demo Franchise');
        if ($franchise && Truck::where('franchise_id',$franchise->id)->count() < 3) {
            while (Truck::where('franchise_id',$franchise->id)->count() < 3) {
                Truck::firstOrCreate(['license_plate' => strtoupper(Str::random(2)).'-'.rand(100,999).'-'.strtoupper(Str::random(2))], [
                    'name' => 'Truck Bulk '.Str::upper(Str::random(3)),
                    'franchise_id' => $franchise->id,
                ]);
            }
        }
    }
}
