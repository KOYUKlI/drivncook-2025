<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Example monetary checks (MySQL 8+)
        DB::statement("ALTER TABLE order_items ADD CONSTRAINT chk_order_items_qty CHECK (quantity >= 0)");
        DB::statement("ALTER TABLE stock_order_items ADD CONSTRAINT chk_stock_order_items_qty CHECK (quantity >= 0)");
    }
    public function down(): void {
        DB::statement('ALTER TABLE order_items DROP CHECK chk_order_items_qty');
        DB::statement('ALTER TABLE stock_order_items DROP CHECK chk_stock_order_items_qty');
    }
};
