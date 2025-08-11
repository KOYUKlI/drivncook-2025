<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('compliance_kpis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('franchise_id')->constrained('franchises')->cascadeOnDelete();
            $table->integer('period_year');
            $table->unsignedTinyInteger('period_month');
            $table->decimal('external_turnover', 12, 2)->default(0);
            $table->timestamps();
            $table->unique(['franchise_id','period_year','period_month'], 'uk_kpi_period');
        });
    }
    public function down(): void {
        Schema::dropIfExists('compliance_kpis');
    }
};
