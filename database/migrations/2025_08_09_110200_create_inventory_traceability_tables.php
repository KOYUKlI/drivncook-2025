<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('inventory_lots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained('inventory')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('lot_code', 64)->nullable();
            $table->date('expires_at')->nullable();
            $table->decimal('qty', 12, 3);
            $table->timestamps();
        });

        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained('inventory')->cascadeOnUpdate()->restrictOnDelete();
            $table->enum('type', ['in','out','transfer']);
            $table->decimal('qty', 12, 3);
            $table->enum('reason', ['purchase','sale','prep','waste','adjust','transfer']);
            $table->string('ref_table', 40)->nullable();
            $table->unsignedBigInteger('ref_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('inventory_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained('inventory')->cascadeOnUpdate()->restrictOnDelete();
            $table->decimal('qty_diff', 12, 3);
            $table->enum('reason', ['waste','breakage','audit']);
            $table->string('note', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }
    public function down(): void {
        Schema::dropIfExists('inventory_adjustments');
        Schema::dropIfExists('inventory_movements');
        Schema::dropIfExists('inventory_lots');
    }
};
