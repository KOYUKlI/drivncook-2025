<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\{User, Franchise, Truck, Warehouse, Supply, Supplier, Dish, DishTranslation, Location, CustomerOrder, OrderItem, LoyaltyCard, LoyaltyRule, Commission, Event, EventRegistration, Newsletter};

class DeltaBaselineSeeder extends Seeder
{
    public function run(): void
    {
        $franchise = Franchise::first() ?? Franchise::create(['name' => 'Demo Franchise']);

        $admin = User::firstOrCreate(['email'=>'admin@example.test'], [
            'name' => 'Admin', 'password'=>Hash::make('password'),'role'=>'admin'
        ]);
        $frUser = User::firstOrCreate(['email'=>'franchise@example.test'], [
            'name'=>'Fr User','password'=>Hash::make('password'),'role'=>'franchise','franchise_id'=>$franchise->id
        ]);
        $customer = User::firstOrCreate(['email'=>'customer@example.test'], [
            'name'=>'Client','password'=>Hash::make('password'),'role'=>'customer','preferred_language'=>'fr'
        ]);

        $truck = Truck::firstOrCreate(['license_plate'=>'AA-123-AA'], [
            'name'=>'Truck Demo', 'franchise_id'=>$franchise->id, 'status'=>'active'
        ]);

        foreach (['Nord','Sud','Est','Ouest'] as $w) {
            Warehouse::firstOrCreate(['name'=>'Entrepôt '.$w,'franchise_id'=>$franchise->id], [
                'location'=>null
            ]);
        }

        $supplies = collect([
            ['name'=>'Tomate','unit'=>'kg','cost'=>2.5],
            ['name'=>'Fromage','unit'=>'kg','cost'=>6.0],
            ['name'=>'Pain','unit'=>'pc','cost'=>0.4],
            ['name'=>'Boisson','unit'=>'pc','cost'=>0.6],
            ['name'=>'Salade','unit'=>'kg','cost'=>3.2],
        ])->map(fn($d)=> Supply::firstOrCreate(['name'=>$d['name']], $d));

        Supplier::firstOrCreate(['name'=>'Fournisseur Ext'], ['siret'=>'SIRET'.Str::random(6)]);

        $dish = Dish::firstOrCreate(['name'=>'Burger Demo'], ['price'=>9.90,'is_active'=>true]);
        DishTranslation::firstOrCreate(['dish_id'=>$dish->id,'locale'=>'en'], ['name'=>'Demo Burger EN']);
        DishTranslation::firstOrCreate(['dish_id'=>$dish->id,'locale'=>'fr'], ['name'=>'Burger Démo FR']);

        $location = Location::firstOrCreate(['label'=>'Centre Ville']);

        $order = CustomerOrder::firstOrCreate(['reference'=>'ORD-DEMO-1'], [
            'truck_id'=>$truck->id,
            'customer_id'=>$customer->id,
            'total_price'=>9.90,
            'status'=>'completed',
            'payment_status'=>'paid',
            'ordered_at'=>now()
        ]);
        OrderItem::firstOrCreate(['customer_order_id'=>$order->id,'dish_id'=>$dish->id], [
            'quantity'=>1,'price'=>9.90
        ]);

    $card = LoyaltyCard::firstOrCreate(['code'=>'CARD-DEMO'], ['points'=>100,'user_id'=>$customer->id]);
        LoyaltyRule::firstOrCreate(['active'=>true], ['points_per_euro'=>1,'redeem_rate'=>100]);

        Commission::firstOrCreate([
            'franchisee_id'=>$frUser->id,'period_year'=>now()->year,'period_month'=>now()->month
        ], ['turnover'=>1000,'rate'=>4.0,'status'=>'pending','calculated_at'=>now()]);

        $event = Event::firstOrCreate(['title'=>'Fête Food'], ['starts_at'=>now()->addWeek()]);
        EventRegistration::firstOrCreate(['event_id'=>$event->id,'user_id'=>$frUser->id]);

        Newsletter::firstOrCreate(['subject'=>'Bienvenue'], ['body'=>'Newsletter de bienvenue','sent_at'=>now()]);
    }
}
