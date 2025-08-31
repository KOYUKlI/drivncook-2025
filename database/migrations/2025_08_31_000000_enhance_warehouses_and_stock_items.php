<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('warehouses', function (Blueprint $table) {
            $table->string('code', 10)->unique()->after('id');
            $table->string('region', 64)->nullable()->after('city');
            $table->text('address')->nullable()->after('region');
            $table->string('phone', 32)->nullable()->after('address');
            $table->string('email')->nullable()->after('phone');
            $table->boolean('is_active')->default(true)->after('email');
            $table->text('notes')->nullable()->after('is_active');
            
            $table->index(['code', 'is_active']);
        });

        Schema::table('stock_items', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('is_central');
            $table->text('notes')->nullable()->after('is_active');
            
            $table->index('is_central');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropIndex(['code', 'is_active']);
            $table->dropColumn(['code', 'region', 'address', 'phone', 'email', 'is_active', 'notes']);
        });

        Schema::table('stock_items', function (Blueprint $table) {
            $table->dropIndex('is_central');
            $table->dropIndex('is_active');
            $table->dropColumn(['is_active', 'notes']);
        });
    }
};
