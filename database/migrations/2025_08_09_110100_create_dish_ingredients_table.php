<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('dish_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dish_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('supply_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->decimal('qty_per_dish', 12, 3);
            $table->string('unit', 20);
            $table->timestamps();
            $table->unique(['dish_id','supply_id'], 'uk_bom');
        });
    }
    public function down(): void {
        Schema::dropIfExists('dish_ingredients');
    }
};
