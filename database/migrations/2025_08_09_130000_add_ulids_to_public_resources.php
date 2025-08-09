<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void
    {
        $tables = [
            'trucks', 'warehouses', 'stock_orders', 'stock_order_items', 'maintenance_records', 'supplies', 'suppliers',
            'dishes', 'dish_ingredients', 'inventory', 'inventory_movements',
            'franchises', 'customer_orders', 'order_items', 'event_registrations', 'locations', 'truck_deployments'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'ulid')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->ulid('ulid')->nullable()->unique();
                });
            }
        }

        // Backfill ULIDs
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $rows = DB::table($table)->select('id','ulid')->get();
                foreach ($rows as $row) {
                    if (empty($row->ulid)) {
                        DB::table($table)->where('id', $row->id)->update(['ulid' => (string) Str::ulid()]);
                    }
                }
            }
        }

    // Optionally, enforce NOT NULL later with DBAL; keeping nullable unique avoids DBAL dependency.
    }

    public function down(): void
    {
        $tables = [
            'trucks', 'warehouses', 'stock_orders', 'stock_order_items', 'maintenance_records', 'supplies', 'suppliers',
            'dishes', 'dish_ingredients', 'inventory', 'inventory_movements',
            'franchises', 'customer_orders', 'order_items', 'event_registrations', 'locations', 'truck_deployments'
        ];
        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'ulid')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropColumn('ulid');
                });
            }
        }
    }
};
