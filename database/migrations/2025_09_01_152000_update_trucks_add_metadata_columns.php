<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('trucks')) {
            return;
        }

        Schema::table('trucks', function (Blueprint $t) {
            if (!Schema::hasColumn('trucks', 'code')) {
                $t->string('code')->nullable()->after('id');
                $t->unique('code');
            }
            if (!Schema::hasColumn('trucks', 'name')) {
                $t->string('name')->nullable()->after('code');
            }
            if (!Schema::hasColumn('trucks', 'vin')) {
                $t->string('vin')->nullable()->after('plate');
            }
            if (!Schema::hasColumn('trucks', 'make')) {
                $t->string('make')->nullable()->after('vin');
            }
            if (!Schema::hasColumn('trucks', 'model')) {
                $t->string('model')->nullable()->after('make');
            }
            if (!Schema::hasColumn('trucks', 'year')) {
                $t->unsignedSmallInteger('year')->nullable()->after('model');
            }
            if (!Schema::hasColumn('trucks', 'acquired_at')) {
                $t->date('acquired_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('trucks', 'mileage_km')) {
                $t->unsignedInteger('mileage_km')->default(0)->after('service_start');
            }
            if (!Schema::hasColumn('trucks', 'notes')) {
                $t->text('notes')->nullable()->after('mileage_km');
            }
            if (!Schema::hasColumn('trucks', 'registration_doc_path')) {
                $t->string('registration_doc_path')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('trucks', 'insurance_doc_path')) {
                $t->string('insurance_doc_path')->nullable()->after('registration_doc_path');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('trucks')) {
            return;
        }

        Schema::table('trucks', function (Blueprint $t) {
            foreach ([
                'insurance_doc_path', 'registration_doc_path', 'notes', 'mileage_km',
                'acquired_at', 'year', 'model', 'make', 'vin', 'name', 'code'
            ] as $col) {
                if (Schema::hasColumn('trucks', $col)) {
                    $t->dropColumn($col);
                }
            }
        });
    }
};
