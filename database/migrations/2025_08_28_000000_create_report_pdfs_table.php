<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Migration reprise dans 2025_08_28_175817_update_report_pdfs_table_schema.php
        // pour éviter les conflits
    }

    public function down(): void
    {
        Schema::dropIfExists('report_pdfs');
    }
};
