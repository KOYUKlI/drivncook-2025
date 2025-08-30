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
        Schema::table('report_pdfs', function (Blueprint $table) {
            // Rename user_id to franchisee_id
            $table->renameColumn('user_id', 'franchisee_id');

            // Add year and month columns
            $table->integer('year')->after('type');
            $table->integer('month')->after('year');

            // Rename period to something else or drop it if not needed
            $table->dropColumn('period');

            // Rename path to storage_path
            $table->renameColumn('path', 'storage_path');

            // Add file_size column for better UX
            $table->bigInteger('file_size')->nullable()->after('storage_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('report_pdfs', function (Blueprint $table) {
            $table->renameColumn('franchisee_id', 'user_id');
            $table->dropColumn(['year', 'month', 'file_size']);
            $table->string('period')->after('type');
            $table->renameColumn('storage_path', 'path');
        });
    }
};
