<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouse_inventories', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('warehouse_id');
            $table->string('stock_item_id');
            $table->bigInteger('qty_on_hand')->default(0);
            $table->bigInteger('min_qty')->nullable();
            $table->bigInteger('max_qty')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->foreign('stock_item_id')->references('id')->on('stock_items');
            $table->unique(['warehouse_id', 'stock_item_id']);
            
            // Performance indexes
            $table->index('warehouse_id');
            $table->index('stock_item_id');
            $table->index('qty_on_hand');
        });

        Schema::create('stock_movements', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('warehouse_id');
            $table->string('stock_item_id');
            $table->enum('type', ['receipt', 'withdrawal', 'adjustment', 'transfer_in', 'transfer_out']);
            $table->bigInteger('quantity');
            $table->text('reason')->nullable();
            $table->string('ref_type')->nullable();
            $table->string('ref_id')->nullable();
            $table->string('related_movement_id')->nullable();
            $table->string('user_id');
            $table->timestamps();
            
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->foreign('stock_item_id')->references('id')->on('stock_items');
            $table->foreign('user_id')->references('id')->on('users');
            
            // Performance indexes
            $table->index('warehouse_id');
            $table->index('stock_item_id');
            $table->index('type');
            $table->index(['ref_type', 'ref_id']);
            $table->index('related_movement_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('warehouse_inventories');
    }
};
