<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Extend purchase_orders with kind, placed_by, status audit fields
        if (Schema::hasTable('purchase_orders')) {
            Schema::table('purchase_orders', function (Blueprint $t) {
                if (!Schema::hasColumn('purchase_orders', 'kind')) {
                    $t->string('kind', 20)->default('Standard')->after('status');
                    $t->index('kind');
                }
                if (!Schema::hasColumn('purchase_orders', 'placed_by')) {
                    $t->foreignId('placed_by')->nullable()->after('franchisee_id')
                        ->constrained('users')->nullOnDelete();
                }
                if (!Schema::hasColumn('purchase_orders', 'status_updated_at')) {
                    $t->timestamp('status_updated_at')->nullable()->after('updated_at');
                }
                if (!Schema::hasColumn('purchase_orders', 'status_updated_by')) {
                    $t->foreignId('status_updated_by')->nullable()->after('status_updated_at')
                        ->constrained('users')->nullOnDelete();
                }
            });
        }

        // Extend purchase_lines with operational quantities
        if (Schema::hasTable('purchase_lines')) {
            Schema::table('purchase_lines', function (Blueprint $t) {
                if (!Schema::hasColumn('purchase_lines', 'qty_picked')) {
                    $t->unsignedInteger('qty_picked')->default(0)->after('qty');
                }
                if (!Schema::hasColumn('purchase_lines', 'qty_shipped')) {
                    $t->unsignedInteger('qty_shipped')->default(0)->after('qty_picked');
                }
                if (!Schema::hasColumn('purchase_lines', 'qty_delivered')) {
                    $t->unsignedInteger('qty_delivered')->default(0)->after('qty_shipped');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('purchase_orders')) {
            Schema::table('purchase_orders', function (Blueprint $t) {
                foreach (['kind','placed_by','status_updated_at','status_updated_by'] as $col) {
                    if (Schema::hasColumn('purchase_orders', $col)) {
                        // Drop FK first when relevant
                        if (in_array($col, ['placed_by','status_updated_by'])) {
                            $fk = 'purchase_orders_'.$col.'_foreign';
                            if (Schema::hasColumn('purchase_orders', $col)) {
                                try { $t->dropForeign($fk); } catch (\Throwable $e) {}
                            }
                        }
                        $t->dropColumn($col);
                    }
                }
            });
        }
        if (Schema::hasTable('purchase_lines')) {
            Schema::table('purchase_lines', function (Blueprint $t) {
                foreach (['qty_picked','qty_shipped','qty_delivered'] as $col) {
                    if (Schema::hasColumn('purchase_lines', $col)) {
                        $t->dropColumn($col);
                    }
                }
            });
        }
    }
};
