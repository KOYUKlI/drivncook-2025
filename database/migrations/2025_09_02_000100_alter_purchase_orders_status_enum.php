<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('purchase_orders') || !Schema::hasColumn('purchase_orders', 'status')) {
            return;
        }

        $driver = DB::getDriverName();
        // We need to support both legacy PO statuses and new replenishment ones
        $allStatuses = [
            'Draft', 'Approved', 'Prepared', 'Picked', 'Shipped', 'Received', 'Delivered', 'Closed', 'Cancelled',
        ];

        if ($driver === 'mysql') {
            $enum = "'" . implode("','", $allStatuses) . "'";
            DB::statement("ALTER TABLE purchase_orders MODIFY COLUMN status ENUM($enum) NOT NULL DEFAULT 'Draft'");
        } elseif ($driver === 'pgsql') {
            // Safest cross-version approach: widen to VARCHAR to accept new values
            DB::statement("ALTER TABLE purchase_orders ALTER COLUMN status TYPE VARCHAR(32)");
        } else {
            // sqlite or others: enum is stored as TEXT already; nothing to do
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('purchase_orders') || !Schema::hasColumn('purchase_orders', 'status')) {
            return;
        }

        $driver = DB::getDriverName();
        // Revert to the original smaller set if on MySQL; otherwise keep as-is
        if ($driver === 'mysql') {
            $original = "'Draft','Approved','Prepared','Shipped','Received','Cancelled'";
            DB::statement("ALTER TABLE purchase_orders MODIFY COLUMN status ENUM($original) NOT NULL DEFAULT 'Draft'");
        } elseif ($driver === 'pgsql') {
            // Keep VARCHAR(32); reversing enum types in PG would require more complex type management.
        } else {
            // No-op for sqlite/others
        }
    }
};
