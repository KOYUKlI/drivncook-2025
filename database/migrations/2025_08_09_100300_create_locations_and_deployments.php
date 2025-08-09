<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('address')->nullable();
            $table->string('city', 120)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->decimal('lat', 9, 6)->nullable();
            $table->decimal('lng', 9, 6)->nullable();
            $table->timestamps();
        });

        Schema::create('truck_deployments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('truck_id')->constrained()->cascadeOnDelete();
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at')->nullable();
            $table->timestamps();
            $table->index(['truck_id','starts_at','ends_at'], 'ix_td_range');
        });
    }
    public function down(): void {
        Schema::dropIfExists('truck_deployments');
        Schema::dropIfExists('locations');
    }
};
