<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('franchise');    // 'admin' or 'franchise':contentReference[oaicite:3]{index=3}
            $table->foreignId('franchise_id')->nullable()
                  ->constrained('franchises')->nullOnDelete();
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'franchise_id']);
        });
    }
};
