<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ensure warehouse_inventories has all required columns
        if (Schema::hasTable('warehouse_inventories')) {
            Schema::table('warehouse_inventories', function (Blueprint $table) {
                if (!Schema::hasColumn('warehouse_inventories', 'warehouse_id')) {
                    $table->foreignUlid('warehouse_id')->after('id')->constrained('warehouses')->cascadeOnDelete();
                }
                if (!Schema::hasColumn('warehouse_inventories', 'stock_item_id')) {
                    $table->foreignUlid('stock_item_id')->after('warehouse_id')->constrained('stock_items')->restrictOnDelete();
                }
                if (!Schema::hasColumn('warehouse_inventories', 'qty_on_hand')) {
                    $table->integer('qty_on_hand')->default(0)->after('stock_item_id');
                }
                if (!Schema::hasColumn('warehouse_inventories', 'min_qty')) {
                    $table->integer('min_qty')->nullable()->after('qty_on_hand');
                }
                if (!Schema::hasColumn('warehouse_inventories', 'max_qty')) {
                    $table->integer('max_qty')->nullable()->after('min_qty');
                }
                if (!Schema::hasColumn('warehouse_inventories', 'deleted_at')) {
                    $table->softDeletes();
                }
            });

            // Add unique index if missing
            try {
                Schema::table('warehouse_inventories', function (Blueprint $table) {
                    $table->unique(['warehouse_id', 'stock_item_id'], 'wh_inv_unique');
                });
            } catch (Throwable $e) {
                // ignore if already exists
            }

            // Helpful indexes
            try {
                Schema::table('warehouse_inventories', function (Blueprint $table) {
                    $table->index('qty_on_hand');
                    $table->index('min_qty');
                });
            } catch (Throwable $e) {
                // ignore if already exists
            }
        }
    }

    public function down(): void
    {
        // No-op downgrade to avoid destructive changes
    }
};
