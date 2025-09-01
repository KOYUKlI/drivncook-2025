<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('report_pdfs')) { return; }
        Schema::table('report_pdfs', function (Blueprint $t) {
            try {
                $t->foreignUlid('franchisee_id')->nullable()->change();
            } catch (\Throwable $e) {
                // Some drivers don't support altering FK nullability easily; fallback to dropping and recreating
                try { $t->dropForeign(['franchisee_id']); } catch (\Throwable $e2) {}
                try { $t->ulid('franchisee_id')->nullable()->change(); } catch (\Throwable $e3) {}
                try { $t->foreign('franchisee_id')->references('id')->on('franchisees')->nullOnDelete(); } catch (\Throwable $e4) {}
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('report_pdfs')) { return; }
        Schema::table('report_pdfs', function (Blueprint $t) {
            try { $t->foreignUlid('franchisee_id')->nullable(false)->change(); } catch (\Throwable $e) {}
        });
    }
};
