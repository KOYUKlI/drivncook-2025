<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('purchase_orders') || !Schema::hasColumn('purchase_orders', 'warehouse_id')) {
            return;
        }

        $driver = DB::getDriverName();

        // MySQL / MariaDB: alter column nullability with raw SQL
        if (in_array($driver, ['mysql', 'mariadb'])) {
            try { DB::statement('ALTER TABLE purchase_orders DROP FOREIGN KEY purchase_orders_warehouse_id_foreign'); } catch (\Throwable $e) {}
            try { DB::statement("ALTER TABLE purchase_orders MODIFY warehouse_id CHAR(26) NULL"); } catch (\Throwable $e) {}
            try { DB::statement('ALTER TABLE purchase_orders ADD CONSTRAINT purchase_orders_warehouse_id_foreign FOREIGN KEY (warehouse_id) REFERENCES warehouses(id) ON DELETE CASCADE'); } catch (\Throwable $e) {}
            return;
        }

        // SQLite / others: best-effort; some drivers support change() but usually require doctrine/dbal.
        try {
            Schema::table('purchase_orders', function (Blueprint $table) {
                $table->ulid('warehouse_id')->nullable()->change();
            });
        } catch (\Throwable $e) {
            // No-op if not supported; tests using MySQL will have been handled above.
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('purchase_orders') || !Schema::hasColumn('purchase_orders', 'warehouse_id')) {
            return;
        }
        $driver = DB::getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'])) {
            try { DB::statement('ALTER TABLE purchase_orders DROP FOREIGN KEY purchase_orders_warehouse_id_foreign'); } catch (\Throwable $e) {}
            try { DB::statement("ALTER TABLE purchase_orders MODIFY warehouse_id CHAR(26) NOT NULL"); } catch (\Throwable $e) {}
            try { DB::statement('ALTER TABLE purchase_orders ADD CONSTRAINT purchase_orders_warehouse_id_foreign FOREIGN KEY (warehouse_id) REFERENCES warehouses(id) ON DELETE CASCADE'); } catch (\Throwable $e) {}
            return;
        }

        try {
            Schema::table('purchase_orders', function (Blueprint $table) {
                $table->ulid('warehouse_id')->nullable(false)->change();
            });
        } catch (\Throwable $e) {
            // No-op
        }
    }
};
