<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('franchisee_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->integer('period_year');
            $table->unsignedTinyInteger('period_month');
            $table->decimal('turnover', 12, 2);
            $table->decimal('rate', 5, 2)->default(4.00);
            $table->enum('status', ['pending','paid','canceled'])->default('pending');
            $table->timestamp('calculated_at');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->unique(['franchisee_id','period_year','period_month'], 'uk_commissions_period');
        });
    }
    public function down(): void {
        Schema::dropIfExists('commissions');
    }
};
