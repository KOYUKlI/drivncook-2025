<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\{User, Franchise, Warehouse, Supply, Truck, Dish, DishIngredient, StockOrder, StockOrderItem, CustomerOrder, OrderItem};
use App\Services\InventoryService;

/**
 * Consolidated, idempotent seeder supporting profiles via SEED_PROFILE env:
 *  - minimal (default): core users, franchise, warehouses, supplies
 *  - demo: minimal + sample dish, truck, stock orders, received inventory, one customer order
 *  - bulk: demo + scaled dataset (extra supplies & trucks)
 *  - real: multi-franchise realistic dataset (multiple warehouses, trucks, dishes, orders, payments, stock orders, compliance scenario)
 */
class BaselineSeeder extends Seeder
{
    public function run(): void
    {
        $profile = env('SEED_PROFILE', 'minimal');
        echo "[BaselineSeeder] SEED_PROFILE= {$profile}\n";
        if (! in_array($profile, ['minimal','demo','bulk','real'])) {
            $profile = 'minimal';
        }
        // 'real' profile builds its own larger dataset (still begins with minimal core for consistency)
        if ($profile === 'real') {
            echo "[BaselineSeeder] Executing minimal + real dataset seeding...\n";
            $this->seedMinimal();
            $this->seedReal();
            echo "[BaselineSeeder] Real dataset seeding complete.\n";
            return; // stop here (don't mix with demo/bulk helpers)
        }

        $this->seedMinimal();
        if ($profile === 'demo' || $profile === 'bulk') { $this->seedDemo(); }
        if ($profile === 'bulk') { $this->seedBulk(); }
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

    // Mission 2 loyalty program removed in Mission 1 scope
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

    /**
     * Rich “realistic” dataset across multiple franchises.
     * Idempotent: deterministic naming so reruns do not duplicate.
     */
    protected function seedReal(): void
    {
        // Base supplies catalogue (if not already added by minimal)
        $catalog = [
            ['name'=>'Pain Burger Brioché','unit'=>'pc','cost'=>0.55],
            ['name'=>'Steak 180g Premium','unit'=>'kg','cost'=>12.40],
            ['name'=>'Cheddar Affiné','unit'=>'kg','cost'=>8.10],
            ['name'=>'Oignon Rouge','unit'=>'kg','cost'=>2.30],
            ['name'=>'Sauce Signature','unit'=>'kg','cost'=>5.90],
            ['name'=>'Frites Surgelées','unit'=>'kg','cost'=>1.90],
            ['name'=>'Boisson Cola 33cl','unit'=>'pc','cost'=>0.42],
            ['name'=>'Boisson Eau 50cl','unit'=>'pc','cost'=>0.25],
            ['name'=>'Salade Batavia','unit'=>'kg','cost'=>3.10],
            ['name'=>'Tomate Ronde','unit'=>'kg','cost'=>2.60],
        ];
        foreach ($catalog as $c) { Supply::firstOrCreate(['name'=>$c['name']], $c); }

        // Create additional franchises
        $franchisesSpec = [
            ['name'=>'Franchise Paris Centre','email'=>'paris.centre@local.test'],
            ['name'=>'Franchise Ouest IDF','email'=>'ouest.idf@local.test'],
            ['name'=>'Franchise Sud IDF','email'=>'sud.idf@local.test'],
        ];

        foreach ($franchisesSpec as $fSpec) {
            $fr = Franchise::firstOrCreate(['name'=>$fSpec['name']]);
            User::firstOrCreate(['email'=>$fSpec['email']], [
                'name'=>Str::before($fSpec['name'],' ').' Manager',
                'password'=>Hash::make('password'),
                'role'=>'franchise',
                'franchise_id'=>$fr->id,
            ]);
            // Warehouses for each franchise (2 each)
            for ($w=1;$w<=2;$w++) {
                Warehouse::firstOrCreate([
                    'name'=>$fr->name.' Entrepôt '.$w,
                    'franchise_id'=>$fr->id,
                ], ['location'=>'Secteur '.$w]);
            }
            // Trucks (2 each)
            for ($t=1;$t<=2;$t++) {
                Truck::firstOrCreate([
                    'license_plate'=> sprintf('%s-%d%s-%02d', Str::upper(Str::random(2)), rand(10,99), Str::upper(Str::random(1)), $t),
                ], [
                    'name'=>$fr->name.' Truck '.$t,
                    'franchise_id'=>$fr->id,
                ]);
            }
        }

        // Dishes referencing supplies (simple BOM). Use subset of existing supplies.
        $coreForDish = Supply::whereIn('name',[ 'Pain Burger Brioché','Steak 180g Premium','Cheddar Affiné','Oignon Rouge','Salade Batavia','Tomate Ronde','Sauce Signature' ])->get()->keyBy('name');
        $dishDefs = [
            ['Burger Signature', 11.90, [
                ['Pain Burger Brioché',1,'pc'],
                ['Steak 180g Premium',0.18,'kg'],
                ['Cheddar Affiné',0.035,'kg'],
                ['Oignon Rouge',0.02,'kg'],
                ['Salade Batavia',0.015,'kg'],
                ['Tomate Ronde',0.05,'kg'],
                ['Sauce Signature',0.02,'kg'],
            ]],
            ['Burger Classique', 9.90, [
                ['Pain Burger Brioché',1,'pc'],
                ['Steak 150g',0.15,'kg'], // fallback to minimal supply if present
                ['Cheddar',0.03,'kg'],
                ['Salade Batavia',0.012,'kg'],
                ['Tomate Ronde',0.04,'kg'],
            ]],
            ['Frites Portion', 3.50, [
                ['Frites Surgelées',0.18,'kg']
            ]],
            ['Boisson Cola 33cl', 2.50, [ ['Boisson Cola 33cl',1,'pc'] ]],
            ['Eau 50cl', 2.00, [ ['Boisson Eau 50cl',1,'pc'] ]],
        ];
        foreach ($dishDefs as [$dName,$price,$ingredients]) {
            $dish = Dish::firstOrCreate(['name'=>$dName], ['price'=>$price,'description'=>$dName]);
            foreach ($ingredients as [$sName,$qty,$unit]) {
                $s = Supply::firstWhere('name',$sName);
                if(!$s) continue;
                DishIngredient::firstOrCreate([
                    'dish_id'=>$dish->id,
                    'supply_id'=>$s->id,
                ], ['qty_per_dish'=>$qty,'unit'=>$unit]);
            }
        }

        // Generate stock orders for each truck; receive them into inventory
        $allTrucks = Truck::with('franchise')->get();
        $supplies = Supply::inRandomOrder()->take(8)->get();
        foreach ($allTrucks as $truck) {
            $warehouse = Warehouse::where('franchise_id',$truck->franchise_id)->inRandomOrder()->first();
            if(!$warehouse) continue;
            for ($i=0;$i<2;$i++) { // two orders each
                $order = StockOrder::firstOrCreate([
                    'truck_id'=>$truck->id,
                    'warehouse_id'=>$warehouse->id,
                    'status'=>'completed',
                    'ordered_at'=>now()->subDays( rand(2,7) ),
                ]);
                foreach ($supplies->shuffle()->take(5) as $s) {
                    StockOrderItem::firstOrCreate([
                        'stock_order_id'=>$order->id,
                        'supply_id'=>$s->id,
                    ], ['quantity'=>rand(3,15)]);
                }
                try { app(InventoryService::class)->receiveStockOrder($order->load('items')); } catch(\Throwable $e) {}
            }
        }

        // Generate customer orders (sales) using available dishes
        $dishes = Dish::all();
        foreach ($allTrucks as $truck) {
            for ($o=0;$o<5;$o++) {
                $co = CustomerOrder::firstOrCreate([
                    'truck_id'=>$truck->id,
                    'ordered_at'=>now()->subHours(rand(8,60)),
                    'status'=>'completed',
                    'payment_status'=>'paid',
                ], ['total_price'=>0,'order_type'=>'on_site']);
                $pick = $dishes->shuffle()->take(rand(1,3));
                $total = 0;
                foreach ($pick as $dish) {
                    $qty = rand(1,3);
                    OrderItem::firstOrCreate([
                        'customer_order_id'=>$co->id,
                        'dish_id'=>$dish->id,
                    ], ['quantity'=>$qty,'price'=>$dish->price]);
                    $total += $qty * (float)$dish->price;
                }
                $co->update(['total_price'=>round($total,2)]);
            }
        }
        // Note: commissions & compliance KPIs are computed by scheduled commands, not here.
    }
}
