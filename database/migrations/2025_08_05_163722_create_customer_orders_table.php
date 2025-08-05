<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('customer_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('truck_id')->constrained()->onDelete('cascade');
            $table->foreignId('loyalty_card_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('total_price', 8, 2)->nullable();
            $table->timestamp('ordered_at')->useCurrent();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('customer_orders');
    }
};
