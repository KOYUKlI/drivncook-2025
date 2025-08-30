<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('truck_deployments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('truck_id')->constrained('trucks')->cascadeOnDelete();
            $table->foreignUlid('franchisee_id')->nullable()->constrained('franchisees')->nullOnDelete();
            $table->string('location_text');
            $table->timestamp('planned_start_at')->nullable();
            $table->timestamp('planned_end_at')->nullable();
            $table->timestamp('actual_start_at')->nullable();
            $table->timestamp('actual_end_at')->nullable();
            $table->enum('status', ['planned', 'open', 'closed', 'cancelled'])->index();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('truck_id');
            $table->index('franchisee_id');
            $table->index('planned_start_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('truck_deployments');
    }
};
