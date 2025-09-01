<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('purchase_orders')) {
            Schema::table('purchase_orders', function (Blueprint $t) {
                if (!Schema::hasColumn('purchase_orders', 'reference')) {
                    $t->string('reference', 30)->nullable()->after('id');
                    $t->unique('reference');
                    $t->index('reference');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('purchase_orders') && Schema::hasColumn('purchase_orders', 'reference')) {
            Schema::table('purchase_orders', function (Blueprint $t) {
                try { $t->dropUnique(['reference']); } catch (\Throwable $e) {}
                try { $t->dropIndex(['reference']); } catch (\Throwable $e) {}
                $t->dropColumn('reference');
            });
        }
    }
};
