<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('warehouse_inventories')) {
            return;
        }
        $driver = DB::getDriverName();
        // Optional: enforce non-negative qty_on_hand at DB level when supported (MySQL 8+)
        if ($driver === 'mysql') {
            try {
                DB::statement("ALTER TABLE warehouse_inventories ADD CONSTRAINT chk_qty_on_hand_non_negative CHECK (qty_on_hand >= 0)");
            } catch (\Throwable $e) {
                // ignore if already exists or not supported
            }
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('warehouse_inventories')) {
            return;
        }
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            try {
                DB::statement('ALTER TABLE warehouse_inventories DROP CHECK chk_qty_on_hand_non_negative');
            } catch (\Throwable $e) {
                // ignore
            }
        }
    }
};
