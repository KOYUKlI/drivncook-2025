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
        // Check if the severity column already exists
        if (!Schema::hasColumn('maintenance_logs', 'severity')) {
            Schema::table('maintenance_logs', function (Blueprint $table) {
                $table->enum('severity', ['low', 'medium', 'high'])->nullable()->after('kind');
            });
        }
        
        // Check if the priority column already exists
        if (!Schema::hasColumn('maintenance_logs', 'priority')) {
            Schema::table('maintenance_logs', function (Blueprint $table) {
                $table->enum('priority', ['P3', 'P2', 'P1'])->nullable()->after('severity');
            });
        }
        
        // Planning information
        if (!Schema::hasColumn('maintenance_logs', 'planned_start_at')) {
            Schema::table('maintenance_logs', function (Blueprint $table) {
                $table->timestamp('planned_start_at')->nullable()->after('priority');
            });
        }
        
        if (!Schema::hasColumn('maintenance_logs', 'planned_end_at')) {
            Schema::table('maintenance_logs', function (Blueprint $table) {
                $table->timestamp('planned_end_at')->nullable()->after('planned_start_at');
            });
        }
        
        if (!Schema::hasColumn('maintenance_logs', 'due_at')) {
            Schema::table('maintenance_logs', function (Blueprint $table) {
                $table->timestamp('due_at')->nullable()->after('planned_end_at');
            });
        }
        
        // Mileage tracking
        if (!Schema::hasColumn('maintenance_logs', 'mileage_open_km')) {
            Schema::table('maintenance_logs', function (Blueprint $table) {
                $table->integer('mileage_open_km')->nullable()->after('due_at');
            });
        }
        
        if (!Schema::hasColumn('maintenance_logs', 'mileage_close_km')) {
            Schema::table('maintenance_logs', function (Blueprint $table) {
                $table->integer('mileage_close_km')->nullable()->after('mileage_open_km');
            });
        }
        
        // Provider information
        if (!Schema::hasColumn('maintenance_logs', 'provider_name')) {
            Schema::table('maintenance_logs', function (Blueprint $table) {
                $table->string('provider_name')->nullable()->after('mileage_close_km');
            });
        }
        
        if (!Schema::hasColumn('maintenance_logs', 'provider_contact')) {
            Schema::table('maintenance_logs', function (Blueprint $table) {
                $table->string('provider_contact')->nullable()->after('provider_name');
            });
        }
        
        // Cost information
        if (!Schema::hasColumn('maintenance_logs', 'cost_cents')) {
            Schema::table('maintenance_logs', function (Blueprint $table) {
                $table->integer('cost_cents')->nullable()->after('provider_contact');
            });
        }
        
        if (!Schema::hasColumn('maintenance_logs', 'labor_cents')) {
            Schema::table('maintenance_logs', function (Blueprint $table) {
                $table->integer('labor_cents')->nullable()->after('cost_cents');
            });
        }
        
        if (!Schema::hasColumn('maintenance_logs', 'parts_cents')) {
            Schema::table('maintenance_logs', function (Blueprint $table) {
                $table->integer('parts_cents')->nullable()->after('labor_cents');
            });
        }
        
        // Pause information
        if (!Schema::hasColumn('maintenance_logs', 'paused_at')) {
            Schema::table('maintenance_logs', function (Blueprint $table) {
                $table->timestamp('paused_at')->nullable()->after('parts_cents');
            });
        }
        
        if (!Schema::hasColumn('maintenance_logs', 'resumed_at')) {
            Schema::table('maintenance_logs', function (Blueprint $table) {
                $table->timestamp('resumed_at')->nullable()->after('paused_at');
            });
        }
        
        // Add index if it doesn't exist
        Schema::table('maintenance_logs', function (Blueprint $table) {
            if (!Schema::hasIndex('maintenance_logs', 'maintenance_logs_started_at_index')) {
                $table->index('started_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop columns if they exist
        $columns = [
            'severity',
            'priority',
            'planned_start_at',
            'planned_end_at',
            'due_at',
            'mileage_open_km',
            'mileage_close_km',
            'provider_name',
            'provider_contact',
            'cost_cents',
            'labor_cents',
            'parts_cents',
            'paused_at',
            'resumed_at',
        ];
        
        foreach ($columns as $column) {
            if (Schema::hasColumn('maintenance_logs', $column)) {
                Schema::table('maintenance_logs', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
        
        // Drop index if it exists
        if (Schema::hasIndex('maintenance_logs', 'maintenance_logs_started_at_index')) {
            Schema::table('maintenance_logs', function (Blueprint $table) {
                $table->dropIndex(['started_at']);
            });
        }
    }
};
