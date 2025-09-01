<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
    Schema::table('purchase_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('purchase_orders', 'shipped_at')) {
        // Place after updated_at for compatibility across setups
        $table->timestamp('shipped_at')->nullable()->after('updated_at');
                $table->index('shipped_at');
            }
            if (!Schema::hasColumn('purchase_orders', 'delivered_at')) {
                $table->timestamp('delivered_at')->nullable()->after('shipped_at');
                $table->index('delivered_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            if (Schema::hasColumn('purchase_orders', 'delivered_at')) {
                $table->dropIndex(['delivered_at']);
                $table->dropColumn('delivered_at');
            }
            if (Schema::hasColumn('purchase_orders', 'shipped_at')) {
                $table->dropIndex(['shipped_at']);
                $table->dropColumn('shipped_at');
            }
        });
    }
};
