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
        Schema::create('maintenance_attachments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('maintenance_log_id')->constrained('maintenance_logs')->cascadeOnDelete();
            $table->string('label')->nullable();
            $table->string('path');
            $table->string('mime_type');
            $table->integer('size_bytes');
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_attachments');
    }
};
