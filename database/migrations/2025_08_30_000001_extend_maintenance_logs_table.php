<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('maintenance_logs', function (Blueprint $table) {
            // Add new status options to match planned|open|paused|closed|cancelled
            // Add severity and priority enums
            $table->enum('severity', ['low', 'medium', 'high'])->nullable()->after('type');
            $table->enum('priority', ['P3', 'P2', 'P1'])->nullable()->after('severity');
            
            // Planning information
            $table->timestamp('planned_start_at')->nullable()->after('priority');
            $table->timestamp('planned_end_at')->nullable()->after('planned_start_at');
            $table->timestamp('due_at')->nullable()->after('planned_end_at');
            
            // Mileage tracking
            $table->integer('mileage_open_km')->nullable()->after('due_at');
            $table->integer('mileage_close_km')->nullable()->after('mileage_open_km');
            
            // Provider information
            $table->string('provider_name')->nullable()->after('mileage_close_km');
            $table->string('provider_contact')->nullable()->after('provider_name');
            
            // Detailed costs (existing cost_cents will be for total)
            $table->integer('labor_cents')->nullable()->after('cost_cents');
            $table->integer('parts_cents')->nullable()->after('labor_cents');
            
            // Pause information
            $table->timestamp('paused_at')->nullable()->after('parts_cents');
            $table->timestamp('resumed_at')->nullable()->after('paused_at');
            
            // Modify existing status column 
            // MySQL doesn't support altering enum columns directly,
            // so we'll handle this in the model instead
            
            // Add indices for performance
            $table->index('status');
            $table->index('planned_start_at');
            $table->index('opened_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maintenance_logs', function (Blueprint $table) {
            // Drop columns
            $table->dropColumn([
                'severity',
                'priority',
                'planned_start_at',
                'planned_end_at',
                'due_at',
                'mileage_open_km',
                'mileage_close_km',
                'provider_name',
                'provider_contact',
                'labor_cents',
                'parts_cents',
                'paused_at',
                'resumed_at',
            ]);
            
            // Drop indices
            $table->dropIndex(['status']);
            $table->dropIndex(['planned_start_at']);
            $table->dropIndex(['opened_at']);
        });
    }
};
