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
        if (!Schema::hasTable('report_pdfs')) {
            // Create the table with the final structure directly
            Schema::create('report_pdfs', function (Blueprint $table) {
                $table->id();
                $table->string('type');
                $table->integer('year');
                $table->integer('month');
                $table->string('storage_path');
                $table->bigInteger('file_size')->nullable();
                $table->foreignId('franchisee_id')->nullable()->constrained()->nullOnDelete();
                $table->timestamps();
            });
        } else {
            // Update existing table structure
            Schema::table('report_pdfs', function (Blueprint $table) {
                // Only make changes if columns exist
                if (Schema::hasColumn('report_pdfs', 'user_id')) {
                    $table->renameColumn('user_id', 'franchisee_id');
                } else if (!Schema::hasColumn('report_pdfs', 'franchisee_id')) {
                    $table->foreignId('franchisee_id')->nullable()->constrained()->nullOnDelete();
                }

                // Add year and month columns if they don't exist
                if (!Schema::hasColumn('report_pdfs', 'year')) {
                    $table->integer('year')->after('type');
                }
                
                if (!Schema::hasColumn('report_pdfs', 'month')) {
                    $table->integer('month')->after('year');
                }

                // Drop period column if it exists
                if (Schema::hasColumn('report_pdfs', 'period')) {
                    $table->dropColumn('period');
                }

                // Rename path to storage_path if path exists
                if (Schema::hasColumn('report_pdfs', 'path') && !Schema::hasColumn('report_pdfs', 'storage_path')) {
                    $table->renameColumn('path', 'storage_path');
                }

                // Add file_size column if it doesn't exist
                if (!Schema::hasColumn('report_pdfs', 'file_size')) {
                    $table->bigInteger('file_size')->nullable()->after('storage_path');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('report_pdfs')) {
            Schema::table('report_pdfs', function (Blueprint $table) {
                if (Schema::hasColumn('report_pdfs', 'franchisee_id')) {
                    $table->dropConstrainedForeignId('franchisee_id');
                    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                }
                
                if (Schema::hasColumn('report_pdfs', 'year')) {
                    $table->dropColumn('year');
                }
                
                if (Schema::hasColumn('report_pdfs', 'month')) {
                    $table->dropColumn('month');
                }
                
                if (Schema::hasColumn('report_pdfs', 'file_size')) {
                    $table->dropColumn('file_size');
                }
                
                if (!Schema::hasColumn('report_pdfs', 'period')) {
                    $table->string('period')->after('type');
                }
                
                if (Schema::hasColumn('report_pdfs', 'storage_path') && !Schema::hasColumn('report_pdfs', 'path')) {
                    $table->renameColumn('storage_path', 'path');
                }
            });
        }
    }
};
