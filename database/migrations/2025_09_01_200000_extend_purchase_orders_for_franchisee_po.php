<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('purchase_orders')) {
            Schema::table('purchase_orders', function (Blueprint $table) {
                if (!Schema::hasColumn('purchase_orders', 'total_cents')) {
                    $table->integer('total_cents')->default(0)->after('corp_ratio_cached');
                }
                if (!Schema::hasColumn('purchase_orders', 'submitted_at')) {
                    $table->timestamp('submitted_at')->nullable()->after('status_updated_by');
                    $table->index('submitted_at');
                }
                if (!Schema::hasColumn('purchase_orders', 'approved_at')) {
                    $table->timestamp('approved_at')->nullable()->after('submitted_at');
                    $table->index('approved_at');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('purchase_orders')) {
            Schema::table('purchase_orders', function (Blueprint $table) {
                if (Schema::hasColumn('purchase_orders', 'approved_at')) {
                    $table->dropIndex(['approved_at']);
                    $table->dropColumn('approved_at');
                }
                if (Schema::hasColumn('purchase_orders', 'submitted_at')) {
                    $table->dropIndex(['submitted_at']);
                    $table->dropColumn('submitted_at');
                }
                if (Schema::hasColumn('purchase_orders', 'total_cents')) {
                    $table->dropColumn('total_cents');
                }
            });
        }
    }
};
