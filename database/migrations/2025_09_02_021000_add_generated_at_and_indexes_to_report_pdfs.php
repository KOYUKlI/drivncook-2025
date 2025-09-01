<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('report_pdfs')) {
            return;
        }

        Schema::table('report_pdfs', function (Blueprint $table) {
            if (! Schema::hasColumn('report_pdfs', 'generated_at')) {
                $table->timestamp('generated_at')->nullable()->after('storage_path');
            }

            // Add helpful indexes if they don't exist
            try { $table->index(['franchisee_id', 'year', 'month'], 'report_pdfs_fym_idx'); } catch (\Throwable $e) {}
            try { $table->index(['type', 'year', 'month'], 'report_pdfs_type_period_idx'); } catch (\Throwable $e) {}
            // Optional unique guard to avoid duplicates per period/type/franchisee
            try { $table->unique(['type', 'franchisee_id', 'year', 'month'], 'report_pdfs_unique_period'); } catch (\Throwable $e) {}
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('report_pdfs')) {
            return;
        }

        Schema::table('report_pdfs', function (Blueprint $table) {
            if (Schema::hasColumn('report_pdfs', 'generated_at')) {
                $table->dropColumn('generated_at');
            }
            try { $table->dropIndex('report_pdfs_fym_idx'); } catch (\Throwable $e) {}
            try { $table->dropIndex('report_pdfs_type_period_idx'); } catch (\Throwable $e) {}
            try { $table->dropUnique('report_pdfs_unique_period'); } catch (\Throwable $e) {}
        });
    }
};
