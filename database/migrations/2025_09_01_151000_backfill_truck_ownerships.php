<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('truck_ownerships') || !Schema::hasTable('trucks')) {
            return;
        }

        // Backfill: for each truck with a franchisee_id and without an active ownership record, create one
        \App\Models\Truck::query()
            ->whereNotNull('franchisee_id')
            ->chunkById(200, function ($trucks) {
                foreach ($trucks as $truck) {
                    $hasActive = \App\Models\TruckOwnership::where('truck_id', $truck->id)
                        ->whereNull('ended_at')
                        ->exists();
                    if (! $hasActive) {
                        \App\Models\TruckOwnership::create([
                            'id' => (string) \Illuminate\Support\Str::ulid(),
                            'truck_id' => $truck->id,
                            'franchisee_id' => $truck->franchisee_id,
                            'started_at' => now(),
                        ]);
                    }
                }
            });
    }

    public function down(): void
    {
        // No-op: don't remove historical backfilled data
    }
};
