<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('supplies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('unit')->nullable();   // e.g., kg, liters, etc.
            $table->decimal('cost', 8, 2)->nullable(); // optional cost per unit
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('supplies');
    }
};
