<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('truck_ownerships', function (Blueprint $t) {
            $t->ulid('id')->primary();
            $t->foreignUlid('truck_id')->constrained('trucks')->cascadeOnDelete();
            $t->foreignUlid('franchisee_id')->nullable()->constrained('franchisees')->nullOnDelete();
            $t->timestamp('started_at')->useCurrent();
            $t->timestamp('ended_at')->nullable();
            $t->timestamps();
            // One active ownership per truck at a time (ended_at NULL)
            $t->unique(['truck_id', 'ended_at']);
            $t->index('franchisee_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('truck_ownerships');
    }
};
