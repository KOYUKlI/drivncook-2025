<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('franchise_applications', function (Blueprint $t) {
            $t->ulid('id')->primary();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // compte "candidat"
            $t->string('full_name');
            $t->string('email');
            $t->string('phone')->nullable();
            $t->string('company_name')->nullable();
            $t->string('desired_area')->nullable();
            $t->boolean('entry_fee_ack')->default(false);  // accepte 50k€ [IMPLICITE]
            $t->boolean('royalty_ack')->default(false);    // accepte 4% CA [IMPLICITE]
            $t->boolean('central80_ack')->default(false);  // accepte 80/20 [IMPLICITE]
            $t->enum('status', ['draft', 'submitted', 'prequalified', 'interview', 'approved', 'rejected'])->default('draft');
            $t->text('notes')->nullable();
            $t->timestamps();
            $t->index(['email', 'status']);
        });

        Schema::create('franchise_application_documents', function (Blueprint $t) {
            $t->ulid('id')->primary();
            $t->foreignUlid('franchise_application_id')->constrained('franchise_applications')->cascadeOnDelete();
            $t->string('kind', 40); // id/business_plan/other
            $t->string('path');
            $t->timestamps();
        });

        Schema::create('franchise_application_events', function (Blueprint $t) {
            $t->ulid('id')->primary();
            $t->foreignUlid('franchise_application_id')->constrained('franchise_applications')->cascadeOnDelete();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // agent BO
            $t->string('from_status', 24)->nullable();
            $t->string('to_status', 24)->nullable();
            $t->text('message')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('franchise_application_events');
        Schema::dropIfExists('franchise_application_documents');
        Schema::dropIfExists('franchise_applications');
    }
};
