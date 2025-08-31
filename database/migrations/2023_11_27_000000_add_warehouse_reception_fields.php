<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add tracking and shipping fields to purchase_orders table (guard if table exists)
        if (Schema::hasTable('purchase_orders')) {
            Schema::table('purchase_orders', function (Blueprint $table) {
                if (!Schema::hasColumn('purchase_orders', 'preparation_notes')) {
                    $table->text('preparation_notes')->nullable()->after('status_updated_by');
                }
                if (!Schema::hasColumn('purchase_orders', 'shipping_notes')) {
                    $table->text('shipping_notes')->nullable()->after('preparation_notes');
                }
                if (!Schema::hasColumn('purchase_orders', 'reception_notes')) {
                    $table->text('reception_notes')->nullable()->after('shipping_notes');
                }
                if (!Schema::hasColumn('purchase_orders', 'tracking_number')) {
                    $table->string('tracking_number', 100)->nullable()->after('reception_notes');
                }
                if (!Schema::hasColumn('purchase_orders', 'carrier')) {
                    $table->string('carrier', 100)->nullable()->after('tracking_number');
                }
                if (!Schema::hasColumn('purchase_orders', 'shipping_date')) {
                    $table->timestamp('shipping_date')->nullable()->after('carrier');
                }
            });
        }

        // Add received_qty field to purchase_lines table (guard if table exists)
        if (Schema::hasTable('purchase_lines') && !Schema::hasColumn('purchase_lines', 'received_qty')) {
            Schema::table('purchase_lines', function (Blueprint $table) {
                $table->integer('received_qty')->nullable()->after('unit_price_cents');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert purchase_orders table (guard)
        if (Schema::hasTable('purchase_orders')) {
            Schema::table('purchase_orders', function (Blueprint $table) {
                foreach ([
                    'preparation_notes',
                    'shipping_notes',
                    'reception_notes',
                    'tracking_number',
                    'carrier',
                    'shipping_date',
                ] as $col) {
                    if (Schema::hasColumn('purchase_orders', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }

        // Revert purchase_lines table (guard)
        if (Schema::hasTable('purchase_lines') && Schema::hasColumn('purchase_lines', 'received_qty')) {
            Schema::table('purchase_lines', function (Blueprint $table) {
                $table->dropColumn('received_qty');
            });
        }
    }
};
