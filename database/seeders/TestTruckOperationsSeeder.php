<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class TestTruckOperationsSeeder extends Seeder
{
    public function run(): void
    {
        // Create additional test trucks with diverse statuses
        $this->createTestTrucks();
        
        // Fetch all trucks including our new test trucks
        $trucks = DB::table('trucks')->select('id', 'plate', 'franchisee_id', 'status')->get();
        if ($trucks->isEmpty()) {
            $this->command?->warn('No trucks found, skipping TestTruckOperationsSeeder.');
            return;
        }

        // Create more diverse deployment scenarios for testing
        if (Schema::hasTable('truck_deployments')) {
            $this->seedTestDeployments($trucks);
        } else {
            $this->command?->warn('Table truck_deployments not found. Skipping deployment seeding.');
        }

        // Create more diverse maintenance scenarios
        if (Schema::hasTable('maintenance_logs')) {
            $this->seedTestMaintenance($trucks);
        } else {
            $this->command?->warn('Table maintenance_logs not found. Skipping maintenance seeding.');
        }

        // Ensure all trucks have documents for testing
        $this->ensureTestDocuments($trucks);
    }

    private function createTestTrucks(): void
    {
        // Get franchisee IDs for assignment
        $franchiseeIds = DB::table('franchisees')->pluck('id')->toArray();
        
        if (empty($franchiseeIds)) {
            $this->command?->warn('No franchisees found, skipping test truck creation.');
            return;
        }

        // Check if our test trucks exist
        $existingTrucks = DB::table('trucks')
            ->whereIn('plate', ['TEST-001', 'TEST-002', 'TEST-003', 'TEST-004'])
            ->count();

        if ($existingTrucks > 0) {
            $this->command?->line('Test trucks already exist, skipping creation.');
            return;
        }

        // Create trucks with different statuses for testing
        $testTrucks = [
            [
                'id' => (string) Str::ulid(),
                'code' => 'TRK-TEST1',
                'plate' => 'TEST-001',
                'vin' => 'VINTEST00000001',
                'make' => 'Renault',
                'model' => 'Master',
                'year' => 2023,
                'status' => 'Active',
                'mileage_km' => 15000,
                'franchisee_id' => $franchiseeIds[0],
                'acquired_at' => now()->subMonths(6)->toDateString(),
                'service_start' => now()->subMonths(5)->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::ulid(),
                'code' => 'TRK-TEST2',
                'plate' => 'TEST-002',
                'vin' => 'VINTEST00000002',
                'make' => 'Peugeot',
                'model' => 'Boxer',
                'year' => 2023,
                'status' => 'InMaintenance',
                'mileage_km' => 22000,
                'franchisee_id' => $franchiseeIds[0],
                'acquired_at' => now()->subMonths(9)->toDateString(),
                'service_start' => now()->subMonths(8)->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::ulid(),
                'code' => 'TRK-TEST3',
                'plate' => 'TEST-003',
                'vin' => 'VINTEST00000003',
                'make' => 'Citroën',
                'model' => 'Jumper',
                'year' => 2022,
                'status' => 'Retired',
                'mileage_km' => 85000,
                'franchisee_id' => count($franchiseeIds) > 1 ? $franchiseeIds[1] : $franchiseeIds[0],
                'acquired_at' => now()->subYears(2)->toDateString(),
                'service_start' => now()->subYears(2)->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::ulid(),
                'code' => 'TRK-TEST4',
                'plate' => 'TEST-004',
                'vin' => 'VINTEST00000004',
                'make' => 'Mercedes-Benz',
                'model' => 'Sprinter',
                'year' => 2024,
                'status' => 'Draft',
                'mileage_km' => 500,
                'franchisee_id' => null,  // Unassigned truck
                'acquired_at' => now()->subWeeks(2)->toDateString(),
                'service_start' => null,  // Not yet in service
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($testTrucks as $truck) {
            DB::table('trucks')->insert($truck);
        }
        
        $this->command?->info('Created 4 test trucks with different statuses');
    }

    private function seedTestDeployments($trucks): void
    {
        $locations = [
            'Paris - Place de la République',
            'Paris - Jardin des Tuileries',
            'Lyon - Place Bellecour',
            'Marseille - Vieux Port',
            'Bordeaux - Place de la Bourse',
            'Lille - Grand Place',
            'Nantes - Île de Nantes',
            'Strasbourg - Place Kléber',
            'Nice - Promenade des Anglais',
            'Toulouse - Place du Capitole',
        ];

        // Clear existing test deployments if any
        DB::table('truck_deployments')
            ->whereIn('truck_id', $trucks->where('plate', 'like', 'TEST-%')->pluck('id'))
            ->delete();
        
        $this->command?->info('Creating diverse deployment scenarios for testing...');

        foreach ($trucks as $truck) {
            // Skip retired trucks
            if ($truck->status === 'Retired') {
                continue;
            }

            // Create different deployment patterns based on truck status
            switch ($truck->status) {
                case 'Active':
                    // Active truck: Past, present and future deployments with various statuses
                    $this->createPastDeployments($truck, $locations, 15);
                    $this->createCurrentDeployment($truck, $locations);
                    $this->createFutureDeployments($truck, $locations, 10);
                    break;
                    
                case 'InMaintenance':
                    // In Maintenance: Only past and future deployments (none current)
                    $this->createPastDeployments($truck, $locations, 10);
                    $this->createFutureDeployments($truck, $locations, 5);
                    break;
                    
                case 'Draft':
                    // Draft truck: Only future deployments (pending setup)
                    $this->createFutureDeployments($truck, $locations, 3);
                    break;
            }
        }
    }

    private function createPastDeployments($truck, $locations, $count): void
    {
        for ($i = 1; $i <= $count; $i++) {
            $daysAgo = mt_rand($i, $i + 3);
            $date = now()->subDays($daysAgo)->startOfDay();
            $startHour = mt_rand(8, 12);
            $durationHours = mt_rand(4, 8);
            
            $plannedStart = $date->copy()->setTime($startHour, 0);
            $plannedEnd = $plannedStart->copy()->addHours($durationHours);
            
            // 85% completed, 15% cancelled
            $status = (mt_rand(1, 100) <= 15) ? 'cancelled' : 'closed';
            
            $actualStart = null;
            $actualEnd = null;
            
            if ($status === 'closed') {
                // Actuals around planned (±60 min to create more variance)
                $actualStart = $plannedStart->copy()->addMinutes(mt_rand(-60, 60));
                $actualEnd = $plannedEnd->copy()->addMinutes(mt_rand(-60, 60));
                
                // 10% chance of ending early by 1-2 hours
                if (mt_rand(1, 100) <= 10) {
                    $actualEnd = $actualEnd->copy()->subHours(mt_rand(1, 2));
                }
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
                'notes' => $this->getRandomDeploymentNote($status),
                'created_at' => $date->copy()->subDays(mt_rand(1, 7)),
                'updated_at' => now()->subHours(mt_rand(1, 24)),
            ]);
        }
    }

    private function createCurrentDeployment($truck, $locations): void
    {
        $startTime = now()->subHours(mt_rand(1, 4));
        $endTime = now()->addHours(mt_rand(2, 6));
        
        DB::table('truck_deployments')->insert([
            'id' => (string) Str::ulid(),
            'truck_id' => $truck->id,
            'franchisee_id' => $truck->franchisee_id,
            'location_text' => $locations[array_rand($locations)],
            'planned_start_at' => $startTime,
            'planned_end_at' => $endTime,
            'actual_start_at' => $startTime->copy()->addMinutes(mt_rand(-15, 30)),
            'actual_end_at' => null,
            'status' => 'open',
            'notes' => mt_rand(1, 100) <= 40 ? 'Activité intense aujourd\'hui.' : null,
            'created_at' => now()->subDays(mt_rand(1, 7)),
            'updated_at' => now(),
        ]);
    }

    private function createFutureDeployments($truck, $locations, $count): void
    {
        for ($i = 1; $i <= $count; $i++) {
            $daysAhead = mt_rand($i, $i + 2);
            $date = now()->addDays($daysAhead)->startOfDay();
            $startHour = mt_rand(8, 13);
            $durationHours = mt_rand(4, 8);
            
            $plannedStart = $date->copy()->setTime($startHour, 0);
            $plannedEnd = $plannedStart->copy()->addHours($durationHours);
            
            DB::table('truck_deployments')->insert([
                'id' => (string) Str::ulid(),
                'truck_id' => $truck->id,
                'franchisee_id' => $truck->franchisee_id,
                'location_text' => $locations[array_rand($locations)],
                'planned_start_at' => $plannedStart,
                'planned_end_at' => $plannedEnd,
                'actual_start_at' => null,
                'actual_end_at' => null,
                'status' => 'planned',
                'notes' => mt_rand(1, 100) <= 30 ? $this->getRandomDeploymentNote('planned') : null,
                'created_at' => now()->subDays(mt_rand(1, 3)),
                'updated_at' => now()->subHours(mt_rand(1, 12)),
            ]);
        }
    }

    private function getRandomDeploymentNote($status): ?string
    {
        $notes = [
            'planned' => [
                'Prévoir rallonge électrique supplémentaire.',
                'Événement spécial à proximité, prévoir plus de stock.',
                'Zone à forte affluence, prévoir personnel supplémentaire.',
                'Vérifier les autorisations locales avant déploiement.',
                null,
            ],
            'cancelled' => [
                'Annulation due aux conditions météo défavorables.',
                'Annulé à la demande de l\'organisateur de l\'événement.',
                'Camion indisponible suite à un problème technique.',
                'Franchisé indisponible pour cause de maladie.',
                'Autorisation municipale révoquée à la dernière minute.',
            ],
            'closed' => [
                'Très bonne affluence, à refaire.',
                'Ventes légèrement en dessous des attentes.',
                'Emplacement à reconsidérer pour les prochaines fois.',
                'Problème d\'alimentation électrique résolu sur place.',
                null, null, null, // More chances of no notes
            ],
        ];

        $noteArray = $notes[$status] ?? [null];
        return $noteArray[array_rand($noteArray)];
    }

    private function seedTestMaintenance($trucks): void
    {
        // Clear existing test maintenance logs if any
        DB::table('maintenance_logs')
            ->whereIn('truck_id', $trucks->where('plate', 'like', 'TEST-%')->pluck('id'))
            ->delete();
            
        $this->command?->info('Creating diverse maintenance scenarios for testing...');

        foreach ($trucks as $truck) {
            $maintenanceCount = match($truck->status) {
                'Active' => mt_rand(3, 6),
                'InMaintenance' => mt_rand(5, 8),
                'Retired' => mt_rand(8, 12),
                'Draft' => 0,
                default => mt_rand(2, 4),
            };
            
            // Past completed maintenance
            for ($i = 0; $i < $maintenanceCount - 1; $i++) {
                $this->createCompletedMaintenance($truck);
            }
            
            // Current active maintenance for InMaintenance trucks
            if ($truck->status === 'InMaintenance') {
                $this->createActiveMaintenance($truck);
            }
        }
    }
    
    private function createCompletedMaintenance($truck): void
    {
        $startedDaysAgo = mt_rand(30, 365);
        $durationDays = mt_rand(1, 7);
        
        // 70% preventive, 30% corrective
        $kind = (mt_rand(1, 100) <= 70) ? 'Preventive' : 'Corrective';
        
        $descriptions = [
            'Preventive' => [
                'Vidange huile moteur et filtres',
                'Contrôle et remplacement plaquettes de frein',
                'Révision générale 30 000 km',
                'Remplacement courroie distribution',
                'Contrôle système électrique',
                'Mise à jour logiciel véhicule',
                'Contrôle et nettoyage circuit de refroidissement',
                'Contrôle et réglage géométrie',
            ],
            'Corrective' => [
                'Remplacement alternateur défectueux',
                'Réparation fuite circuit hydraulique',
                'Remplacement démarreur',
                'Réparation climatisation',
                'Remplacement injecteur diesel',
                'Réparation système d\'échappement',
                'Remplacement amortisseurs avant',
                'Réparation court-circuit tableau de bord',
            ],
        ];
        
        $startedAt = now()->subDays($startedDaysAgo);
        $closedAt = now()->subDays($startedDaysAgo - $durationDays);
        
        DB::table('maintenance_logs')->insert([
            'id' => (string) Str::ulid(),
            'truck_id' => $truck->id,
            'kind' => $kind,
            'description' => $descriptions[$kind][array_rand($descriptions[$kind])],
            'started_at' => $startedAt,
            'closed_at' => $closedAt,
            'created_at' => $startedAt,
            'updated_at' => $closedAt,
        ]);
    }
    
    private function createActiveMaintenance($truck): void
    {
        $startedDaysAgo = mt_rand(1, 5);
        
        // Active maintenance is more likely to be corrective
        $kind = (mt_rand(1, 100) <= 30) ? 'Preventive' : 'Corrective';
        
        $descriptions = [
            'Preventive' => [
                'Révision complète avant saison haute',
                'Contrôle général et mise à niveau',
                'Mise à jour équipements de sécurité',
                'Révision programmée 50 000 km',
            ],
            'Corrective' => [
                'Diagnostic panne moteur',
                'Réparation système de freinage',
                'Problème démarrage à froid',
                'Perte de puissance intermittente',
                'Surchauffe moteur',
                'Panne électrique tableau de bord',
            ],
        ];
        
        DB::table('maintenance_logs')->insert([
            'id' => (string) Str::ulid(),
            'truck_id' => $truck->id,
            'kind' => $kind,
            'description' => $descriptions[$kind][array_rand($descriptions[$kind])],
            'started_at' => now()->subDays($startedDaysAgo),
            'closed_at' => null,
            'created_at' => now()->subDays($startedDaysAgo),
            'updated_at' => now(),
        ]);
    }

    private function ensureTestDocuments($trucks): void
    {
        // Create diverse document scenarios
        $disk = Storage::disk('local');
        
        // Create test document directory if it doesn't exist
        if (!$disk->exists('private/test_docs')) {
            $disk->makeDirectory('private/test_docs');
        }
        
        // Create demo documents with different content
        $documents = [
            'registration_valid.pdf' => 'Document de démonstration: Certificat d\'immatriculation valide',
            'registration_expired.pdf' => 'Document de démonstration: Certificat d\'immatriculation expiré',
            'insurance_valid.pdf' => 'Document de démonstration: Assurance valide jusqu\'au 31/12/2026',
            'insurance_expired.pdf' => 'Document de démonstration: Assurance expirée le 01/01/2025',
            'inspection_report.pdf' => 'Document de démonstration: Rapport de contrôle technique',
            'maintenance_record.pdf' => 'Document de démonstration: Historique de maintenance complet',
        ];
        
        foreach ($documents as $filename => $content) {
            $path = 'private/test_docs/' . $filename;
            if (!$disk->exists($path)) {
                $disk->put($path, $content);
            }
        }
        
        // Assign documents to test trucks with various patterns
        foreach ($trucks as $index => $truck) {
            if (strpos($truck->plate, 'TEST-') === 0) {
                // Assign different document patterns based on truck number
                switch ($truck->plate) {
                    case 'TEST-001': // Active with valid documents
                        DB::table('trucks')->where('id', $truck->id)->update([
                            'registration_doc_path' => 'private/test_docs/registration_valid.pdf',
                            'insurance_doc_path' => 'private/test_docs/insurance_valid.pdf',
                            'updated_at' => now(),
                        ]);
                        break;
                        
                    case 'TEST-002': // InMaintenance with one expired document
                        DB::table('trucks')->where('id', $truck->id)->update([
                            'registration_doc_path' => 'private/test_docs/registration_valid.pdf',
                            'insurance_doc_path' => 'private/test_docs/insurance_expired.pdf',
                            'updated_at' => now(),
                        ]);
                        break;
                        
                    case 'TEST-003': // Retired with both expired documents
                        DB::table('trucks')->where('id', $truck->id)->update([
                            'registration_doc_path' => 'private/test_docs/registration_expired.pdf',
                            'insurance_doc_path' => 'private/test_docs/insurance_expired.pdf',
                            'updated_at' => now(),
                        ]);
                        break;
                        
                    case 'TEST-004': // Draft with no documents
                        DB::table('trucks')->where('id', $truck->id)->update([
                            'registration_doc_path' => null,
                            'insurance_doc_path' => null,
                            'updated_at' => now(),
                        ]);
                        break;
                }
            }
        }
    }
}
