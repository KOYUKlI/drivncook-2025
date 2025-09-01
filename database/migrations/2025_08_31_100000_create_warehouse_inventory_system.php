<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
    // warehouse_inventories table is created by an earlier migration (043040)
    // Create stock_movements only
    if (!Schema::hasTable('stock_movements')) {
    Schema::create('stock_movements', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->foreignUlid('stock_item_id')->constrained('stock_items')->restrictOnDelete();
            $table->enum('type', ['receipt', 'withdrawal', 'adjustment', 'transfer_in', 'transfer_out']);
            $table->integer('quantity');
            $table->text('reason')->nullable();
            $table->string('ref_type')->nullable();
            $table->string('ref_id')->nullable();
            $table->foreignUlid('related_movement_id')->nullable()->references('id')->on('stock_movements')->nullOnDelete();
            // users.id is a big integer (increments), so use foreignId here instead of foreignUlid
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            // Performance indexes
            $table->index('type');
            $table->index(['ref_type', 'ref_id']);
            $table->index('created_at');
    });
    }
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
