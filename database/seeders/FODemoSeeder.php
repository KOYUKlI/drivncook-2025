<?php

namespace Database\Seeders;

use App\Models\Franchisee;
use App\Models\ReportPdf;
use App\Models\Sale;
use App\Models\SaleLine;
use App\Models\StockItem;
use App\Models\Truck;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FODemoSeeder extends Seeder
{
    /**
     * Run the FO demo data seeder.
     * Creates a demo franchisee user for easy FO testing with pre-populated data.
     */
    public function run(): void
    {
        // Create or get the demo franchisee
        $franchisee = Franchisee::firstOrCreate(
            ['email' => 'demo@drivncook.test'],
            [
                'id' => (string) Str::ulid(),
                'name' => 'Demo Franchisee',
                'phone' => '+33 6 00 00 00 00',
                'billing_address' => '1 Avenue Demo, 75001 Paris',
                'royalty_rate' => 0.0400,
            ]
        );

        // Create or get the demo user with franchisee role
        $user = User::firstOrCreate(
            ['email' => 'demo@drivncook.test'],
            [
                'name' => 'Demo FO',
                'password' => Hash::make('demodemo'),
                'email_verified_at' => now(),
                'franchisee_id' => $franchisee->id,
                'phone' => '+33 6 00 00 00 00',
                'notification_email_optin' => true,
                'locale' => 'fr',
            ]
        );

        // Ensure user has franchisee role
        if (!$user->hasRole('franchisee')) {
            $user->assignRole('franchisee');
        }

        // Create or get a truck assigned to the demo franchisee
        $truck = Truck::firstOrCreate(
            ['code' => 'TRK-DEMO'],
            [
                'id' => (string) Str::ulid(),
                'name' => 'Truck Demo',
                'plate' => 'DM-123-FO',
                'vin' => 'VF1DEMODEMO123456',
                'make' => 'Renault',
                'model' => 'Master',
                'year' => (int) date('Y'),
                'status' => 'Active',
                'acquired_at' => now('UTC')->subMonths(3)->startOfDay(),
                'service_start' => now('UTC')->subMonths(2)->startOfDay(),
                'mileage_km' => 15000,
                'franchisee_id' => $franchisee->id,
                'registration_doc_path' => 'private/docs/demo_reg.pdf',
                'insurance_doc_path' => 'private/docs/demo_ins.pdf',
            ]
        );

        // Create recent sales data for the demo franchisee
        $items = StockItem::where('is_active', true)->get();
        if ($items->isEmpty()) {
            $this->command->warn('No active stock items found. Sales cannot be created.');
            return;
        }

        // Create 10 recent sales for demo
        for ($d = 10; $d >= 1; $d--) {
            $date = now('UTC')->subDays($d)->format('Y-m-d');
            $sale = Sale::firstOrCreate(
                ['franchisee_id' => $franchisee->id, 'sale_date' => $date],
                ['id' => (string) Str::ulid(), 'total_cents' => 0]
            );

            // Clear existing lines for idempotent seeding
            if ($sale->wasRecentlyCreated === false) {
                SaleLine::where('sale_id', $sale->id)->delete();
                $sale->total_cents = 0;
                $sale->save();
            }

            $linesCount = random_int(2, 5);
            $selected = $items->random($linesCount);
            $total = 0;

            foreach ($selected as $item) {
                $qty = random_int(1, 10);
                $price = $item->price_cents;
                $lineTotal = $qty * $price;
                
                SaleLine::create([
                    'id' => (string) Str::ulid(),
                    'sale_id' => $sale->id,
                    'stock_item_id' => $item->id,
                    'qty' => $qty,
                    'unit_price_cents' => $price,
                ]);
                
                $total += $lineTotal;
            }

            $sale->forceFill(['total_cents' => $total])->save();
        }

        // Create monthly sales reports for demo
        $year = (int) now('UTC')->year;
        $month = now('UTC')->subMonth()->month;
        
        // Create previous month report
        $reportPath = "reports/{$year}" . sprintf('%02d', $month) . "/demo_{$franchisee->id}.pdf";
        
        // Create placeholder file if it doesn't exist
        if (!Storage::disk('public')->exists($reportPath)) {
            Storage::disk('public')->put($reportPath, 'PDF PLACEHOLDER');
        }
        
        ReportPdf::firstOrCreate(
            [
                'franchisee_id' => $franchisee->id,
                'type' => 'monthly_sales',
                'year' => $year,
                'month' => $month,
            ],
            [
                'id' => (string) Str::ulid(),
                'storage_path' => $reportPath,
                'generated_at' => now('UTC')->subDays(5),
            ]
        );
        
        // Create two months ago report
        $month2 = now('UTC')->subMonths(2)->month;
        $reportPath2 = "reports/{$year}" . sprintf('%02d', $month2) . "/demo_{$franchisee->id}.pdf";
        
        if (!Storage::disk('public')->exists($reportPath2)) {
            Storage::disk('public')->put($reportPath2, 'PDF PLACEHOLDER');
        }
        
        ReportPdf::firstOrCreate(
            [
                'franchisee_id' => $franchisee->id,
                'type' => 'monthly_sales',
                'year' => $year,
                'month' => $month2,
            ],
            [
                'id' => (string) Str::ulid(),
                'storage_path' => $reportPath2,
                'generated_at' => now('UTC')->subDays(35),
            ]
        );

        $this->command->info('FO Demo data created successfully.');
        $this->command->info('Demo FO access:');
        $this->command->info('- URL: ' . url('/fo/dashboard'));
        $this->command->info('- Email: demo@drivncook.test');
        $this->command->info('- Password: demodemo');
    }
}
