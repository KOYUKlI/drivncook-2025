<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trucks', function (Blueprint $t) {
            // Identity fields
            $t->string('code')->nullable()->unique()->after('id');
            $t->string('name')->nullable()->after('code');
            // Keep existing 'plate' as canonical; expose as plate_number in model
            // VIN and build info
            $t->string('vin')->nullable()->unique()->after('plate');
            $t->string('make')->nullable()->after('vin');
            $t->string('model')->nullable()->after('make');
            $t->unsignedSmallInteger('year')->nullable()->after('model');
            // Lifecycle
            $t->date('acquired_at')->nullable()->after('status');
            // commissioned_at is mapped to existing service_start column at model layer
            $t->unsignedInteger('mileage_km')->nullable()->after('service_start');
            $t->text('notes')->nullable()->after('mileage_km');
            // Documents (stored privately)
            $t->string('registration_doc_path')->nullable()->after('notes');
            $t->string('insurance_doc_path')->nullable()->after('registration_doc_path');
        });
    }

    public function down(): void
    {
        Schema::table('trucks', function (Blueprint $t) {
            $t->dropColumn([
                'code', 'name', 'vin', 'make', 'model', 'year',
                'acquired_at', 'mileage_km', 'notes',
                'registration_doc_path', 'insurance_doc_path',
            ]);
        });
    }
};
