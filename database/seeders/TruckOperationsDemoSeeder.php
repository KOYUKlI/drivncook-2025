<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class TruckOperationsDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch demo trucks and franchisee
        $trucks = DB::table('trucks')->select('id', 'plate', 'franchisee_id')->get();
        if ($trucks->isEmpty()) {
            $this->command?->warn('No trucks found, skipping TruckOperationsDemoSeeder.');
            return;
        }

        // Light idempotency and schema guard for deployments
        if (! Schema::hasTable('truck_deployments')) {
            $this->command?->warn('Table truck_deployments not found. Skipping deployment seeding.');
        } else {
            $firstTruckId = $trucks->first()->id;
            $existing = DB::table('truck_deployments')->where('truck_id', $firstTruckId)->count();
            if ($existing > 0) {
                $this->command?->line('Truck deployments already present, skipping creation.');
            } else {
                $this->seedDeployments($trucks);
            }
        }

        // Maintenance logs: seed only if none exist yet
        if (! Schema::hasTable('maintenance_logs')) {
            $this->command?->warn('Table maintenance_logs not found. Skipping maintenance seeding.');
        } else {
            $existingMaint = DB::table('maintenance_logs')->count();
            if ($existingMaint === 0) {
                $this->seedMaintenance($trucks);
            } else {
                $this->command?->line('Maintenance logs already present, skipping creation.');
            }
        }

        // Enrich trucks with demo document paths so the UI shows documents available
        DB::table('trucks')->update([
            'registration_doc_path' => DB::raw("COALESCE(registration_doc_path, 'private/docs/registration.pdf')"),
            'insurance_doc_path' => DB::raw("COALESCE(insurance_doc_path, 'private/docs/insurance.pdf')"),
            'updated_at' => now(),
        ]);

        // Ensure placeholder documents exist for downloads
        $disk = Storage::disk('local');
        foreach (['private/docs/registration.pdf' => 'Fichier de démonstration: Immatriculation', 'private/docs/insurance.pdf' => 'Fichier de démonstration: Assurance'] as $path => $content) {
            if (! $disk->exists($path)) {
                $disk->put($path, $content);
            }
        }
    }

    private function seedDeployments($trucks): void
    {
        $locations = [
            'Paris - Marché d\'Aligre',
            'Nanterre - Parc André Malraux',
            'Créteil - Lac de Créteil',
            'Saint-Denis - Stade de France',
            'Paris - La Défense Esplanade',
            'Ivry - Centre Commercial',
        ];

        foreach ($trucks as $idx => $truck) {
            // Past 30 days + next 7 days demo
            for ($d = 20; $d >= -7; $d--) { // 20 days back to 7 days ahead
                // 60% chance to create a deployment for a given day
                if (mt_rand(1, 100) > 60) { continue; }

                $date = now()->subDays($d)->startOfDay();
                $startHour = [9, 10, 11, 12, 13, 14, 15, 16][array_rand([9, 10, 11, 12, 13, 14, 15, 16])];
                $durationHours = [6, 7, 8, 9, 10][array_rand([6,7,8,9,10])];

                $plannedStart = $date->copy()->setTime($startHour, 0);
                $plannedEnd = $plannedStart->copy()->addHours($durationHours);

                // Status logic: future -> planned, past -> mostly closed, some cancelled, present -> open
                if ($plannedStart->isFuture()) {
                    $status = 'planned';
                } elseif ($plannedEnd->isPast()) {
                    $status = (mt_rand(1, 100) <= 15) ? 'cancelled' : 'closed';
                } else {
                    $status = 'open';
                }

                $actualStart = null;
                $actualEnd = null;
                if ($status === 'closed') {
                    // Actuals around planned (±30 min)
                    $actualStart = $plannedStart->copy()->addMinutes(mt_rand(-30, 30));
                    $actualEnd = $plannedEnd->copy()->addMinutes(mt_rand(-30, 30));
                } elseif ($status === 'open') {
                    $actualStart = now()->subHours(mt_rand(1, 3));
                }

                DB::table('truck_deployments')->insert([
                    'id' => (string) Str::ulid(),
                    'truck_id' => $truck->id,
                    'franchisee_id' => $truck->franchisee_id,
                    'location_text' => $locations[array_rand($locations)],
                    'planned_start_at' => $plannedStart,
                    'planned_end_at' => $plannedEnd,
                    'actual_start_at' => $actualStart,
                    'actual_end_at' => $actualEnd,
                    'status' => $status,
                    'notes' => $status === 'cancelled' ? 'Annulé par le client (météo).' : (mt_rand(1,100) <= 30 ? 'Prévoir rallonge électrique.' : null),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function seedMaintenance($trucks): void
    {
        foreach ($trucks as $truck) {
            // Closed corrective maintenance ~10 days ago
            DB::table('maintenance_logs')->insert([
                'id' => (string) Str::ulid(),
                'truck_id' => $truck->id,
                'kind' => 'Corrective',
                'description' => 'Remplacement alternateur',
                'started_at' => now()->subDays(12),
                'closed_at' => now()->subDays(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Open preventive maintenance started recently
            DB::table('maintenance_logs')->insert([
                'id' => (string) Str::ulid(),
                'truck_id' => $truck->id,
                'kind' => 'Preventive',
                'description' => 'Vidange et contrôle général',
                'started_at' => now()->subDays(2),
                'closed_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
