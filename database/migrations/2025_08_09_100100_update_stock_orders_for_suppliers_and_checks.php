<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasColumn('stock_orders', 'supplier_id')) {
            Schema::table('stock_orders', function (Blueprint $table) {
                $table->foreignId('supplier_id')->nullable()->after('warehouse_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            });
        }
        // NOTE: We enforce XOR (warehouse_id XOR supplier_id) at application level and via validation.
        // MySQL CHECK on a column involved in a FK referential action can raise error 3823; so we avoid a DB CHECK here.
    }
    public function down(): void {
        if (Schema::hasColumn('stock_orders', 'supplier_id')) {
            Schema::table('stock_orders', function (Blueprint $table) {
                $table->dropConstrainedForeignId('supplier_id');
            });
        }
    }
};
