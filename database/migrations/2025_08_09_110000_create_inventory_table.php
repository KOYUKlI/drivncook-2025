<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('supply_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->decimal('on_hand', 12, 3)->default(0);
            $table->timestamps();
            $table->unique(['warehouse_id','supply_id'], 'uk_inventory');
        });
    }
    public function down(): void {
        Schema::dropIfExists('inventory');
    }
};
