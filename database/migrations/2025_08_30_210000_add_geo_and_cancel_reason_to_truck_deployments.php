<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('truck_deployments', function (Blueprint $table) {
            $table->decimal('geo_lat', 10, 7)->nullable()->after('location_text');
            $table->decimal('geo_lng', 10, 7)->nullable()->after('geo_lat');
            $table->text('cancel_reason')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('truck_deployments', function (Blueprint $table) {
            $table->dropColumn(['geo_lat', 'geo_lng', 'cancel_reason']);
        });
    }
};
