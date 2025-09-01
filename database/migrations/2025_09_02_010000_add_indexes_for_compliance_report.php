<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Purchase orders frequently filtered by kind, franchisee, status, dates
        Schema::table('purchase_orders', function (Blueprint $table) {
            try { $table->index('kind', 'po_kind_idx'); } catch (\Throwable $e) {}
            try { $table->index('franchisee_id', 'po_franchisee_idx'); } catch (\Throwable $e) {}
            try { $table->index('status', 'po_status_idx'); } catch (\Throwable $e) {}
            try { $table->index('created_at', 'po_created_at_idx'); } catch (\Throwable $e) {}
            if (Schema::hasColumn('purchase_orders', 'placed_by')) {
                try { $table->index('placed_by', 'po_placed_by_idx'); } catch (\Throwable $e) {}
            }
        });

        // Lines used to compute totals and join to stock items
        Schema::table('purchase_lines', function (Blueprint $table) {
            try { $table->index('purchase_order_id', 'pl_order_idx'); } catch (\Throwable $e) {}
            try { $table->index('stock_item_id', 'pl_stock_item_idx'); } catch (\Throwable $e) {}
        });

        // Stock items central flag used for 80/20 split
        Schema::table('stock_items', function (Blueprint $table) {
            if (Schema::hasColumn('stock_items', 'is_central')) {
                try { $table->index('is_central', 'si_is_central_idx'); } catch (\Throwable $e) {}
            }
        });
    }

    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            try { $table->dropIndex('po_kind_idx'); } catch (\Throwable $e) {}
            try { $table->dropIndex('po_franchisee_idx'); } catch (\Throwable $e) {}
            try { $table->dropIndex('po_status_idx'); } catch (\Throwable $e) {}
            try { $table->dropIndex('po_created_at_idx'); } catch (\Throwable $e) {}
            try { $table->dropIndex('po_placed_by_idx'); } catch (\Throwable $e) {}
        });

        Schema::table('purchase_lines', function (Blueprint $table) {
            try { $table->dropIndex('pl_order_idx'); } catch (\Throwable $e) {}
            try { $table->dropIndex('pl_stock_item_idx'); } catch (\Throwable $e) {}
        });

        Schema::table('stock_items', function (Blueprint $table) {
            try { $table->dropIndex('si_is_central_idx'); } catch (\Throwable $e) {}
        });
    }
};
