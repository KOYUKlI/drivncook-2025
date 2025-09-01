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
        if (Schema::hasTable('maintenance_logs')) {
            Schema::table('maintenance_logs', function (Blueprint $table) {
                // Add status enum to align with model if missing
                if (!Schema::hasColumn('maintenance_logs', 'status')) {
                    $table->enum('status', ['planned', 'open', 'paused', 'closed', 'cancelled'])->default('planned')->after('kind');
                }
                // Add severity and priority enums (place at end to avoid missing after-columns)
                if (!Schema::hasColumn('maintenance_logs', 'severity')) {
                    $table->enum('severity', ['low', 'medium', 'high'])->nullable();
                }
                if (!Schema::hasColumn('maintenance_logs', 'priority')) {
                    $table->enum('priority', ['P3', 'P2', 'P1'])->nullable();
                }

                // Planning information
                if (!Schema::hasColumn('maintenance_logs', 'planned_start_at')) {
                    $table->timestamp('planned_start_at')->nullable();
                }
                if (!Schema::hasColumn('maintenance_logs', 'planned_end_at')) {
                    $table->timestamp('planned_end_at')->nullable();
                }
                if (!Schema::hasColumn('maintenance_logs', 'due_at')) {
                    $table->timestamp('due_at')->nullable();
                }

                // Mileage tracking
                if (!Schema::hasColumn('maintenance_logs', 'mileage_open_km')) {
                    $table->integer('mileage_open_km')->nullable();
                }
                if (!Schema::hasColumn('maintenance_logs', 'mileage_close_km')) {
                    $table->integer('mileage_close_km')->nullable();
                }

                // Provider information
                if (!Schema::hasColumn('maintenance_logs', 'provider_name')) {
                    $table->string('provider_name')->nullable();
                }
                if (!Schema::hasColumn('maintenance_logs', 'provider_contact')) {
                    $table->string('provider_contact')->nullable();
                }

                // Detailed costs (keep total in a separate column if exists; add granular if missing)
                if (!Schema::hasColumn('maintenance_logs', 'labor_cents')) {
                    $table->integer('labor_cents')->nullable();
                }
                if (!Schema::hasColumn('maintenance_logs', 'parts_cents')) {
                    $table->integer('parts_cents')->nullable();
                }

                // Pause information
                if (!Schema::hasColumn('maintenance_logs', 'paused_at')) {
                    $table->timestamp('paused_at')->nullable();
                }
                if (!Schema::hasColumn('maintenance_logs', 'resumed_at')) {
                    $table->timestamp('resumed_at')->nullable();
                }

                // Indices only if columns exist
                if (Schema::hasColumn('maintenance_logs', 'status')) {
                    $table->index('status');
                }
                if (Schema::hasColumn('maintenance_logs', 'planned_start_at')) {
                    $table->index('planned_start_at');
                }
                if (Schema::hasColumn('maintenance_logs', 'opened_at')) {
                    $table->index('opened_at');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('maintenance_logs')) {
            Schema::table('maintenance_logs', function (Blueprint $table) {
                // Drop columns if they exist
                foreach ([
                    'severity', 'priority', 'planned_start_at', 'planned_end_at', 'due_at',
                    'mileage_open_km', 'mileage_close_km', 'provider_name', 'provider_contact',
                    'labor_cents', 'parts_cents', 'paused_at', 'resumed_at',
                ] as $col) {
                    if (Schema::hasColumn('maintenance_logs', $col)) {
                        $table->dropColumn($col);
                    }
                }
                // Skip dropping indexes explicitly to avoid errors on unknown names
            });
        }
    }
};
