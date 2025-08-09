<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('loyalty_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loyalty_card_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['earn','redeem','adjust']);
            $table->integer('points');
            $table->foreignId('customer_order_id')->nullable()->constrained('customer_orders')->nullOnDelete();
            $table->string('note', 255)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('loyalty_transactions');
    }
};
