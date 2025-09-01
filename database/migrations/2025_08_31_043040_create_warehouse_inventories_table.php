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
        Schema::create('warehouse_inventories', function (Blueprint $table) {
            // Primary key as ULID to match other core tables
            $table->ulid('id')->primary();

            // Foreign keys
            $table->foreignUlid('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->foreignUlid('stock_item_id')->constrained('stock_items')->restrictOnDelete();

            // Inventory quantities
            $table->integer('qty_on_hand')->default(0);
            $table->integer('min_qty')->nullable();
            $table->integer('max_qty')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Unique per warehouse + item to avoid duplicates
            $table->unique(['warehouse_id', 'stock_item_id']);
            $table->index('qty_on_hand');
            $table->index('min_qty');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_inventories');
    }
};
