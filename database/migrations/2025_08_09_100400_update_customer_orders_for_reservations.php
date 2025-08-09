<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('customer_orders', function (Blueprint $table) {
            $table->enum('order_type', ['online','on_site','reservation'])->default('online')->after('loyalty_card_id');
            $table->dateTime('pickup_at')->nullable()->after('order_type');
            $table->foreignId('location_id')->nullable()->after('truck_id')->constrained('locations')->nullOnDelete();
            $table->decimal('total_price', 12, 2)->change();
        });
        // Keep ordered_at as-is; ensure index for period reporting
        Schema::table('customer_orders', function (Blueprint $table) {
            $table->index(['truck_id','ordered_at']);
        });
    }
    public function down(): void {
        Schema::table('customer_orders', function (Blueprint $table) {
            $table->dropIndex(['truck_id','ordered_at']);
            $table->dropConstrainedForeignId('location_id');
            $table->dropColumn(['order_type','pickup_at']);
            $table->decimal('total_price', 8, 2)->change();
        });
    }
};
