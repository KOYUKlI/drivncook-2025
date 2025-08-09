<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('loyalty_rules', function (Blueprint $table) {
            $table->id();
            $table->decimal('points_per_euro', 6, 2)->default(1.00);
            $table->decimal('redeem_rate', 6, 2)->default(100.00); // 100 pts = 1€
            $table->integer('expires_after_months')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('loyalty_rules');
    }
};
