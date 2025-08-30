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
            $table->string('id', 26)->primary();
            $table->string('maintenance_log_id', 26);
            $table->string('label')->nullable();
            $table->string('path');
            $table->string('mime_type');
            $table->integer('size_bytes');
            $table->string('uploaded_by', 26)->nullable();
            $table->timestamps();
            
            $table->foreign('maintenance_log_id')
                  ->references('id')
                  ->on('maintenance_logs')
                  ->onDelete('cascade');
                  
            $table->foreign('uploaded_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
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
